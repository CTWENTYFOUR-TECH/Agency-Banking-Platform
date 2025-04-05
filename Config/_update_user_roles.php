<?php
include('_permission.php');

// Start session and get user data
$userSessionData = getUserSessionData();

// Set headers
header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests allowed']);
    exit;
}

// Configuration
$base_url = 'https://lukeportservice.cpay.ng/lukeportservice';
$apiKey = $userSessionData['secretKey'] ?? null;
$debugLogFile = "role_updates.log";

// Validate session key
if (empty($apiKey)) {
    error_log(date("Y-m-d H:i:s") . " - API Key missing\n", 3, $debugLogFile);
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized: API key missing']);
    exit;
}

// Define all supported roles with default values
$supportedRoles = [
    'CreateUser' => 0,
    'ViewUser' => 0,
    'DeleteUser' => 0,
    'EditUser' => 0,
    'UpdateUserRole' => 0,
    'UnlockUser' => 0,
    'CreateRole' => 0,
    'ViewGroup' => 0,
    'CreateSubAgent' => 0,
    'DeactivateUser' => 0,
    'ReactivateUser' => 0,
    'UpgradeToAggregator' => 0,
    'AccountOpening' => 0,
    'CardIssuance' => 0,
    'CardIssuanceReport' => 0,
    'AccountOpeningReport' => 0,
    'AgentOnboardedReport' => 0,
    'AggregatorReport' => 0
];

// Get and validate input data
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    // Fallback to regular POST if JSON parse fails
    $input = $_POST;
}

error_log("Received input data: " . print_r($input, true));

// Validate required fields
$requiredFields = ['GroupID', 'GroupName', 'LoginID'];
$errors = [];
$cleanInput = [];

foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        $errors[] = "$field is required";
    } else {
        $cleanInput[$field] = htmlspecialchars(trim($input[$field]), ENT_QUOTES, 'UTF-8');
    }
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
    exit;
}

// Process role values
foreach ($supportedRoles as $role => $default) {
    $supportedRoles[$role] = isset($input[$role]) ? 1 : 0;
}

// Prepare request payload
$requestData = [
    'GroupID' => $cleanInput['GroupID'],
    'GroupName' => $cleanInput['GroupName'],
    'LoginID' => $cleanInput['LoginID'],  // Fixed from GroupName to LoginID
    ...$supportedRoles
];

error_log("Prepared request data: " . print_r($requestData, true));

// Initialize cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $base_url.'/api/update_user_role',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($requestData),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'X-Request-ID: ' . bin2hex(random_bytes(8))
    ],
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2
]);

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Log the request and response
error_log(date("Y-m-d H:i:s") . " - Request: " . json_encode($requestData) . "\nResponse: $response\n", 3, $debugLogFile);

// Handle errors
if ($error) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'message' => 'Service unavailable',
        'debug' => (ENVIRONMENT === 'development') ? $error : null
    ]);
    exit;
}

// Validate response
$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(502);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid API response',
        'raw_response' => $response
    ]);
    exit;
}

// Check for API errors
if (!isset($data['ResponseCode']) || $data['ResponseCode'] !== '00') {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $data['ResponseMessage'] ?? 'Update failed',
        'api_response' => $data
    ]);
    exit;
}

// Success response
echo json_encode([
    'status' => 'success',
    'message' => 'Roles updated successfully',
    'data' => $data
]);
?>