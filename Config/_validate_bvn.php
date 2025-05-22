<?php

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');
ob_end_flush();
include('all_functions.php');

// Initialize variables
$phoneNumber = $bvNumber = $requestId = $userId = $channel = $pcCode = $agentCode = $bankCode = $emailAddress = '';
$userId = "20329325801";
$channel = "TP-C24";

if (isset($_POST['validateBVN'])) {
    // Sanitize inputs
    $phoneNumber = filter_input(INPUT_POST, 'phoneNumber', FILTER_SANITIZE_STRING);
    $bvNumber = filter_input(INPUT_POST, 'bvNumber', FILTER_SANITIZE_STRING);
    $pcCode = filter_input(INPUT_POST, 'pcCode', FILTER_SANITIZE_STRING);
    $bankCode = filter_input(INPUT_POST, 'bank', FILTER_SANITIZE_STRING);
    $emailAddress = filter_input(INPUT_POST, 'emailAddress', FILTER_SANITIZE_STRING);
    $agentCode = filter_input(INPUT_POST, 'agentCode', FILTER_SANITIZE_STRING);
    $requestId = date('YmdHis') . rand();
    $referenceNumber = date('YmdHis') . rand();

    // Wema credentials
    $wemaUrl = $_ENV['WEMA_URL'] ?? '';
    $wemaApiKey = $_ENV['WEMA_API_KEY'] ?? '';
    $wemaOcpApim = $_ENV['WEMA_OCP_APIM_SUB_KEY'] ?? '';

    // Validate required inputs
    if (empty($phoneNumber) || empty($bvNumber)) {
        showError("Phone number and BVN are required");
        exit;
    }

    $_SESSION['bank'] = $bankCode;
    $_SESSION['pcCode'] = $pcCode;

    try {
        // Load public key for encryption
        $publicKeyPath = '../AccountOpeningValidation/public_key.pem';
        $publicKey = file_get_contents($publicKeyPath);
        if (!$publicKey) {
            logError("Could not read public key from {$publicKeyPath}");
            throw new Exception("Failed to load encryption key");
        }

        // Encrypt sensitive data
        $encryptedData = [
            'bvn' => encryptData($bvNumber, $publicKey),
            'phoneNumber' => encryptData($phoneNumber, $publicKey),
            'userId' => encryptData($userId, $publicKey),
            'requestId' => encryptData($requestId, $publicKey)
        ];

        logData("User Consent Request", $encryptedData);

        if ($bankCode === '000013') {
            // Keystone Bank: Consent after BVN check
            processBvnVerification($encryptedData, $channel, true);
            
            $responseUserConsent = userConsent($agentCode, $requestId, $phoneNumber, $channel, $pcCode, $userId, $referenceNumber);
            $responseUserDecode = json_decode($responseUserConsent, true);
            
            logData("User Consent Response", $responseUserDecode);
            $_SESSION['referenceNumber'] = $referenceNumber;

            if (!$responseUserConsent || !isset($responseUserDecode['ResponseCode']) || $responseUserDecode['ResponseCode'] !== "00") {
                $error = $responseUserDecode['ResponseDescription'] ?? "Consent verification failed";
                showError($error);
                exit;
            }

        } elseif ($bankCode === '000017') {

            $responseWemaConsent = wemaConsent($wemaUrl, $wemaOcpApim, $wemaApiKey, $phoneNumber, $emailAddress, $bvNumber);
            $responseWemaDecode = json_decode($responseWemaConsent, true);

            logData("Wema Consent Response", $responseWemaDecode);

            // Check first — BEFORE calling processBvnVerification
            if (!isset($responseWemaDecode['status']) || $responseWemaDecode['status'] !== true) {
                $error = $responseWemaDecode['message'] ?? "Wema Consent verification failed";
                showError($error);
                exit;
            }

            // Wema Bank: Consent after BVN check
            processBvnVerification($encryptedData, $channel, true);
            $_SESSION['TrackingID'] = $responseWemaDecode['data']['trackingId'] ?? '';

        } else {
            // Other banks: No consent, direct BVN verification
            processBvnVerification($encryptedData, $channel);
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
function storeSessionData($data, $withConsent = false, $consentRef='') {
    $_SESSION['phoneNumber'] = $data['phoneNumber'] ?? '';
    $_SESSION['stateOfOrigin'] = $data['stateOfOrigin'] ?? '';
    $_SESSION['lgaOfOrigin'] = $data['lgaOfOrigin'] ?? '';
    $_SESSION['firstName'] = $data['firstName'] ?? '';
    $_SESSION['middleName'] = $data['middleName'] ?? '';
    $_SESSION['lastName'] = $data['lastName'] ?? '';
    $_SESSION['dateOfBirth'] = $data['dateOfBirth'] ?? '';
    $_SESSION['bvn'] = $data['bvn'] ?? '';
    $_SESSION['email'] = $data['email'] ?? '';
    
    // if ($withConsent && $consentRef) {
    //     $_SESSION['ReferenceNumber'] = $consentRef;
    // }
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