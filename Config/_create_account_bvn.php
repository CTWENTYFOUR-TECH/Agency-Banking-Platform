<?php
// include('_permission.php');
// $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();
include('db.php');

$userSessionData = getUserSessionData();

// header("Content-Type: application/json"); // Ensure JSON response

if (isset($_POST['submitAccount'])) {

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
        $DateOfBirth = htmlspecialchars(trim(date('m/d/Y', strtotime($_POST['DateOfBirth']))));
        $StreetName = htmlspecialchars(trim($_POST['StreetName']));
        $StateOfOrigin = htmlspecialchars(trim($_POST['StateOfOrigin']));
        $LocalGovtOrgin = htmlspecialchars(trim($_POST['LocalGovtOrgin']));
        $City           = htmlspecialchars(trim($_POST['City']));
        $MothersMaidenName = htmlspecialchars(trim($_POST['MothersMaidenName']));
        $PhoneNumber = htmlspecialchars(trim($_POST['PhoneNumber']));
        $BvnNumber = htmlspecialchars(trim($_POST['BvnNumber']));
        $NIN = htmlspecialchars(trim($_POST['NIN']));
        $EmailAddress = htmlspecialchars(trim($_POST['EmailAddress']));
        $CheckBox = htmlspecialchars(trim($_POST['defaultCheck1']));
        $ReferenceNumber = htmlspecialchars(trim($_POST['ReferenceNumber']));
        $GroupID = htmlspecialchars(trim($_POST['GroupID']));

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
        $otpWema = htmlspecialchars(trim($_POST['otp_wema']));
        $ocpApim = $_ENV['OCP_APIM'];
        $clientId = $_ENV['CLIENT_ID'];
        $fcmburl = $_ENV['FCMB_URL'];
        $wemaurl = $_ENV['WEMA_URL'];
        $wema_api_key = $_ENV['WEMA_API_KEY'];
        $wema_ocp_apim = $_ENV['WEMA_OCP_APIM_SUB_KEY'];
        $TrackingId = htmlspecialchars(trim($_POST['trackingID']));

        $RequestID = date('Ymdhis').rand();

        // Get FCMB TOKEN
        $token_url = $_ENV['BASE_URL'];
        $responseToken = getFcmbToken($token_url);

        // Decode the token
        $decodeToken = json_decode($responseToken, true);
        // X Token
        $xtoken = $decodeToken['x-token'];
        $utcTimeStamp = $decodeToken['UTCTimestamp'];

        // var_dump($ConsentCode); die();

    if($BankCode === '000013'){

        $BankName = "GTBANK";
        
        $gtburl = $_ENV['GTBANK_BASE_URL'];

        $datagtb =  [
                    
                    'RequestId'=> $RequestID,
                    'FirstName'=> $FirstName,
                    'MiddleName'=>$MiddleName,
                    'LastName'=>$LastName,
                    'Gender'=>$Gender,
                    'Channel'=>'TP-C24',
                    'DateOfBirth'=>$DateOfBirth,
                    'StreetName'=>$StreetName,
                    'City'=>$City,
                    'EmailAddress'=>$EmailAddress,
                    'PhoneNumber'=>$PhoneNumber,
                    'CustomerImage'=>$out_imageConvert,
                    'CustomerSignature'=>$out_signConvert,
                    'AccountOpeningBalance'=> "0",
                    'AgentAcc'=>$AgentAccount,
                    'AuthMode'=>$AuthMode,
                    'AuthValue'=>$AuthValue,
                    'BankVerificationNumber'=>$BvnNumber,
                    'UserId'=>$UserId,
                    'stateOfOrigin'=>$StateOfOrigin,
                    'LocalGovtArea'=>$LocalGovtOrgin,
                    'MothersMaidenName'=>$MothersMaidenName,
                    'PCCode'=>$PCCode,
                    'AgentWalletID'=>$AgentCode,
                    'NdprConsentFlag'=>$CheckBox,
                    'ReferenceNumber'=>$ReferenceNumber,
                    'NdprCode'=> $ConsentCode
            ];


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $gtburl.'/Api/AccountOpening3',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,  // Set timeout to prevent long waits
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($datagtb),

            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $responsegtb = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

        if (curl_errno($curl)) {
            logError(curl_error($curl));
            showError("cURL error: " . curl_error($curl));
        }

        curl_close($curl);

        $dataResponsegtb = json_decode($responsegtb, true);

        
        $clean = stripslashes($responsegtb);

        // Step 2: Decode JSON
        $data = json_decode($clean, true);

        // echo json_encode($data);
        if(isset($data['responseCode']) && $data['responseCode'] == "10"){
            logData("Request Payload: ", "Customer exists with an account with GTBank");
            showError("Customer exists with an account with GTBank");
        }

        // Log API response (optional, for debugging)
        logData("GTB Decoded Response", $responsegtb);
        logData("Request Payload: ",json_encode($datagtb));
        showError($dataResponsegtb['ResponseDescription']);

        // Check if JSON decoding was successful
        // if (!$dataResponsegtb) {
        //     logData("GTB Decoded Response", $responsegtb);
        //     showError("Invalid response from server. Raw Response". $dataResponsegtb);
        // }


        // Handle API errors
        if (isset($dataResponsegtb['ResponseCode']) && $dataResponsegtb['ResponseCode'] !== '00') {
            logData($dataResponsegtb['ResponseDescription']);
            showError($dataResponsegtb['ResponseDescription'] ?? 'Unknown error from API');
        }else{
            $AccountNumber = $dataResponsegtb['AccountNumber'];
            $AccountName = $dataResponsegtb['AccountName'];
            showSuccess(
                "Account Opening", 
                $AccountNumber
            );

            createSavingsAccount(
                $conn,
                $ReferenceNumber,
                $AgentCode,
                $AggregatorCode,
                $FirstName,
                $LastName,
                $MiddleName,
                $Gender,
                $DateOfBirth,
                $EmailAddress,
                $StreetName,
                $StateOfOrigin,
                $City,
                $GroupID,
                $BankCode,
                $BankName,
                $AccountNumber,
                $BvnNumber,
                $PhoneNumber,
                $AccountName
            );
        }

    }else if($BankCode === '000003') {  //for FCMB
        
        $BankName = "FCMB";
        // $fcmburl = 'https://devapi.fcmb.com/OpenAccount-clone/api/Accounts/v1';

        $dataFcmb =  [
                    // Harcoded fields
                    "accountOfficerCode" => "127CB",
                    "brokerCode" => "X0000",
                    "branchCode"=> "001",
                    "configCredentials"=> [
                        "clientId" => $clientId,
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
                        "state"=>strtoupper(substr($StateOfOrigin, 0, 2)),
                        "country"=> "NG"
                    ],

                    "image"=>$out_imageConvert,
                    "bvnMobileNumber"=>$PhoneNumber,
                    "bvn"=>$BvnNumber,
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


        $curlFcmb = curl_init();
        curl_setopt_array($curlFcmb, [
            CURLOPT_URL => $fcmburl.'/CreateRetailAccountWithBvn',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,  // Set timeout to prevent long waits
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataFcmb),

            CURLOPT_HTTPHEADER => [
                CURLOPT_HTTPHEADER => array(
                    'Ocp-Apim-Subscription-Key: '.$ocpApim,
                    'UTCTimestamp: '.$utcTimeStamp,
                    'x-token: '.$xtoken,
                    'Client_id: '.$clientId,
                    'Content-Type: application/json'
                )
            ],
        ]);

        $responseFcmb = curl_exec($curlFcmb);
        $httpCode = curl_getinfo($curlFcmb, CURLINFO_HTTP_CODE); // Get HTTP status code

        if (curl_errno($curlFcmb)) {
            logError(curl_error($curlFcmb));
            showError("cURL error: " . curl_error($curlFcmb));
        }

        curl_close($curlFcmb);

        // if($httpCode !== 200){
        //     logError($responseFcmb);
        //     showError("Response Error: " . $responseFcmb);
        // }



        $decodedResponse = json_decode($responseFcmb, true);

        // Log API response (optional, for debugging)
        

        // Check if JSON decoding was successful
        if (!$decodedResponse) {
            logData("FCMB Decoded Response", $decodedResponse);
            showError("Invalid response from server. Raw Response". $responseFcmb);
            logData("Request Payload: ",json_encode($dataFcmb));
        }

        // Handle API errors
        if (isset($decodedResponse['code']) && $decodedResponse['code'] !== '00' && $httpCode !== 200) {
            logError("Unknown error from API");
            showError($decodedResponse['description'] ?? "Unknown error from API.");
            exit;
        }else{
            // Account Number and Account Name
            $AccountNumber = $decodedResponse['data']['accountNumber'];
            $AccountName = $decodedResponse['data']['accountName'];

            showSuccess(
                "Account Opening", 
                $AccountNumber ." FCMB Account Opened Successfully"
            );
            logData("FCMB Successful Opening", $AccountNumber);

            // Insert Into DB

            createSavingsAccount(
                $conn,
                $ReferenceNumber,
                $AgentCode,
                $AggregatorCode,
                $FirstName,
                $LastName,
                $MiddleName,
                $Gender,
                $DateOfBirth,
                $EmailAddress,
                $StreetName,
                $StateOfOrigin,
                $City,
                $GroupID,
                $BankCode,
                $BankName,
                $AccountNumber,
                $BvnNumber,
                $PhoneNumber,
                $AccountName
            );
        }
    } //End of FCMB

    // For WEMA BANK

    else if($BankCode === '000017') {  //for FCMB
        
        $BankName = "WEMA";

        // Pass the tracking ID

            $dataOtp =  [
                // Harcoded fields
                "phoneNumber"=> $PhoneNumber,
                "otp" => $otpWema,
                "trackingId" => $TrackingId
            ];

        $curlOtp = curl_init();
        curl_setopt_array($curlOtp, [
            CURLOPT_URL => $wemaurl.'/CustomerAccount/ValidateBVNandEnqueueAccountCreation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,  // Set timeout to prevent long waits
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataOtp),

            CURLOPT_HTTPHEADER => [
                CURLOPT_HTTPHEADER => array(
                    'Ocp-Apim-Subscription-Key: '.$wema_ocp_apim,
                    'x-api-key: '.$wema_api_key,
                    'Content-Type: application/json'
                )
            ],
        ]);

        $responseOtp = curl_exec($curlOtp);
        $httpCode = curl_getinfo($curlOtp, CURLINFO_HTTP_CODE); // Get HTTP status code

        if (curl_errno($curlOtp)) {
            logError(curl_error($curlOtp));
            showError("cURL error: " . curl_error($curlOtp));
        }

        curl_close($curlOtp);

        $decodedResponseOtp = json_decode($responseOtp, true);

        // Log API response (optional, for debugging)
        

        // Check if JSON decoding was successful
        if (!$decodedResponseOtp) {
            logData("WEMA Decoded Response", $decodedResponseOtp);
            showError("Invalid response from server. Raw Response". $responseOtp);
            logData("Request Payload: ",json_encode($dataOtp));
        }

        // Handle API errors
            if (isset($decodedResponseOtp['status']) && $decodedResponseOtp['status'] !== true && $httpCode !== 200) {
                logError("Wema Unknown error from API");
                showError($decodedResponseOtp['message'] ?? "Wema Unknown error from API.");
                exit;
            }

            showSuccess(
                "Wema Account Opening", 
                $decodedResponseOtp['message']
            );
            logData("Wema Successful Opening", $decodedResponseOtp['message']);

            // Insert Into DB

            createSavingsAccount(
                $conn,
                $ReferenceNumber,
                $AgentCode,
                $AggregatorCode,
                $FirstName,
                $LastName,
                $MiddleName,
                $Gender,
                $DateOfBirth,
                $EmailAddress,
                $StreetName,
                $StateOfOrigin,
                $City,
                $GroupID,
                $BankCode,
                $BankName,
                $AccountNumber,
                $BvnNumber,
                $PhoneNumber,
                $AccountName
            );
    }
}

