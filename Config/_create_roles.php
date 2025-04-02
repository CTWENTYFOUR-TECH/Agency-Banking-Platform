<?php
include('_permission.php');

$userSessionData = getUserSessionData();

$loginID = $userSessionData['emailAddress'];

// Validate session key
$apiKey = $userSessionData['secretKey']; 

$base_url = 'https://lukeportservice.cpay.ng/lukeportservice';

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($apiKey) || empty($apiKey)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. API key missing.']);
    exit;
}


    // Validate input fields
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // $loginID = htmlspecialchars(trim($_POST['loginID']));
    $groupName = htmlspecialchars(trim($_POST['roleName']));
    $createAdmin = htmlspecialchars(trim($_POST['createAdmin']));
    $createSubAgent = htmlspecialchars(trim($_POST['createSubAgent']));
    $createAggregator = htmlspecialchars(trim($_POST['createAggregator']));
    $updateUserRole = htmlspecialchars(trim($_POST['updateUserRole']));
    $viewUser = htmlspecialchars(trim($_POST['viewUser']));
    $deleteUser = htmlspecialchars(trim($_POST['deleteUser']));
    $deactivateUser = htmlspecialchars(trim($_POST['deactivateUser']));
    $activateUser = htmlspecialchars(trim($_POST['activateUser']));
    $upgradeAggregator = htmlspecialchars(trim($_POST['upgradeAggregator']));
    $unlockUser = htmlspecialchars(trim($_POST['unlockUser']));
    $editUser = htmlspecialchars(trim($_POST['editUser']));
    $createRoles = htmlspecialchars(trim($_POST['createRoles']));
    $viewRoles = htmlspecialchars(trim($_POST['viewRoles']));
    $cardIssuance = htmlspecialchars(trim($_POST['cardIssuance']));
    $accountOpeningReport  = htmlspecialchars(trim($_POST['accountOpeningReport']));
    $cardIssuanceReport  = htmlspecialchars(trim($_POST['cardIssuanceReport']));
    $agentOnboardedReport  = htmlspecialchars(trim($_POST['agentOnboardedReport']));
    $aggregatorReport  = htmlspecialchars(trim($_POST['aggregatorReport']));
   
        // Sanitize inputs
    $groupName = htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8');
    $loginID = htmlspecialchars($loginID, ENT_QUOTES, 'UTF-8');
    $groupID = str_replace(' ', '_', $groupName);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $base_url.'/api/create_role',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,  // Set timeout to prevent long waits
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
                "GroupID" => $groupID,
                "GroupName"=> $groupName,
                "ViewUser"=> $viewUser,
                "DeleteUser"=> $deleteUser,
                "EditUser"=> $editUser,
                "UnlockUser"=> $unlockUser,
                "UpdateUserRole"=>$updateUserRole,
                "CreateRole"=> $createRoles,
                "ViewGroup"=> $viewRoles,
                "CreateUser"=> $createAdmin,
                "CreateSubAgent"=> $createSubAgent,
                "CreateAggregator"=> $createAggregator,
                "DeactivateUser"=> $deactivateUser,
                "ReactivateUser" => $activateUser,
                "AccountOpening" => $accountOpeningReport,
                "CardIssuance"=> $cardIssuance,
                "AccountOpeningReport"=> $accountOpeningReport,
                "CardIssuanceReport"=> $cardIssuanceReport,
                "AgentOnboardedReport"=> $agentOnboardedReport,
                "AggregatorReport" => $aggregatorReport,
                "LoginID"=> $loginID
        ]),

        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
    ]);

    $resCreateGroup = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code

    if (curl_errno($curl)) {
        echo json_encode(['status' => 'error', 'message' => 'cURL error: ' . curl_error($curl)]);
        curl_close($curl);
        exit;
    }

    curl_close($curl);

    $decodedResponse = json_decode($resCreateGroup, true);

    // Log API response (optional, for debugging)
    file_put_contents("debug_log.txt", date("Y-m-d H:i:s") . " - API Response Roles: " . $resCreateGroup . PHP_EOL, FILE_APPEND);

    // Check if JSON decoding was successful
    if (!$decodedResponse) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid response from server. Raw Response: ' . $resCreateGroup
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
        'message' => 'Group role created successfully!',
        'redirectUrl' => $redirectUrl
    ]);
    exit;
}
?>
