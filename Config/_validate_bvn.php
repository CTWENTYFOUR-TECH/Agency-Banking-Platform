<?php
ob_start(); // Start output buffering
session_start();
include('all_functions.php');
ob_end_flush();
// Initialize variables
$Phoneno = $BVNo = $Requestid = $UserId = $Channel = $PCCode = $AgentCode = $BankCode = '';
$phoneNumber = $stateOfOrigin = $lgaOfOrigin = $firstName = $middleName = $lastName = $dateOfBirth = $bvn = $email = '';

if(isset($_POST['validateBVN'])) {
    // Sanitize and validate inputs
    $Phoneno = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
    $BVNo = filter_input(INPUT_POST, 'bvNumber', FILTER_SANITIZE_STRING);
    $PCCode = filter_input(INPUT_POST, 'pcCode', FILTER_SANITIZE_STRING);
    $BankCode = filter_input(INPUT_POST, 'bank', FILTER_SANITIZE_STRING);
    $UserId = "20329325801";
    $Channel = "TP-C24";
    // Generate unique request ID
    $Requestid = date('Ymdhis').rand();
    $AgentCode = filter_input(INPUT_POST, 'agentCode', FILTER_SANITIZE_STRING);
    $AgentCode = $userSessionData['agentCode'] ?? '';
    $ReferenceNumber = date('Ymdhis').rand();

    // Validate required fields
    if(empty($Phoneno) || empty($BVNo)) {
        showError("Phone number and BVN are required");
        exit;
    }

    $_SESSION['bank'] = $BankCode;

    try {
        // Load public key
        $publicKey = file_get_contents('../AccountOpeningValidation/public_key.pem');
        if(!$publicKey) {
            throw new Exception("Failed to load encryption key");
        }

        // Encrypt sensitive data
        $encryptedData = [
            'bvn' => encryptData($BVNo, $publicKey),
            'phoneNumber' => encryptData($Phoneno, $publicKey),
            'userId' => encryptData($UserId, $publicKey),
            'requestId' => encryptData($Requestid, $publicKey)
        ];

        logData("User Consent Request", $encryptedData);

        // Process based on bank code
        if($BankCode == '000013') {
            // Get user consent first
            $responseUserConsent = userConsent($AgentCode, $Requestid, $Phoneno, $Channel, $PCCode, $UserId, $ReferenceNumber);
            logData("User Consent Request", $responseUserConsent);
            
            $responseUserDecode = json_decode($responseUserConsent, true);
            
            if(!$responseUserConsent || !isset($responseUserDecode['ResponseCode'])) {
                throw new Exception("Invalid response from consent service");
            }

            if($responseUserDecode['ResponseCode'] != "00") {
                showError($responseUserDecode['ResponseDescription'] ?? "Consent verification failed");
                exit;
            }

            // Proceed with BVN verification after successful consent
            processBvnVerification($encryptedData, $Channel, true, $responseUserDecode['ReferenceNumber']);
        } else {
            // Direct BVN verification without consent
            processBvnVerification($encryptedData, $Channel);
        }
    } catch (Exception $e) {
        logError($e->getMessage());
        showError("System error occurred. Please try again later.");
    }
}

/**
 * Encrypts data using public key
 */
function encryptData($data, $publicKey) {
    if(!openssl_public_encrypt($data, $encrypted, $publicKey)) {
        throw new Exception("Encryption failed");
    }
    return base64_encode($encrypted);
}

/**
 * Processes BVN verification
 */
function processBvnVerification($encryptedData, $channel, $withConsent = false, $consentRef = '') {
     // Debug: Log the channel value
     error_log("Channel value before getBvn call: " . $channel);
    $responseBVN = getBvn(
        $encryptedData['bvn'],
        $encryptedData['phoneNumber'],
        $encryptedData['requestId'],
        $channel,                  // Correct position for channel
        $encryptedData['userId']
    );

    $decodeBVN = json_decode($responseBVN, true);
    logData("BVN Verification Response", $responseBVN);

    if(!isset($decodeBVN['responseCode'])) {
        throw new Exception("Invalid BVN verification response");
        logData("BVN Verification Response", $responseBVN);
    }

    if($decodeBVN['responseCode'] == '00') {
        // Store successful verification data
        storeSessionData($decodeBVN['responseObject'], $withConsent, $consentRef);
        
        $message = $withConsent 
            ? "BVN Validation and User Consent\nA code has been sent to the customer phone as a consent"
            : "BVN Validation successful";
            
            showSuccess(
                "BVN Verified", 
                $withConsent 
                    ? "Verification successful! A code has been sent to the customer's phone."
                    : "BVN verification completed successfully."
            );
    } else {
        showError($decodeBVN['responseDescription'] ?? "BVN verification failed");
    }
}