// else{
//     showError("Invalid request method.");
// }
/**
 * Shows error message using SweetAlert
 */
function showError($message) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '".addslashes($message)."',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'btn btn-danger'
            }
        }).then(() => window.location.href='../AccountOpeningValidation');
    </script>";
}

/**
 * Shows success message using SweetAlert
 */
function showSuccess($title, $message) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '".addslashes($title)."',
            text: '".addslashes($message)."',
            confirmButtonText: 'Continue',
            customClass: {
                confirmButton: 'btn btn-success'
            }
        }).then(() => window.location.href='../AccountOpeningValidation');
    </script>";
}

/**
 * Logs data to file
 */
function logData($action, $data) {
    $log = sprintf(
        "[%s] %s: %s - %s\n%s\n\n",
        date("Y-m-d H:i:s"),
        $_SERVER['REMOTE_ADDR'],
        $_SESSION['login_session'] ?? 'unknown',
        $action,
        is_string($data) ? $data : json_encode($data)
    );
    file_put_contents('log_'.date("Y-m-d").'.log', $log, FILE_APPEND);
}

/**
 * Logs errors
 */
function logError($message) {
    $log = sprintf(
        "[%s] ERROR - %s: %s\n\n",
        date("Y-m-d H:i:s"),
        $_SERVER['REMOTE_ADDR'],
        $message
    );
    file_put_contents('error_'.date("Y-m-d").'.log', $log, FILE_APPEND);
}

