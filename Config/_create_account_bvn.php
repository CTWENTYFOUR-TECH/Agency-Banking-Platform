<?php
// include('_permission.php');
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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
        $CheckBox = htmlspecialchars(trim($_POST['defaultCheck1']));
        $ReferenceNumber = htmlspecialchars(trim($_POST['ReferenceNumber']));

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

        // var_dump($ConsentCode); die();

    if($BankCode === '000013'){
        
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
                    'BankVerificationNumber'=>$BankVerificationNumber,
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

        $responsegtb = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

        if (curl_errno($curl)) {
            logError(curl_error($curl));
            showError("cURL error: " . curl_error($curl));
        }

        curl_close($curl);

        $dataResponsegtb = json_decode($responsegtb, true);

        // Log API response (optional, for debugging)
        logData("GTB Decoded Response", $responsegtb);
        logData("Request Payload: ",json_encode($datagtb));
        showError("Invalid response from server. Raw Response". $dataResponsegtb['ResponseDescription']);

        // Check if JSON decoding was successful
        // if (!$dataResponsegtb) {
        //     logData("GTB Decoded Response", $responsegtb);
        //     showError("Invalid response from server. Raw Response". $dataResponsegtb);
        // }

        // Handle API errors
        if (isset($dataResponsegtb['ResponseCode']) && $dataResponsegtb['ResponseCode'] !== '00') {
            logData($dataResponsegtb['ResponseDescription']);
            showError($dataResponsegtb['ResponseDescription'] ?? 'Unknown error from API');
        }
        showSuccess(
            "Account Opening", 
            $dataResponsegtb['AccountNumber']. " GTB Account Opened Successfully"
        );
        logData("GTB Successful Opening", $dataResponsegtb['AccountNumber']);

    }else if($BankCode === '000003') {  //for FCMB
        $fcmburl = 'https://devapi.fcmb.com/OpenAccount-clone/api/Accounts/v1';

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
            CURLOPT_URL => $fcmburl.'/CreateRetailAccountWithBvn',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,  // Set timeout to prevent long waits
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataFcmb),

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

        $responseFcmb = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

        if (curl_errno($curl)) {
            logError(curl_error($curl));
            showError("cURL error: " . curl_error($curl));
        }

        curl_close($curl);

        $decodedResponse = json_decode($responseFcmb, true);

        // Log API response (optional, for debugging)
        

        // Check if JSON decoding was successful
        if (!$decodedResponse) {
            logData("FCMB Decoded Response", $decodedResponse);
            showError("Invalid response from server. Raw Response". $decodedResponse);
        }

        // Handle API errors
        if (isset($decodedResponse['ResponseCode']) && $decodedResponse['ResponseCode'] !== '00') {
            logError("Unknown error from API");
            showError($decodedResponse['ResponseMessage'] ?? "Unknown error from API.");
        }

        showSuccess(
            "Account Opening", 
            $decodedResponse['AccountNumber']. " FCMB Account Opened Successfully"
        );
        logData("FCMB Successfull Opening", $decodedResponse['accountNumber']);
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
        }).then(() => window.location.href='../OpenAccountBVN');
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


?>