/**
 * Stores data in session
 */
function storeSessionData($data, $withConsent = false, $consentRef = '') {
    $_SESSION['phoneNumber'] = $data['phoneNumber'] ?? '';
    $_SESSION['stateOfOrigin'] = $data['stateOfOrigin'] ?? '';
    $_SESSION['lgaOfOrigin'] = $data['lgaOfOrigin'] ?? '';
    $_SESSION['firstName'] = $data['firstName'] ?? '';
    $_SESSION['middleName'] = $data['middleName'] ?? '';
    $_SESSION['lastName'] = $data['lastName'] ?? '';
    $_SESSION['dateOfBirth'] = $data['dateOfBirth'] ?? '';
    $_SESSION['bvn'] = $data['bvn'] ?? '';
    $_SESSION['email'] = $data['email'] ?? '';
    
    if($withConsent && $consentRef) {
        $_SESSION['ReferenceNumber'] = $consentRef;
    }
}

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













// include('_permission.php');
// include('all_functions.php');

// $userSessionData = getUserSessionData();

// $Phoneno = $BVNo = $Requestid = $UserId = $Channel = $PCCode = $AgentCode = $BankCode = $phoneNumber = $stateOfOrigin = $lgaOfOrigin = $firstName = $middleName = $lastName = $dateOfBirth = $bvn =$email

// if(isset($_POST['verify'])){
//         $Phoneno = htmlspecialchars(trim($_POST['phoneno']));
//         $BVNo = htmlspecialchars(trim($_POST['bvn']));
//         $Requestid = date('Ymdhis').rand();
//         $UserId = "043429325801";
//         $Channel = "KL-342";
//         $PCCode = htmlspecialchars(trim($_POST['pcCode']));
//         $AgentCode = $userSessionData['agentCode'];
//         $BankCode = htmlspecialchars(trim($_POST['bankCode']));

//         // RequestID will also be used as teh ReferenceNumber
//         $ReferenceNumber = $Requestid;
                
//             if($Phoneno != '' && $BVNo != '') {
//                 //User Consent
//                 if($BankCode == '000013'){    
                    
//                     $responseUserConsent = userConsent($AgentCode, $Requestid, $Phoneno, $Channel, $PCCode, $UserId, $ReferenceNumber);
                    
//                         file_put_contents("debug_log.txt", "responseUserConsent ".$responseUserConsent, FILE_APPEND); 
                    
//                     $responseUserDecode = json_decode($responseUserConsent, true);
                    
//                     file_put_contents("debug_log.txt", "responseUserConsent ".$responseUserDecode, FILE_APPEND); 
                    
//                     $responUserConsentDesc = $responseUserDecode['ResponseDescription'];
//                     $responUserRef = $responseUserDecode['ReferenceNumber'];
                    
//                     if(!$responseUserConsent){
//                         echo "<script>
//                                     Swal.fire({
//                                     icon: 'error',
//                                     title: 'Oops...',
//                                     text: 'Error encountered, contact administrator'
//                                     }).then(function(){
//                                         window.location.href='../AccountOpeningValidation'
//                                     })
//                                     </script>";
//                     }
         
//                 if($responseUserDecode['ResponseCode'] == "00"){
                                        
//                         file_put_contents("debug_log.txt", "$responUserConsentDesc ".$responUserConsentDesc, FILE_APPEND);                                 
        
//                     // Get the encrypted text
//                     // Load your existing public key from a file
//                     $publicKey = file_get_contents('../path/path/public_key.pem');
            
