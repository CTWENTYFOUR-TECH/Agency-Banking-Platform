<?php
// permissions.php

// Start session (ensure this is only called once per request)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Africa/Lagos');

// Define permission keys as constants

define('PERMISSION_CREATE_ADMIN_USER', 'createadmin_user');
define('PERMISSION_UPDATE_ROLE', 'updaterole_user');
define('PERMISSION_DELETE_USER', 'delete_user');
define('PERMISSION_VIEW_USER', 'view_user');
define('PERMISSION_UNLOCK_USER', 'unlock_user');
define('PERMISSION_EDIT_USER', 'user_edit');
define('PERMISSION_CREATE_ROLE', 'createrole_user');
define('PERMISSION_VIEW_GROUP', 'viewgroup_user');
define('PERMISSION_ACCOUNT_OPENING', 'accountopening_user');
define('PERMISSION_CARD_ISSUANCE', 'cardissuance_user');
define('PERMISSION_CARD_ISSUANCE_REPORT', 'cardissuancereport_user');
define('PERMISSION_ACCOUNTOPENING_REPORT', 'accountopeningreport_user');
define('PERMISSION_AGENTONBOARDED_REPORT', 'agentreport_user'); //This is for all the reports
define('PERMISSION_AGGREGATOR_REPORT', 'aggregatorreport_user');
define('PERMISSION_CREATE_AGGREGATOR', 'createaggregator_user');
define('PERMISSION_CREATE_SUB_AGENT', 'createsubagent_user');
define('PERMISSION_UPGRADE_AGGREGATOR', 'upgradeaggregator_user');
define('PERMISSION_DEACTIVATE_USER', 'deactivate_user');
define('PERMISSION_REACTIVATE_USER', 'reactivate_user');


// Session timeout duration (30 minutes)
const SESSION_TIMEOUT = 1800;

/**
 * Check if the session is valid and update last activity time.
 */
function validateSession() {
    if (!isset($_SESSION['LoginData'])) {
        header('Location: ../Logout');
        exit;
    }

    // Session timeout handling
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        header('Location: ../Logout');
        exit;
    }

    // Update last activity time
    $_SESSION['LAST_ACTIVITY'] = time();
}

/**
 * Get user permissions from session roles.
 */
function getUserPermissions() {
    $user = $_SESSION['LoginData'] ?? [];
    $roles = $user['Group']['Roles'] ?? [];

    $permissions = [];
    foreach ($roles as $role) {
        $group_name = $role['GroupName'] ?? 'N/A';
        $group_id = $role['GroupID'] ?? 0;
       
        $permissions = array_merge($permissions, [
            'GroupName' => $group_name,
            'GroupID' => $group_id,
            PERMISSION_CREATE_ADMIN_USER => $role['CreateUser'] ?? 0,
            PERMISSION_UPDATE_ROLE => $role['UpdateUserRole'] ?? 0,
            PERMISSION_DELETE_USER => $role['DeleteUser'] ?? 0,
            PERMISSION_VIEW_USER => $role['ViewUser'] ?? 0,
            PERMISSION_EDIT_USER => $role['EditUser'] ?? 0,
            PERMISSION_CREATE_ROLE => $role['CreateRole'] ?? 0,
            PERMISSION_VIEW_GROUP => $role['ViewGroup'] ?? 0,
            PERMISSION_UNLOCK_USER => $role['UnlockUser'] ?? 0,
            PERMISSION_ACCOUNT_OPENING => $role['AccountOpening'] ?? 0,
            PERMISSION_CARD_ISSUANCE => $role['CardIssuance'] ?? 0,
            PERMISSION_CARD_ISSUANCE_REPORT => $role['CardIssuanceReport'] ?? 0,
            PERMISSION_ACCOUNTOPENING_REPORT => $role['AccountOpeningReport'] ?? 0,
            PERMISSION_AGENTONBOARDED_REPORT => $role['AgentOnboardedReport'] ?? 0,
            PERMISSION_AGGREGATOR_REPORT => $role['AggregatorReport'] ?? 0,
            PERMISSION_CREATE_AGGREGATOR => $role['CreateAggregator'] ?? 0,
            PERMISSION_UPGRADE_AGGREGATOR => $role['UpgradeToAggregator'] ?? 0,
            PERMISSION_DEACTIVATE_USER => $role['DeactivateUser'] ?? 0,
            PERMISSION_REACTIVATE_USER => $role['ReactivateUser'] ?? 0,
            PERMISSION_CREATE_SUB_AGENT => $role['CreateSubAgent'] ?? 0
        ]);
    }

    return $permissions;
}

/**
 * Check if the user has the required permission.
 */
function checkPermissions($requiredPermission) {
    validateSession();
    $permissions = getUserPermissions();

    return isset($permissions[$requiredPermission]) && $permissions[$requiredPermission] == 1;
}

/**
 * Generate CSS to hide elements based on permissions.
 */
function getHiddenElementsCSS() {
    $permissions = getUserPermissions();

    $hiddenElements = [];
    foreach ($permissions as $key => $value) {
        if ($value == 0) {
            $hiddenElements[] = (strpos($key, '_user') !== false) ? '.' . $key : '#' . $key;
        }
    }

    if (empty($hiddenElements)) {
        return '';
    }

    return "<style>" . implode(", ", $hiddenElements) . " { display: none !important; }</style>";
}

/**
 * Get additional user and session data.
 */
function getUserSessionData() {
    $user = $_SESSION['LoginData'] ?? [];
    return [
        'secretKey' => $_SESSION['SecretKey'] ?? '',
        'agentCode' => $user['AgentCode'] ?? '',
        'aggregatorCode' => $user['aggregatorCode'] ?? '',
        'firstName' => $user['FirstName'] ?? '',
        'lastName' => $user['LastName'] ?? '',
        'middleName' => $user['MiddleName'] ?? '',
        'fullName' => ($user['FirstName'] ?? '') . ' ' . ($user['LastName'] ?? ''),
        'emailAddress' => $user['EmailAddress'] ?? '',
        'phoneNumber' => $user['PhoneNumber'] ?? '',
        'gender' => $user['Gender'] ?? '',
        'accountStatus' => $user['AccountStatus'] ?? '',
        'firstTimeLogin' => $user['FirstTimeLogin'] ?? '',
    ];
}
?>