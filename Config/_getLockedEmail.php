<?php
include('_permission.php');

// Start session and get user data
$userSessionData = getUserSessionData();

// Set headers first to ensure JSON response
header("Content-Type: application/json"); 
header("X-Content-Type-Options: nosniff");

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error', 
        'message' => 'Invalid request method. Only POST allowed.'
    ]);
    exit;
}

// Configuration
$base_url = 'https://lukeportservice.cpay.ng/lukeportservice';
$apiKey = $userSessionData['secretKey'] ?? null;
$debugLogFile = "debug_log.txt";

// Validate session key
if (empty($apiKey)) {
    error_log(date("Y-m-d H:i:s") . " - API Key is missing\n", 3, $debugLogFile);
    http_response_code(401);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Unauthorized access. API key missing.'
    ]);
    exit;
}

// Validate and sanitize input fields
$requiredFields = [
    'loginEmail' => 'Login Email',
    'loginID' => 'Login ID'
];

$errors = [];
$cleanInput = [];

foreach ($requiredFields as $field => $name) {
    if (empty($_POST[$field])) {
        $errors[] = "$name is required.";
    } else {
        $cleanInput[$field] = htmlspecialchars(trim($_POST[$field]), ENT_QUOTES, 'UTF-8');
    }
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// Prepare API request
$requestData = [
    "LoginID" => $cleanInput['loginID'],
    "LockedAccount" => $cleanInput['loginEmail']
];

$ch = curl_init();
$curlOptions = [
    CURLOPT_URL => $base_url.'/api/lockAccountEmail',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 3,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($requestData),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $apiKey,
        'X-Request-ID: ' . bin2hex(random_bytes(8))
    ],
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2
];

curl_setopt_array($ch, $curlOptions);

// Execute request with error handling
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Debug logging
$debugInfo = [
    'timestamp' => date("Y-m-d H:i:s"),
    'request' => $requestData,
    'response' => $response,
    'http_code' => $httpCode,
    'curl_error' => $curlError
];

// file_put_contents($debugLogFile, json_encode($debugInfo, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

// Handle cURL errors
if (!empty($curlError)) {
    http_response_code(502); // Bad Gateway
    echo json_encode([
        'status' => 'error',
        'message' => 'Service temporarily unavailable',
        'debug' => (ENVIRONMENT === 'development') ? $curlError : null
    ]);
    exit;
}

// Parse API response
$apiResponse = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid response from API service'
    ]);
    exit;
}

// Handle API error responses
if (!isset($apiResponse['ResponseCode']) || $apiResponse['ResponseCode'] !== '00') {
    $errorMessage = $apiResponse['ResponseMessage'] ?? 'Unknown API error';
    echo json_encode([
        'status' => 'error',
        'message' => $errorMessage
    ]);
    exit;
}

// Successful response
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'lockedEmail' => $apiResponse['LockedEmail']
    // 'data' => [
    //     'lockedEmail' => $apiResponse['LockedEmail'] ?? $cleanInput['loginEmail'],
    //     'isLocked' => true,
    //     'failedAttempts' => $apiResponse['FailedAttempts'] ?? null
    // ]
]);
exit;
?>