//                         // Encrypt plaintext with the public key
//                     $encrytBvn = openssl_public_encrypt($BVNo, $cipherbvn, $publicKey);
//                     $encrytPhone = openssl_public_encrypt($Phoneno, $cipherphone, $publicKey);
//                     $encrytUserid = openssl_public_encrypt($UserId, $cipheruserid, $publicKey);
//                     $encrytRequestid = openssl_public_encrypt($Requestid, $cipherrequestid, $publicKey);
                    
                    
//                     // Output base64 encoded ciphertext
//                     $baseBVN       =   base64_encode($cipherbvn);
//                     $basePhoneNo   =   base64_encode($cipherphone);
//                     $baseRequestID =   base64_encode($cipherrequestid);
//                     $baseUserID   =   base64_encode($cipheruserid);
                    
//                     $vBvn         = $baseBVN;
//                     $vPhoneNumber = $basePhoneNo;
//                     $vRequestID   = $baseRequestID;
//                     $vUserID      = $baseUserID;
//                     $vChannel     = $Channel;
                        
//                         //     // Prepare the data to be sent in the request
//                     $responseBVN = getBvn($vBvn, $vPhoneNumber, $vRequestID, $vUserID, $vChannel);
                            
//                                 // decode the response 
//                     $decodeBVN = json_decode($responseBVN, true);
//                     $responseDesc = $decodeBVN['responseDescription'];
                                
//                     if($decodeBVN['responseCode'] == '00'){
//                         foreach($decodeBVN['responseObject'] as $myBvn)
                                    
//                         $phoneNumber   =  $decodeBVN['responseObject']['phoneNumber'];
//                         $stateOfOrigin =  $decodeBVN['responseObject']['stateOfOrigin'];
//                         $lgaOfOrigin   =  $decodeBVN['responseObject']['lgaOfOrigin'];
//                         $firstName     =  $decodeBVN['responseObject']['firstName'];
//                         $middleName    =  $decodeBVN['responseObject']['middleName'];
//                         $lastName      =  $decodeBVN['responseObject']['lastName'];
//                         $dateOfBirth   =  $decodeBVN['responseObject']['dateOfBirth'];
//                         $bvn           =  $decodeBVN['responseObject']['bvn']; 
//                         $email         =  $decodeBVN['responseObject']['email']; 

//                         //Something to write to txt log
//                         $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
//                                 "User: ".$login_session.PHP_EOL."ResponseDescription: ".$responseBVN.PHP_EOL.
//                                             "-------------------------".PHP_EOL;
//                         //Save string to log, use FILE_APPEND to append.
//                         file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                                    
//                                     $_SESSION['phoneNumber']        =   $phoneNumber;
//                                     $_SESSION['stateOfOrigin']      =   $stateOfOrigin;
//                                     $_SESSION['lgaOfOrigin']        =   $lgaOfOrigin;
//                                     $_SESSION['firstName']          =   $firstName;
//                                     $_SESSION['middleName']         =   $middleName;
//                                     $_SESSION['lastName']           =   $lastName;
//                                     $_SESSION['dateOfBirth']        =   $dateOfBirth;
//                                     $_SESSION['bvn']                =   $bvn;
//                                     $_SESSION['email']              =   $email;
//                                     $_SESSION['ReferenceNumber']    =   $responUserRef; //This is from /UserConsent API
                                        
//                                             echo "<script>
//                                             Swal.fire({
//                                                 icon: 'success',
//                                                 title: 'BVN Validation and User Consent',
//                                                 text: '$responseDesc A code has been sent to the customer phone as a consent, provide the code on account opening'
//                                                 }).then(function(){
//                                                     window.location.href='../OpenAccountBVN'
//                                                 })
//                                         </script>";
//                                 }else{
//                                     echo "<script>
//                                     Swal.fire({
//                                     icon: 'error',
//                                     title: 'Oops...',
//                                     text: '$responseDesc'
//                                     }).then(function(){
//                                         window.location.href='../AccountOpeningValidation'
//                                     })
//                             </script>";
                                        
//                                         //Something to write to txt log
//                                     $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
//                                             "User: ".$login_session.PHP_EOL.
//                                             "Request: ".$decodeBVN.PHP_EOL.
//                                             "ResponseDescription: ".$responseBVN.PHP_EOL.
//                                             "-------------------------".PHP_EOL;
//                                     //Save string to log, use FILE_APPEND to append.
//                                     file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
//                                 }  
                        
//                 }else{ //This is for the failed UserConsent
//                     echo "<script>
//                                     Swal.fire({
//                                     icon: 'error',
//                                     title: 'Oops...',
//                                     text: '$responseUserConsent'
//                                     }).then(function(){
//                                         window.location.href='../AccountOpeningValidation'
//                                     })
//                         </script>";
                        
