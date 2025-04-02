<?php
include('_permission.php');

$userSessionData = getUserSessionData();

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$base_url = 'https://lukeportservice.cpay.ng/lukeportservice';

$apiKey = $userSessionData['secretKey']; 
// $loginID = $userSessionData['emailAddress'];

// Validate session key
if (!isset($apiKey) || empty($apiKey)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. API key missing.']);
    exit;
}

// Validate input fields
$loginEmailConfirmation = isset($_POST['loginEmailConfirmation']) ? trim($_POST['loginEmailConfirmation']) : '';
$loginID = isset($_POST['loginID']) ? trim($_POST['loginID']) : '';
$new_password = isset($_POST['setNewPassword']) ? trim($_POST['setNewPassword']) : '';
$confirm_password = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';


// Sanitize inputs
$loginEmailConfirmation = htmlspecialchars($loginEmailConfirmation, ENT_QUOTES, 'UTF-8');
$loginID = htmlspecialchars($loginID, ENT_QUOTES, 'UTF-8');

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $base_url.'/api/unlockAccount',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,  // Set timeout to prevent long waits
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode([
        "LoginID" => $loginID,
        "LockedAccount" => $loginEmailConfirmation,
        "SetNewPassword" => $new_password,
        "ConfirmPassword" => $confirm_password
    ]),

    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $apiKey
    ],
]);

$responseUnlock = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

if (curl_errno($curl)) {
    echo json_encode(['status' => 'error', 'message' => 'cURL error: ' . curl_error($curl)]);
    curl_close($curl);
    exit;
}

curl_close($curl);

$decodedResponse = json_decode($responseUnlock, true);

// Log API response (optional, for debugging)
// file_put_contents("debug_log.txt", date("Y-m-d H:i:s") . " - API Response Unlock: " . $responseUnlock . PHP_EOL, FILE_APPEND);

// Check if JSON decoding was successful
if (!$decodedResponse) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid response from server. Raw Response: ' . $responseUnlock
    ]);
    exit;
}

// Handle API errors
if (isset($decodedResponse['ResponseCode']) && $decodedResponse['ResponseCode'] !== '00') {
    echo json_encode([
        'status' => 'error',
        'message' => $decodedResponse['ResponseMessage'] ?? 'Unknown error from API.'
    ]);
    exit;
}

$redirectUrl = '';
// Success response
echo json_encode([
    'status' => 'success',
    'message' => 'User Account Unlocked Successfully',
    'redirectUrl' => $redirectUrl
]);
exit;
?>
