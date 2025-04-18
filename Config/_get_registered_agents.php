<?php
// Ensure no output before headers
ob_start();

include('_permission.php');

// Security headers
header("Content-Type: application/json; charset=UTF-8");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: same-origin");

// $allowedIPs = ['192.168.1.0/24', '10.0.0.0/8'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
//     http_response_code(403);
//     die(json_encode(["error" => "Access forbidden"]));
// }

// Restrict access to specific domains in production
// header("Access-Control-Allow-Origin: https://yourdomain.com");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type");

// Rate limiting - more sophisticated version
// session_start();
$rateLimit = 10; // Requests per minute
$rateLimitWindow = 60; // Seconds

if (!isset($_SESSION['requests'])) {
    $_SESSION['requests'] = [time()];
} else {
    $currentTime = time();
    $_SESSION['requests'] = array_filter($_SESSION['requests'], function($t) use ($currentTime, $rateLimitWindow) {
        return $t > ($currentTime - $rateLimitWindow);
    });
    
    if (count($_SESSION['requests']) >= $rateLimit) {
        http_response_code(429);
        die(json_encode([
            "error" => "Rate limit exceeded",
            "retry_after" => $rateLimitWindow - ($currentTime - $_SESSION['requests'][0])
        ]));
    }
    $_SESSION['requests'][] = $currentTime;
}

// Disable PHP error output
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

try {
    // Validate session and permissions
    $userSessionData = getUserSessionData();
    $permissions = getUserPermissions();
    
    if (empty($userSessionData)) {
        throw new Exception('Session data not found', 401);
    }

    // Validate required parameters
    $requiredParams = [
        'apiKey' => $userSessionData['secretKey'] ?? null,
        'loginID' => $userSessionData['emailAddress'] ?? null,
        'groupID' => $permissions['GroupID'] ?? null,
        'agentCode' => $userSessionData['agentCode'] ?? null
    ];
    
    foreach ($requiredParams as $param => $value) {
        if (empty($value)) {
            throw new Exception("Missing required parameter: $param", 400);
        }
    }

    // Get DataTables request parameters
    $requestData = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $draw = (int)($requestData['draw'] ?? 1);
    $start = (int)($requestData['start'] ?? 0);
    $length = (int)($requestData['length'] ?? 10);
    $searchValue = $requestData['search']['value'] ?? null;
    $orderColumn = (int)($requestData['order'][0]['column'] ?? 0);
    $orderDir = in_array(strtoupper($requestData['order'][0]['dir'] ?? 'ASC'), ['ASC', 'DESC']) ? 
                strtoupper($requestData['order'][0]['dir']) : 'ASC';

    // Prepare API request
    $apiRequest = [
        'login_id' => $requiredParams['loginID'],
        'group_id' => $requiredParams['groupID'],
        'agent_code' => $requiredParams['agentCode'],
        'page' => floor($start / $length) + 1,
        'per_page' => $length,
        'search' => $searchValue,
        'order_column' => $orderColumn,
        'order_dir' => $orderDir
    ];

    // Make authenticated API request
    $ch = curl_init('https://lukeportservice.cpay.ng/lukeportservice/api/getRegisterAgent');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $requiredParams['apiKey'],
            'X-Request-From: Proxy-Server'
        ],
        CURLOPT_POSTFIELDS => json_encode($apiRequest),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $apiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode !== 200 || !$apiResponse) {
        throw new Exception('API request failed: ' . curl_error($ch), 502);
    }

    $response = json_decode($apiResponse, true);
    curl_close($ch);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid API response format', 502);
    }

    // Transform to DataTables format
    $output = [
        "draw" => $draw,
        "recordsTotal" => $response['Pagination']['total_items'] ?? 0,
        "recordsFiltered" => $response['Pagination']['total_items'] ?? 0,
        "data" => $response['AgentsData'] ?? []
    ];

    // Cache control (adjust as needed)
    header('Cache-Control: no-store, max-age=0');
    echo json_encode($output);

} catch (Exception $e) {
    // Clean any output
    if (ob_get_length()) ob_clean();
    
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        "draw" => $draw ?? 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => $e->getMessage()
    ]);
    
    // Log the error securely
    error_log("Proxy Error: " . $e->getMessage());
} finally {
    // Flush output buffer
    ob_end_flush();
}
?>