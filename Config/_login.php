<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailAddress = htmlspecialchars(trim($_POST['emailAddress']));
    $password = htmlspecialchars(trim($_POST['password']));

    $apiKey = '40c33ea83f80fe9f33114f52deb29f85f2adfae9603b32f3e4cdb9c91120a9b3cc97db061d8822e51dc891e393fef0ef593fc81cdb9109fa8b679766969c9326';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://lukeportservice.cpay.ng/lukeportservice/api/auth/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            'LoginID' => $emailAddress,
            'Password' => $password,
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' .$apiKey
        ),
    ));

    $responseLogin = curl_exec($curl);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

    if (curl_errno($curl)) {
        echo json_encode(['status' => 'error', 'message' => 'cURL error: ' . curl_error($curl)]);
        curl_close($curl);
        exit;
    }

    curl_close($curl);
    $decodedResponse = json_decode($responseLogin, true);

    // DEBUGGING: Log API response for debugging purposes (optional)
    file_put_contents("debug_log.txt", $responseLogin, FILE_APPEND);

    // Check if JSON decoding was successful
    if (!$decodedResponse) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid response from server. Raw Response: ' . $responseLogin]);
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

    // Ensure LoginData is present
    if (!isset($decodedResponse['LoginData'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing login data in response.']);
        exit;
    }

    if($decodedResponse['LoginData']['AccountStatus'] == 'Deactivated'){
        echo json_encode(['status' => 'error', 'message' => 'Account is Deactivated. Contact your administrator']);
        exit;
    }

    // Store LoginData in SESSION
    $_SESSION['LoginData'] = $decodedResponse['LoginData'];
    $_SESSION['SecretKey'] = $decodedResponse['secretKey'];
    // Redirect based on FirstTimeLogin

    file_put_contents("debug_log.txt", $_SESSION['LoginData']['AccountStatus'], FILE_APPEND);
    $redirectUrl = ($_SESSION['LoginData']['AccountStatus'] == 0) ? 'http://127.0.0.1/agency-banking-platform/Settings' : 'http://127.0.0.1/agency-banking-platform/Dashboard';

    echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
}
?>

