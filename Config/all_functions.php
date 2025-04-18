<?php
 function getBvn($bvn, $PhoneNumber, $RequestID, $Channel, $userID) {
    // Validate inputs
    if (empty($bvn) || empty($PhoneNumber) || empty($RequestID) || empty($Channel) || empty($userID)) {
        throw new InvalidArgumentException("All parameters are required");
    }

    // Initialize cURL session
    $curl = curl_init();
    if ($curl === false) {
        throw new RuntimeException("Failed to initialize cURL");
    }

    // Prepare request data with validation
    $requestData = [
        //"channel" => substr($Channel, 0, 20), // Truncate to prevent overflow
        "channel"=>$Channel,
        "bvn" => $bvn,
        "phoneNumber" => $PhoneNumber,
        "requestId" => $RequestID,
        "userId" => $userID
    ];

    // Configure cURL options
    $curlOptions = [
        CURLOPT_URL => 'https://collection.gtbank.com/AppServices/GTBRequestService/Api/GetSingleBVN',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true, // Never disable SSL verification in production!
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_TIMEOUT => 30, // 30 seconds timeout
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($requestData),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_HEADER => true // Include headers in output for debugging
    ];

    // Set cURL options
    if (!curl_setopt_array($curl, $curlOptions)) {
        throw new RuntimeException("Failed to set cURL options");
    }

    // Execute request
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    // Error handling
    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        throw new RuntimeException("cURL error: " . $error);
    }

    curl_close($curl);

    // Log the complete response for debugging
    // file_put_contents("bvn_api_debug.log", 
    //     "[" . date('Y-m-d H:i:s') . "] BVN API Request:\n" .
    //     "URL: " . $curlOptions[CURLOPT_URL] . "\n" .
    //     "Request Data: " . json_encode($requestData) . "\n" .
    //     "HTTP Code: " . $httpCode . "\n" .
    //     "Headers:\n" . $headers . "\n" .
    //     "Body:\n" . $body . "\n\n",
    //     FILE_APPEND
    // );

    // Validate HTTP status code
    if ($httpCode !== 200) {
        throw new RuntimeException("API returned HTTP code: " . $httpCode);
    }

    // Parse JSON response
    $decodedResponse = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException("Invalid JSON response: " . json_last_error_msg());
    }

    // Validate API response structure
    if (!isset($decodedResponse['responseCode'])) {
        throw new RuntimeException("Invalid API response format");
    }

    return $body; // Return raw response for upstream processing
}
// User Consent for Account opening
 function userConsent($AgentCode, $Requestid, $Phoneno, $Channel, $PCCode, $UserId, $ReferenceNumber) {
     
       
            $rawUserConsent = array(
                        "AgentId"=> $AgentCode,
                        "ReferenceNumber"=> $ReferenceNumber,
                        "PhoneNumber"=> $Phoneno,
                        "Channel"=> $Channel,
                        "PcCode"=> $PCCode,
                        "UserId" => $UserId,
                        "AuthMode"=> "MPIN",
                        "AuthValue"=> "1234",
                        "RequestId"=> $Requestid
                    );
           
     
    // Initialize cURL session
    $curl = curl_init();          

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://collection.gtbank.com/SANEF/AccountOpening/Api/AccountOpeningConsent',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS=> json_encode($rawUserConsent),
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    // Execute the cURL request and store the response
        $responseUserConsent = curl_exec($curl);
        
    // Return the response
     return $responseUserConsent;
}





// // Get VNIN
//  function getVnin($vnin, $getToken) {

//     // Initialize cURL session
//     $curl = curl_init();


//     // Set cURL options
//     curl_setopt_array($curl, array(
//         CURLOPT_URL => 'https://collection.gtbank.com/SANEF/IdentityService/api/SearchBySingleParam?SearchType=6&NinNumber='.$vnin,
//           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false),
//           CURLOPT_RETURNTRANSFER => true,
//           CURLOPT_ENCODING => '',
//           CURLOPT_MAXREDIRS => 10,
//           CURLOPT_TIMEOUT => 0,
//           CURLOPT_FOLLOWLOCATION => true,
//           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//           CURLOPT_CUSTOMREQUEST => 'GET',
//           CURLOPT_HTTPHEADER => array(
//             'Channel: TP-C24',
//             'Authorization: Bearer '.$getToken
//           ),
//     ));

//     // Execute the cURL request and store the response
//         $response = curl_exec($curl);
        
//     // Return the response
//      return $response;
// }


// GET TOKEN
 function getToken($username, $password) {
     // Prepare the data to be sent in the request
    $rawToken = array(
        "username" => $username,
        "password" => $password
    );

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://collection.gtbank.com/SANEF/IdentityService/api/GetBearerToken',
        CURLOPT_RETURNTRANSFER => true,
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false),
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($rawToken),
        CURLOPT_HTTPHEADER => array(
            'Channel: TP-C24',
            'Content-Type: application/json'
        ),
    ));

    // Execute the cURL request and store the response
        $responsevnin = curl_exec($curl);
    // Return the response
     return $responsevnin;
}

// Post into the CallBack API To POST into the DB

function callBackAPI($dataResponseCallBack) {

    $callBack = array(
        "ParseBody" => $dataResponseCallBack
    );

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://lukeportservice.cpay.ng/api/webhook/lukeportservice/callback',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS => json_encode($callBack),
CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
),
));
// Execute the cURL request and store the response
$responseCall = curl_exec($curl);
// Return the response
return $responseCall;
}



// // Optimus Bank

// function getOptimusToken() {
// //     // Prepare the data to be sent in the request
//     $rawToken = array(
//         "username" => '4c211cea-24a1-46da-8539-e44c59d8b798',
//         "password" => 'YWMxYjhhY2UtN2NkNS00M2Y1LWI4YmUtOTU4N2MzYjg0Yzc0eXRyZWZnaGpoZ3ZjeGZnd'
//     );

