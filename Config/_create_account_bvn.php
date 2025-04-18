<?php
include('_permission.php');
require_once __DIR__ . '/../vendor/autoload.php';

$userSessionData = getUserSessionData();

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

include '_image_upload_convert.php';
include '_sign_upload_convert.php';

// Validate input fields
    $Salutation = htmlspecialchars(trim($_POST['Salutation']));
    $AgentCode = htmlspecialchars(trim($_POST['AgentCode']));
    $AggregatorCode = htmlspecialchars(trim($_POST['AggregatorCode']));
    $BankCode = htmlspecialchars(trim($_POST['BankCode']));
    $FirstName = htmlspecialchars(trim($_POST['FirstName']));
    $MiddleName = htmlspecialchars(trim($_POST['MiddleName']));
    $LastName = htmlspecialchars(trim($_POST['LastName']));
    $Gender = htmlspecialchars(trim($_POST['Gender']));
    $DateOfBirth = htmlspecialchars(trim($_POST['DateOfBirth']));
    $StreetName = htmlspecialchars(trim($_POST['StreetName']));
    $StateOfOrigin = htmlspecialchars(trim($_POST['StateOfOrigin']));
    $LocalGovtOrgin = htmlspecialchars(trim($_POST['LocalGovtOrgin']));
    $City           = htmlspecialchars(trim($_POST['City']));
    $MothersMaidenName = htmlspecialchars(trim($_POST['MothersMaidenName']));
    $PhoneNumber = htmlspecialchars(trim($_POST['PhoneNumber']));
    $BvnNumber = htmlspecialchars(trim($_POST['BvnNumber']));
    $NIN = htmlspecialchars(trim($_POST['NIN']));
    $EmailAddress = htmlspecialchars(trim($_POST['EmailAddress']));

    // GTB
    $PCCode = htmlspecialchars(trim($_POST['PCCode']));
    $ConsentCode = htmlspecialchars(trim($_POST['ConsentCode']));
    $AgentAccount = htmlspecialchars(trim($_POST['AgentAccount']));
    $AuthMode = $_ENV['AUTH_MODE'];
    $AuthValue = $_ENV['AUTH_VALUE'];
    $UserId = $_ENV['GTBANK_USER_ID'];

    // FCMB
    $PreferredNumber = htmlspecialchars(trim($_POST['PreferredNumber']));
    $MaritalStatus = htmlspecialchars(trim($_POST['MaritalStatus']));
    $nok_firstName = htmlspecialchars(trim($_POST['nok_firstName']));
    $nok_surname = htmlspecialchars(trim($_POST['nok_surname']));
    $nok_middlename = htmlspecialchars(trim($_POST['nok_middlename']));
    $nok_dob = htmlspecialchars(trim($_POST['nok_dob']));
    $nok_relationship = htmlspecialchars(trim($_POST['nok_relationship']));
    $nok_phone = htmlspecialchars(trim($_POST['nok_phone']));
    $nok_gender = htmlspecialchars(trim($_POST['nok_gender']));
    $nok_dob = htmlspecialchars(trim($_POST['nok_dob']));
    $ocpApim = $_ENV['OCP_APIM'];
    $clientId = $_ENV['CLIENT_ID'];
    $fcmburl = $_ENV['FCMB_URL'];

    $RequestID = date('Y-m-d his').rand();

    if(empty($FirstName) || empty($LastName) || empty($BvnNumber) || empty($PhoneNumber)){
        echo json_encode([
            'status' => 'error',
            'message' => 'FirstName, LastName, BVN number, PhoneNumber are all required ',
        ]);
        exit;
    }