//                         exit();
//                 }           
//             }  //end of PCCode
//         else{
//              // Encrypt plaintext with the public key
//         $encrytBvn = openssl_public_encrypt($BVNo, $cipherbvn, $publicKey);
//         $encrytPhone = openssl_public_encrypt($Phoneno, $cipherphone, $publicKey);
//         $encrytUserid = openssl_public_encrypt($UserId, $cipheruserid, $publicKey);
//         $encrytRequestid = openssl_public_encrypt($Requestid, $cipherrequestid, $publicKey);
        
        
//         // Output base64 encoded ciphertext
//         $baseBVN       =   base64_encode($cipherbvn);
//         $basePhoneNo   =   base64_encode($cipherphone);
//         $baseRequestID =   base64_encode($cipherrequestid);
//         $baseUserID   =    base64_encode($cipheruserid);
         
//        $bvn         = $baseBVN;
//        $PhoneNumber = $basePhoneNo;
//        $RequestID   = $baseRequestID;
//        $userID      = $baseUserID;
//        $channel     = $Channel;
            
//             //     // Prepare the data to be sent in the request
//             $responseBVN = getBvn($bvn, $PhoneNumber, $RequestID, $channel, $userID);
            
                        
//             // decode the response 
//             $decodeBVN = json_decode($responseBVN, true);
//             $responseDesc = $decodeBVN['responseDescription'];
                    
//             if($decodeBVN['responseCode'] == '00'){

//                 foreach($decodeBVN['responseObject'] as $myBvn)
                        
//             $phoneNumber   =  $decodeBVN['responseObject']['phoneNumber'];
//             $stateOfOrigin =  $decodeBVN['responseObject']['stateOfOrigin'];
//             $lgaOfOrigin   =  $decodeBVN['responseObject']['lgaOfOrigin'];
//             $firstName     =  $decodeBVN['responseObject']['firstName'];
//             $middleName    =  $decodeBVN['responseObject']['middleName'];
//             $lastName      =  $decodeBVN['responseObject']['lastName'];
//             $dateOfBirth   =  $decodeBVN['responseObject']['dateOfBirth'];
//             $bvn           =  $decodeBVN['responseObject']['bvn']; 
//             $email         =  $decodeBVN['responseObject']['email']; 
                        
//                 //Something to write to txt log
//                 $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
//                                 "User: ".$login_session.PHP_EOL.
//                                 "ResponseDescription: ".$responseBVN.PHP_EOL.
//                                 "-------------------------".PHP_EOL;
//                         //Save string to log, use FILE_APPEND to append.
//                         file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                        
//                         $_SESSION['phoneNumber']        =   $phoneNumber;
//                         $_SESSION['stateOfOrigin']      =   $stateOfOrigin;
//                         $_SESSION['lgaOfOrigin']        =   $lgaOfOrigin;
//                         $_SESSION['firstName']          =   $firstName;
//                         $_SESSION['middleName']         =   $middleName;
//                         $_SESSION['lastName']           =   $lastName;
//                         $_SESSION['dateOfBirth']        =   $dateOfBirth;
//                         $_SESSION['bvn']                =   $bvn;
//                         $_SESSION['email']              =   $email;
                            
//                                 echo "<script>
//                                     Swal.fire({
//                                     icon: 'success',
//                                     title: 'BVN Validation',
//                                     text: '$responseDesc'
//                                     }).then(function(){
//                                         window.location.href='../OpenAccountBVN'
//                                     })
//                               </script>";
//                     }else{
//                          echo "<script>
//                         Swal.fire({
//                           icon: 'error',
//                           title: 'Oops...',
//                           text: '$responseDesc'
//                         }).then(function(){
//                             window.location.href='../AccountOpeningValidation'
//                         })
//                    </script>";
                               
//                              //Something to write to txt log
//                         $log  = "IP: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
//                                 "User: ".$login_session.PHP_EOL.
//                                 "Request: ".$decodeBVN.PHP_EOL.
//                                 "ResponseDescription: ".$responseBVN.PHP_EOL.
//                                 "-------------------------".PHP_EOL;
//                         //Save string to log, use FILE_APPEND to append.
//                         file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
//                     }   
//                 }
        
//         }else{
//             echo "All fields are required";
//         }
// }


?>