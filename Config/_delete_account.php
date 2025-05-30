<?php
include('_permission.php');

$userSessionData = getUserSessionData();

$apiKey = $userSessionData['secretKey']; 
$loginID = $userSessionData['emailAddress'];

// Validate API key in session
if (!isset($apiKey) || empty($apiKey)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. API key missing.']);
    exit();
}

$base_url = 'https://lukeportservice.cpay.ng/lukeportservice';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url.'/api/deleteAccount',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            'emailAddress' => $loginID
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' .$apiKey
        ),
    ));

    $responseUpdate = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

    if (curl_errno($curl)) {
        echo json_encode(['status' => 'error', 'message' => 'cURL error: ' . curl_error($curl)]);
        curl_close($curl);
        exit;
    }

    curl_close($curl);
    $decodedResponse = json_decode($responseUpdate, true);

    // DEBUGGING: Log API response for debugging purposes (optional)
    // file_put_contents("debug_log.txt", "updatePassword.php ".$responseUpdate, FILE_APPEND);

    // Check if JSON decoding was successful
    if (!$decodedResponse) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid response from server. Raw Response: ' . $responseUpdate]);
        exit;
    }

    // Check if API returned an error response
    if (isset($decodedResponse['ResponseCode']) && $decodedResponse['ResponseCode'] !== '00') {
        echo json_encode([
            'status' => 'error',
            'message' => $decodedResponse['ResponseMessage'] ?? 'Unknown error from API.'
        ]);
        exit;
    }

    // Redirect based on FirstTimeLogin
    $redirectUrl =  '../Logout';

    echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
}
?>

