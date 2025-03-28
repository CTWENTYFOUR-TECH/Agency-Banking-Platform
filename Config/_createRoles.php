<?php
include('_permission.php');

$userSessionData = getUserSessionData();

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Validate session key

$apiKey = $userSessionData['secretKey']; 

if (!isset($apiKey) || empty($apiKey)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. API key missing.']);
    exit;
}


    // Validate input fields
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $loginID = htmlspecialchars(trim($_POST['loginID']));
    $groupName = htmlspecialchars(trim($_POST['groupRole']));
    $ReportCreate = htmlspecialchars(trim($_POST['createReport']));
    $ReportUpdate = htmlspecialchars(trim($_POST['updateReport']));
    $ReportDelete = htmlspecialchars(trim($_POST['deleteReport']));
    $UserRoleUpdate = htmlspecialchars(trim($_POST['updateUserRole']));
    $UserCreate = htmlspecialchars(trim($_POST['createUser']));
    $ViewUser = htmlspecialchars(trim($_POST['viewUser']));
    $UserDelete = htmlspecialchars(trim($_POST['deleteUser']));
    $UserEdit = htmlspecialchars(trim($_POST['editUser']));
    $RoleCreate = htmlspecialchars(trim($_POST['createRole']));
    $ReportAudit = htmlspecialchars(trim($_POST['auditReport']));
    $GroupView = htmlspecialchars(trim($_POST['viewGroup']));
    $UserUnlock = htmlspecialchars(trim($_POST['unlockUser']));
    $ReportSend = htmlspecialchars(trim($_POST['sendReport']));
    $BlacklistBvn = htmlspecialchars(trim($_POST['bvnBlacklisting']));
    $UploadBVN  = htmlspecialchars(trim($_POST['uploadBVN']));
    $AppRating  = htmlspecialchars(trim($_POST['surveyRating']));
   
        // Sanitize inputs
    $groupName = htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8');
    $loginID = htmlspecialchars($loginID, ENT_QUOTES, 'UTF-8');
    $groupID = str_replace(' ', '_', $groupName);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://compliancetest.cpay.ng/compliance/api/create_group',
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
                "CreateReport"=> $ReportCreate,
                "DeleteReport"=> $ReportDelete,
                "UpdateReport"=> $ReportUpdate,
                "UpdateUserRole"=> $UserRoleUpdate,
                "CreateUser"=> $UserCreate,
                "ViewUser"=> $ViewUser,
                "DeleteUser"=> $UserDelete,
                "EditUser"=> $UserEdit,
                "CreateRole"=> $RoleCreate,
                "AuditReport"=> $ReportAudit,
                "ViewGroup" => $GroupView,
                "UnlockUser" => $UserUnlock,
                "SendReport"=> $ReportSend,
                "BVNBlacklisting"=> $BlacklistBvn,
                "UploadBVN"=> $UploadBVN,
                "SurveyRating"=> $AppRating,
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
        'message' => 'Group created successfully!',
        'redirectUrl' => $redirectUrl
    ]);
    exit;
}
?>