//     // Initialize cURL session
//     $curl = curl_init();

//     // Set cURL options
//     curl_setopt_array($curl, array(
//         CURLOPT_URL => 'https://optiweb.optimusbank.com:8025/api-gateway/tokens/generate',
//         CURLOPT_RETURNTRANSFER => true,
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false),
//         CURLOPT_ENCODING => '',
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 0,
//         CURLOPT_FOLLOWLOCATION => true,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => 'POST',
//         CURLOPT_POSTFIELDS => json_encode($rawToken),
//         CURLOPT_HTTPHEADER => array(
//             'Content-Type: application/json'
//         ),
//     ));

//     // Execute the cURL request and store the response
//         $responseOptimusToken = curl_exec($curl);
//     // Return the response
//      return $responseOptimusToken;
// }


// // Optimus Encryption Validation
//     function encryptAllData($jsonData){
        
//         $curl = curl_init();
        
//         curl_setopt_array($curl, [
//             CURLOPT_URL => 'https://accountopeningoptimus.onrender.com/encrypt',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'POST',
//             CURLOPT_POSTFIELDS => $jsonData,
//             CURLOPT_HTTPHEADER => [
//                 'Content-Type: application/json'
//             ],
//         ]);
        
//         // Execute cURL and handle response
//         $responseEncrypt = curl_exec($curl);
        
//         if (curl_errno($curl)) {
//             die("cURL error: " . curl_error($curl));
//         }
        
//         curl_close($curl);
        
//         return $responseEncrypt;
//     }
    
    
// // Optimus Decryption Validation
//     function decryptAllData($encryptedDataResponse){
        
//         $curl = curl_init();
        
//         curl_setopt_array($curl, [
//             CURLOPT_URL => 'https://accountopeningoptimus.onrender.com/decrypt',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'POST',
//             CURLOPT_POSTFIELDS => $encryptedDataResponse,
//             CURLOPT_HTTPHEADER => [
//                 'Content-Type: application/json'
//             ],
//         ]);
        
//         // Execute cURL and handle response
//         $decryptDataResponse = curl_exec($curl);
        
//         if (curl_errno($curl)) {
//             die("cURL error: " . curl_error($curl));
//         }
        
//         curl_close($curl);
        
//         return $decryptDataResponse;
//     }
    
    
// // Push Encrypted Data to the Payload for the Validate OTP

// function validateOTPEncrypt($optimusToken, $encryptedOTPBase64){
        
//         $curl = curl_init();
        
//         curl_setopt_array($curl, [
//             CURLOPT_URL => 'https://optiweb.optimusbank.com:8025/api-gateway/opti-finserve-api/v1/card/card-link-otp',
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_ENCODING => '',
//             CURLOPT_MAXREDIRS => 10,
//             CURLOPT_TIMEOUT => 0,
//             CURLOPT_FOLLOWLOCATION => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => 'POST',
//             CURLOPT_POSTFIELDS => $encryptedOTPBase64,
//             CURLOPT_HTTPHEADER => [
//                 'Content-Type: application/json',
//                 'Accept: text/plain',
//                 'client-id: 5848d571-cd81-4761-afc9-073a9a2ed782',
//                 'Authorization: Bearer '.$optimusToken
//             ],
//         ]);
        
//         // Execute cURL and handle response
//         $validateOTP = curl_exec($curl);
        
//         if (curl_errno($curl)) {
//             die("cURL error: " . curl_error($curl));
//         }
        
//         curl_close($curl);
        
//         return $validateOTP;
//     }
    
// // Push Encrypted Data to the Payload for the Validate BVN

//     function bvnValidateEncrypt($optimusToken, $encryptedBVNBase64){
            
//             $curl = curl_init();
            
//             curl_setopt_array($curl, [
//                 CURLOPT_URL => 'https://optiweb.optimusbank.com:8025/api-gateway/opti-finserve-api/v1/customer/validate-bvn',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => $encryptedBVNBase64,
//                 CURLOPT_HTTPHEADER => [
//                     'Content-Type: application/json',
//                     'Accept: text/plain',
//                     'client-id: 5848d571-cd81-4761-afc9-073a9a2ed782',
//                     'Authorization: Bearer '.$optimusToken
//                 ],
//             ]);
            
//             // Execute cURL and handle response
//             $validateBVN = curl_exec($curl);
            
//             if (curl_errno($curl)) {
//                 die("cURL error: " . curl_error($curl));
//             }
            
//             curl_close($curl);
            
//             return $validateBVN;
//         }
        
//     // Push Encrypted Data to the Payload for the Create Account With BVN

//     function createAccountEncrypt($optimusToken, $encryptedCreateAccount){
            
//             $curl = curl_init();
            
//             curl_setopt_array($curl, [
//                 CURLOPT_URL => 'https://optiweb.optimusbank.com:8025/api-gateway/opti-finserve-api/v1/customer/create-by-bvn',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => $encryptedCreateAccount,
//                 CURLOPT_HTTPHEADER => [
//                     'Content-Type: application/json',
//                     'Accept: text/plain',
//                     'client-id: 5848d571-cd81-4761-afc9-073a9a2ed782',
//                     'Authorization: Bearer '.$optimusToken
//                 ],
//             ]);
            
//             // Execute cURL and handle response
//             $accountCreationCustomer = curl_exec($curl);
            
//             if (curl_errno($curl)) {
//                 die("cURL error: " . curl_error($curl));
//             }
            
//             curl_close($curl);
            
//             return $accountCreationCustomer;
//         }


// UUID creation
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

return $requestId = generateUUID();

?>