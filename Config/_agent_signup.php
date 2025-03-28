<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $middleName = htmlspecialchars(trim($_POST['middleName']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $emailLoginId = htmlspecialchars(trim($_POST['emailLoginId']));
    $passwordLogin = htmlspecialchars(trim($_POST['passwordLogin']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));

    $base_url = 'https://lukeportservice.cpay.ng/lukeportservice';
    $apiKey = '40c33ea83f80fe9f33114f52deb29f85f2adfae9603b32f3e4cdb9c91120a9b3cc97db061d8822e51dc891e393fef0ef593fc81cdb9109fa8b679766969c9326';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url.'/api/agent/onboarding',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "FirstName" => $firstName,
            "LastName"=>$lastName,
            "MiddleName"=>$middleName,
            "Password"=>$passwordLogin,
            "ConfirmPassword"=>$confirmPassword,
            "Gender"=>$gender,
            "EmailAddress"=>$emailLoginId,
            "Roles"=>"AGENT"
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' .$apiKey
        ),
    ));

    $responseSignUp = curl_exec($curl);
    file_put_contents("debug_log.txt", $responseSignUp, FILE_APPEND);

    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

    if (curl_errno($curl)) {
        echo json_encode(['status' => 'error', 'message' =>  curl_error($curl)]);
        curl_close($curl);
        exit;
    }

    curl_close($curl);

    // Decode the API response
    $decodedResponse = json_decode($responseSignUp, true);

    // file_put_contents("debug_log.txt", $decodedResponse, FILE_APPEND);

    // Check if JSON decoding was successful
    if (!$decodedResponse) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid response from server. Raw Response: ' . $responseSignUp]);
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
    $redirectUrl = './../signin';

    echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
}
?>

