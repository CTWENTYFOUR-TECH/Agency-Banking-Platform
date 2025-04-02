<?php
include('_permission.php');
header("Access-Control-Allow-Origin: *"); // For development only
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$userSessionData = getUserSessionData();
header("Content-Type: application/json");

// Debug: Log session data
// file_put_contents("debug_log.txt", print_r($_SESSION, true), FILE_APPEND);
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
//     exit;
// }

$base_url = 'https://lukeportservice.cpay.ng/lukeportservice';

$apiKey = $userSessionData['secretKey']; 
$loginID = $userSessionData['emailAddress'];

// Validate session key
if (!isset($apiKey) || empty($apiKey)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. API key missing.']);
    exit;
}


function getAllGroups($login, $apiKey, $base_url) {
    $data = ["LoginID" => $login]; // FIXED: Use function parameter

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url.'/api/all_group',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ),
    ));

    $respAllGroup = curl_exec($curl);

    if (curl_errno($curl)) {
        curl_close($curl);
        return json_encode(["status" => "error", "message" => curl_error($curl)]);
    }

    curl_close($curl);
    return $respAllGroup;
}

// Call function and process response
$response = getAllGroups($loginID, $apiKey, $base_url);
$decodedResponse = json_decode($response, true);

// Debug: Log API response
file_put_contents("debug_log.txt", date("Y-m-d H:i:s") . " - API GROUP: " . print_r($response, true) . PHP_EOL, FILE_APPEND);

if ($decodedResponse['ResponseCode'] == "00") {
    $options = "";
    foreach ($decodedResponse['Groups'] as $group) {
        $options .= "<option value='{$group['GroupID']}'>{$group['GroupName']}</option>";
    }
    echo json_encode(["status" => "success", "options" => $options]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to load groups."]);
}
?>