function getFcmbToken($token_url){

    $curl = curl_init();

curl_setopt_array($curl, array(

  CURLOPT_URL => $token_url.'/api/generate-token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$responseToken = curl_exec($curl);

curl_close($curl);
return $responseToken;
}


function createSavingsAccount(
    $conn,
    $ReferenceNumber,
    $AgentCode,
    $AggregatorCode,
    $FirstName,
    $LastName,
    $MiddleName,
    $Gender,
    $DateOfBirth,
    $EmailAddress,
    $StreetName,
    $StateOfOrigin,
    $City,
    $GroupID,
    $BankCode,
    $BankName,
    $AccountNumber,
    $BvnNumber,
    $PhoneNumber,
    $AccountName
) {
    $sql = "INSERT INTO `lukeport_savings_account` 
            (`ReferenceNo`, `AgentCode`, `AggregatorCode`, `FirstName`, `LastName`, `MiddleName`, 
            `Gender`, `DateOfBirth`, `EmailAddress`, `Address`, `State`, `City`, `Roles`, 
            `BankCode`, `BankName`, `AccountNumber`, `BVN`, `PhoneNumber`, `AccountName`) 
            VALUES 
            (:referenceNo, :agentCode, :aggregatorCode, :firstName, :lastName, :middleName, 
            :gender, :dateOfBirth, :emailAddress, :address, :state, :city, :roles, 
            :bankCode, :bankName, :accountNumber, :bvn, :phoneNumber, :accountName)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':referenceNo', $ReferenceNumber);
        $stmt->bindParam(':agentCode', $AgentCode);
        $stmt->bindParam(':aggregatorCode', $AggregatorCode);
        $stmt->bindParam(':firstName', $FirstName);
        $stmt->bindParam(':lastName', $LastName);
        $stmt->bindParam(':middleName', $MiddleName);
        $stmt->bindParam(':gender', $Gender);
        $stmt->bindParam(':dateOfBirth', $DateOfBirth);
        $stmt->bindParam(':emailAddress', $EmailAddress);
        $stmt->bindParam(':address', $StreetName);
        $stmt->bindParam(':state', $StateOfOrigin);
        $stmt->bindParam(':city', $City);
        $stmt->bindParam(':roles', $GroupID);
        $stmt->bindParam(':bankCode', $BankCode);
        $stmt->bindParam(':bankName', $BankName);
        $stmt->bindParam(':accountNumber', $AccountNumber);
        $stmt->bindParam(':bvn', $BvnNumber);
        $stmt->bindParam(':phoneNumber', $PhoneNumber);
        $stmt->bindParam(':accountName', $AccountName);

        $result = $stmt->execute();
        
        return $result ? true : false;
        
    } catch (PDOException $e) {
        // Log or handle the error as needed
        error_log("Error creating savings account: " . $e->getMessage());
        return false;
    }
}

?>