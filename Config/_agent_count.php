<?php
// Ensure no output before headers
ob_start();

include('_permission.php');

// Set headers first
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Disable PHP error output
ini_set('display_errors', 0);
error_reporting(0);

try {
    $permissions = getUserPermissions();
    $userSessionData = getUserSessionData();
    
    if (empty($userSessionData)) {
        throw new Exception('Session data not found');
    }

    $apiKey = $userSessionData['secretKey'] ?? null;
  
    $loginID = $userSessionData['emailAddress'] ?? null;
    $groupID = $permissions['GroupID'] ?? null;
    $agentCode = $userSessionData['agentCode'] ?? null;

    if (empty($apiKey)) {
        throw new Exception('Unauthorized access. API key missing.');
    }

    function getAgentCount($loginID, $apiKey, $groupID, $agentCode) {
        $base_url = 'https://lukeportservice.cpay.ng/lukeportservice';
        
        $data = [
            'login_id' => $loginID,
            'group_id' => $groupID,
            'agent_code' => $agentCode
        ];

        $options = [
            'http' => [
                'header'  => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey
                ],
                'method'  => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true
            ]
        ];
        
        $context = stream_context_create($options);
        $response = file_get_contents($base_url.'/api/getAllAgents', false, $context);
        
        if ($response === FALSE) {
            throw new Exception('API request failed');
        }
        
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid API response');
        }
        
        return $decoded;
    }

    $response = getAgentCount($loginID, $apiKey, $groupID, $agentCode);

    if (!isset($response['ResponseCode'])) {
        throw new Exception('Invalid API response structure');
    }

    // CORRECTED: Directly access the data properties instead of looping
    $responseData = $response['data'] ?? [];
    $total_agent = $responseData['total_agent'] ?? 0;
    $agent_daily = $responseData['total_daily_agent'] ?? 0;
    $total_aggregator = $responseData['total_aggregator'] ?? 0;
    $total_daily_aggregator = $responseData['total_daily_aggregator'] ?? 0;

    if ($response['ResponseCode'] === '00') {
        echo json_encode([
            'status' => 'success',
            'total_agent' => $total_agent,
            'total_daily_agent' => $agent_daily,
            'total_aggregator' => $total_aggregator,
            'total_daily_aggregator' => $total_daily_aggregator,
            'total_card_issued_agent' => $total_card_issued_agent,
            'daily_card_issued_agent' => $daily_card_issued_agent,
            'total_account_opened_aggregator' => $total_account_opened_aggregator,
            'daily_account_opened_aggregator' => $daily_account_opened_aggregator,
            'message' => $response['ResponseMessage'] ?? 'Success',
            // Debug info (remove in production)
            // '_debug' => [
            //     'api_response' => $response,
            //     'received_data' => $responseData
            // ]
        ]);
    } else {
        throw new Exception($response['ResponseMessage'] ?? 'Unknown error occurred');
    }

} catch (Exception $e) {
    // Ensure no output has been sent
    if (ob_get_length()) ob_clean();
    
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        // Debug info (remove in production)
        // '_debug' => [
        //     'session_data' => $userSessionData ?? null,
        //     'permissions' => $permissions ?? null
        // ]
    ]);
}

// Flush output buffer
ob_end_flush();
?>