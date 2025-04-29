<?php
include('_permission.php');

$userSessionData = getUserSessionData();

header("Content-Type: application/json"); // Ensure JSON response

// Validate session key

$userEmail = $userSessionData['emailAddress']; 
$apikey = $userSessionData['secretKey']; 

if (!isset($userEmail) || empty($userEmail)) {
    echo json_encode(['status' => 'error', 'message' => 'User Email is required.']);
    exit;
}
    // Validate input field

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://lukeportservice.cpay.ng/lukeportservice/api/logout',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            'loginID' => $userEmail
            // 'token' => $apikey
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: '.$apikey
        ),
    ));

    $resUserEmail = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

    if (curl_errno($curl)) {
        echo json_encode(['status' => 'error', 'message' => 'cURL error: ' . curl_error($curl)]);
        curl_close($curl);
        exit;
    }

    curl_close($curl);

    $decodedResponse = json_decode($resUserEmail, true);

    // Log API response (optional, for debugging)
    file_put_contents("debug_log.txt", date("Y-m-d H:i:s") . " - API Response Roles: " . $resUserEmail . PHP_EOL, FILE_APPEND);

    // Check if JSON decoding was successful
    if (!$decodedResponse) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid response from server. Raw Response: ' . $resUserEmail
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

    // $redirectUrl = 'http://127.0.0.1/agency-banking-platform/Logout';
    // // Success response
    // echo json_encode([
    //     'status' => 'success',
    //     'message' => 'Logout Successfully!',
    //     'redirectUrl' => $redirectUrl
    // ]);
    // exit;
    header('Location:http://127.0.0.1/agency-banking-platform/Authentication/signin');
?>
