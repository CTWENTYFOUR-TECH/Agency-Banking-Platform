<?php
include('_permission.php');

$userSessionData = getUserSessionData();

$apiKey = $userSessionData['secretKey']; 
$agentCode = $userSessionData['agentCode'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phoneNumber = htmlspecialchars(trim($_POST['phoneNumber']));
    $bvn = htmlspecialchars(trim($_POST['bvn']));
    $dateofBirth = htmlspecialchars(trim($_POST['dateofbirth']));
    $residentialAddress = htmlspecialchars(trim($_POST['residentialAddress']));
    $businessAddress = htmlspecialchars(trim($_POST['businessAddress']));
    $landmark = htmlspecialchars(trim($_POST['landmark']));
    $state = htmlspecialchars(trim($_POST['state']));
    $city = htmlspecialchars(trim($_POST['city']));
    $businessName = htmlspecialchars(trim($_POST['businessName']));
    $registeredNo = htmlspecialchars(trim($_POST['registeredNo']));
    $loginID = htmlspecialchars(trim($_POST['email']));

    $base_url = 'https://lukeportservice.cpay.ng/lukeportservice';


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url.'/api/agent/onboard/continuation',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "LoginID"=> $loginID,
            "PhoneNumber"=> $phoneNumber,
            "BVN"=> $bvn,
            "ResidentialAddress"=> $residentialAddress,
            "BusinessAddress"=> $businessAddress,
            "Landmark"=> $landmark,
            "DateofBirth"=> $dateofBirth,
            "State"=> $state,
            "City"=> $city,
            "BusinessName"=> $businessName,
            "RegisteredNo"=> $registeredNo,
            "AgentCode"=> $agentCode
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
    $redirectUrl = './_logout.php'; //

    echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
}
?>

