<?php
include('_permission.php');

// Start session and get user data
$userSessionData = getUserSessionData();

// Set headers first to ensure JSON response
header("Content-Type: application/json"); 
header("X-Content-Type-Options: nosniff");

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
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
    'roleName' => 'Group Name',
    'groupID' => 'Group ID',
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
    "login_id" => $cleanInput['loginID'],
    "group_id" => $cleanInput['groupID']
];

$ch = curl_init();
$curlOptions = [
    CURLOPT_URL => $base_url.'/api/getUserRole',
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
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Handle cURL errors
if (!empty($curlError)) {
    http_response_code(502);
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

file_put_contents($debugLogFile, json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

$rolesObject = $apiResponse['Roles'][0] ?? [];

// Define all possible roles with proper extraction
$allRoles = [
    'CreateUser' => (int)($rolesObject['CreateUser'] ?? 0),
    'UpdateUserRole' => (int)($rolesObject['UpdateUserRole'] ?? 0),
    'ViewUser' => (int)($rolesObject['ViewUser'] ?? 0),
    'DeleteUser' => (int)($rolesObject['DeleteUser'] ?? 0),
    'EditUser' => (int)($rolesObject['EditUser'] ?? 0),
    'CreateRole' => (int)($rolesObject['CreateRole'] ?? 0),
    'ViewGroup' => (int)($rolesObject['ViewGroup'] ?? 0),
    'UnlockUser' => (int)($rolesObject['UnlockUser'] ?? 0),
    'CreateSubAgent' => (int)($rolesObject['CreateSubAgent'] ?? 0),
    'UpgradeToAggregator' => (int)($rolesObject['UpgradeToAggregator'] ?? 0),
    'DeactivateUser' => (int)($rolesObject['DeactivateUser'] ?? 0),
    'ReactivateUser' => (int)($rolesObject['ReactivateUser'] ?? 0),
    'AccountOpening' => (int)($rolesObject['AccountOpening'] ?? 0),
    'CardIssuance' => (int)($rolesObject['CardIssuance'] ?? 0),
    'AccountOpeningReport' => (int)($rolesObject['AccountOpeningReport'] ?? 0),
    'CardIssuanceReport' => (int)($rolesObject['CardIssuanceReport'] ?? 0),
    'AgentOnboardedReport' => (int)($rolesObject['AgentOnboardedReport'] ?? 0),
    'AggregatorReport' => (int)($rolesObject['AggregatorReport'] ?? 0)
];

// Return the processed roles
echo json_encode([
    'status' => 'success',
    'roles' => $allRoles,
    'groupName' => $cleanInput['roleName'],
    'groupId' => $rolesObject['GroupID'] ?? '',
    'roleId' => $rolesObject['id'] ?? 0
]);
?>