if($BankCode === '000013'){
    
    $gtburl = $_ENV['GTB_BASE_URL'];

    $datagtb =  [
                
                'requestId'=> $RequestID,
                'firstName'=> $FirstName,
                'middleName'=>$MiddleName,
                'lastName'=>$LastName,
                'gender'=>$Gender,
                'Channel'=>'TP-C24',
                'dateOfBirth'=>$DateOfBirth,
                'streetName'=>$StreetName,
                'city'=>$City,
                'emailAddress'=>$EmailAddress,
                'phoneNumber'=>$PhoneNumber,
                'customerImage'=>$out_imageConvert,
                'customerSignature'=>$out_signConvert,
                'accountOpeningBalance'=> 0,
                'AgentAcc'=>$AgentAccount,
                'AuthMode'=>$AuthMode,
                'AuthValue'=>$AuthValue,
                'BankVerificationNumber'=>$BankVerificationNumber,
                'UserId'=>$UserId,
                'stateOfOrigin'=>$StateOfOrigin,
                'LocalGovtArea'=>$LocalGovtOrgin,
                'MothersMaidenName'=>$MothersMaidenName,
                'PCCode'=>$PCCode,
                'AgentWalletID'=>$AgentCode,
                'NdprConsentFlag'=>$CheckBox,
                'ReferenceNumber'=>$RequestID,
                'NdprCode'=> $ConsentCode
        ];


    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $gtburl.'/Api/AccountOpening3',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,  // Set timeout to prevent long waits
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($datagtb),

        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
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
    file_put_contents("debug_log.txt", date("Y-m-d H:i:s") . " - API Response Unlock: " . $responseUnlock . PHP_EOL, FILE_APPEND);

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

    $allResponseCall = 
        [
                
            'requestId'=> $RequestID,
            'firstName'=> $FirstName,
            'middleName'=>$MiddleName,
            'lastName'=>$LastName,
            'gender'=>$Gender,
            'Channel'=>'TP-C24',
            'dateOfBirth'=>$DateOfBirth,
            'streetName'=>$StreetName,
            'city'=>$City,
            'emailAddress'=>$EmailAddress,
            'phoneNumber'=>$PhoneNumber,
            'customerImage'=>$out_imageConvert,
            'customerSignature'=>$out_signConvert,
            'accountOpeningBalance'=> 0,
            'AgentAcc'=>$AgentAccount,
            'BankVerificationNumber'=>$BankVerificationNumber,
            'stateOfOrigin'=>$StateOfOrigin,
            'LocalGovtArea'=>$LocalGovtOrgin,
            'PCCode'=>$PCCode,
            'AgentWalletID'=>$AgentCode,
            'NdprConsentFlag'=>$CheckBox,
            'ReferenceNumber'=>$RequestID,
            'BankCode' => $BankCode,
            'AccountNumber'=> $decodedResponse['AccountNumber']
        ];

    $dataResponseCallBack = json_encode($allResponseCall);
    // Call the CallBack
    $responseCall = callBackAPI($dataResponseCallBack);

    $redirectUrl = '../AccountOpeningValidation';
    // Success response
    echo json_encode([
        'status' => 'success',
        'message' => 'User Created Successfully',
        'accountNumber' => $decodedResponse['AccountNumber'],
        'redirectUrl' => $redirectUrl
    ]);
exit;
}else if($BankCode === '000003') {  //for FCMB
    $fcmburl = 'https://collection.gtbank.com/SANEF/AccountOpening/';

    $dataFcmb =  [
                // Harcoded fields
                "accountOfficerCode" => "127CB",
                "brokerCode" => "X0000",
                "branchCode"=> "001",
                "configCredentials"=> [
                    "clientId" => "250",
                    "productId" => "14"
                ],
                "declarationClause"=> true,
                "consentClause"=> true,
                "employmentDetails"=>"",
                "referralcode"=> "",
                "isWalletAccount"=> true,
                "addImageFromBVN"=> false,
                "isBvnValidate"=> true,
                "occupation"=> "OTHER",
                "employmentStatus"=> "OTHER",
                // End of Harcoded fields
                "requestId"=> $RequestID,
                "firstName"=> $FirstName,
                "middleName"=>$MiddleName,
                "lastName"=>$LastName,
                "gender"=>$Gender,
                "dob"=>$DateOfBirth,
                "streetName"=>$StreetName,
                "city"=>$City,
                "email"=>$EmailAddress,
                "phoneNumber"=>$PhoneNumber,
                "preferredMobileNumber" => $PreferredNumber,
                "address" => [
                    "addrLine1"=> $StreetName,
                    "houseNum"=> "0",
                    "streetName"=> $StreetName,
                    "city"=> $City,
                    "state"=>$StateOfOrigin,
                    "country"=> "NG"
                ],

                "image"=>$out_imageConvert,
                "bvnMobileNumber"=>$PhoneNumber,
                "bvn"=>$BankVerificationNumber,
                "nin"=>$NIN,
                "motherMaidenName"=>$MothersMaidenName,
                "maritalStatus" => $MaritalStatus,
                "salutation" => $Salutation,
                "nextOfKin" => [
                    "firstName"=> $nok_firstName,
                    "surName"=> $nok_surname,
                    "otherName"=> $nok_middlename,
                    "gender"=> $nok_gender,
                    "dob"=> $nok_dob,
                    "relationship" => $nok_relationship,
                    "phoneNumber"=> $nok_phone
                ]
        ];


    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $fcmburl.'/Api/AccountOpening3',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,  // Set timeout to prevent long waits
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($datagtb),

        CURLOPT_HTTPHEADER => [
            CURLOPT_HTTPHEADER => array(
                'Ocp-Apim-Subscription-Key: '.$ocpApim,
                'UTCTimestamp: '.$utcTimeStamp,
                'x-token: '.$token,
                'Client_id: '.$clientId,
                'Content-Type: application/json'
            )
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
        'message' => 'User Created Successfully',
        'responsePayload' => json_encode($datagtb),
        'redirectUrl' => $redirectUrl
    ]);
    exit;
}

?>