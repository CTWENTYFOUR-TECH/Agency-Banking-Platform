<?php
// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the permissions.php file
require_once("../Config/_permission.php");

// Validate the session and check permissions
validateSession();

// Get user session data
$userSessionData = getUserSessionData();

// Get CSS for hidden elements based on permissions
$css = getHiddenElementsCSS();

// Access GroupName
$permissions = getUserPermissions();

  $group_name = $permissions['GroupName'];
  $group_id = $permissions['GroupID'];


// $createUser = $permissions['createadmin_user'];

$loginUserId = $userSessionData['emailAddress'];
$apiKeyUserId = $userSessionData['secretKey'];

// Set a default title (can be overridden by individual pages)
$title = $title ?? "Agent Management System";

// if($createUser == 0) {
//   echo "<style> .createadmin_user {display: none} </style>";
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    <?= $title; ?>
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.5" rel="stylesheet" />
  <!-- Font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <!-- Toastr CSS -->
   <link href="../assets/toastr/css/toastr.min.css" rel="stylesheet" />

   <!-- Datepicker CSS -->
   <link href="../assets/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
  <!-- Argon CSS -->
  <?php echo $css; ?>
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Lukeport AMS</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="../Dashboard" class="nav-link active" aria-controls="dashboardsExamples" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-shop text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboards</span>
          </a>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#applicationsExamples" class="nav-link " aria-controls="applicationsExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-ui-04 text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">User Management</span>
          </a>
          <div class="collapse " id="applicationsExamples">
            <ul class="nav ms-4">
              <li class="nav-item createadmin_user">
                <a class="nav-link " href="../CreateUsers/">
                  <span class="sidenav-mini-icon"> W </span>
                  <span class="sidenav-normal">Create User</span>
                </a>
              </li>
              <!-- <li class="nav-item createaggregator_user">
                <a class="nav-link " href="../CreateAggregator/">
                  <span class="sidenav-mini-icon"> K </span>
                  <span class="sidenav-normal"> Create Aggregators </span>
                </a>
              </li> -->
              <!-- <li class="nav-item createsubagent_user">
                <a class="nav-link " href="../CreateAgent">
                  <span class="sidenav-mini-icon"> W </span>
                  <span class="sidenav-normal"> Create Agent </span>
                </a>
              </li> -->
              <li class="nav-item upgradeaggregator_user">
                <a class="nav-link " href="../UpgradeToAggregator">
                  <span class="sidenav-mini-icon"> D </span>
                  <span class="sidenav-normal"> Upgrade To Aggregator </span>
                </a>
              </li>
              <li class="nav-item unlock_user">
                <a class="nav-link " href="../UnlockUser">
                  <span class="sidenav-mini-icon"> D </span>
                  <span class="sidenav-normal"> Unlock User </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#pagesExamples" class="nav-link " aria-controls="pagesExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-ungroup text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Operations</span>
          </a>
          <div class="collapse " id="pagesExamples">
            <ul class="nav ms-4">

              <li class="nav-item accountopening_user">
                <a class="nav-link " data-bs-toggle="collapse" aria-expanded="false" href="#AccountOpening">
                  <span class="sidenav-mini-icon"> P </span>
                  <span class="sidenav-normal"> Account Opening <b class="caret"></b></span>
                </a>
                  <div class="collapse " id="AccountOpening">
                    <ul class="nav nav-sm flex-column">
                      <li class="nav-item ">
                        <a class="nav-link " href="../AvailableBanks">
                          <span class="sidenav-mini-icon text-xs"> N </span>
                          <span class="sidenav-normal"> Savings Account (bvn) </span>
                        </a>
                      </li>
                      <li class="nav-item ">
                        <a class="nav-link " href="../OpenAccountNIN">
                          <span class="sidenav-mini-icon text-xs"> E </span>
                          <span class="sidenav-normal"> Savings Account (VNIN) </span>
                        </a>
                      </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item cardissuance_user">
                <a class="nav-link " href="../cardIssuance">
                  <span class="sidenav-mini-icon"> P </span>
                  <span class="sidenav-normal"> Card Issuance </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
  
        <li class="nav-item createrole_user">
          <a data-bs-toggle="collapse" href="#ecommerceExamples" class="nav-link " aria-controls="ecommerceExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-archive-2 text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Role Management</span>
          </a>
          <div class="collapse " id="ecommerceExamples">
            <ul class="nav ms-4">
             
              <li class="nav-item createrole_user">
                <a class="nav-link " href="../RoleManagement">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> User Role </span>
                </a>
              </li>
              <li class="nav-item updaterole_user">
                <a class="nav-link " href="../UpdateUserRoles">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Update User Role </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#Report" class="nav-link " aria-controls="ecommerceExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-pie-35 text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Report</span>
          </a>
          <div class="collapse " id="Report">
            <ul class="nav ms-4">
             
              <li class="nav-item aggregatorreport_user">
                <a class="nav-link " href="../AggregatorReport">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Aggregator List </span>
                </a>
              </li>
            </li>
              <li class="nav-item agentonboardedreport_user">
                <a class="nav-link " href="../AgentReport">
                  <span class="sidenav-mini-icon"> C </span>
                  <span class="sidenav-normal"> Agent List </span>
                </a>
              </li>
              <li class="nav-item accountopeningreport_user">
                <a class="nav-link " href="../AccountOpeningReport">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Account Opening Report </span>
                </a>
              </li>
              <li class="nav-item cardissuancereport_user">
                <a class="nav-link " href="../CardIssuanceReport">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Card Issuance Report </span>
                </a>
              </li>
              <li class="nav-item view_user">
                <a class="nav-link " href="../User">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Lukeport Users </span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#settings" class="nav-link " aria-controls="ecommerceExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-settings text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
          <div class="collapse " id="settings">
            <ul class="nav ms-4">
             
              <li class="nav-item ">
                <a class="nav-link " href="../Settings">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Profile </span>
                </a>
              </li>
            </ul>
          </li>

        <li class="nav-item">
          <hr class="horizontal dark" />
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 my-3">
        <span class="sidenav-normal">  <i class="ni ni-like-2 text-warning"></i> Welcome! <?= $userSessionData['lastName']; ?></span>
    <a href="../Config/_logout.php" class="btn btn-dark btn-sm w-100 mb-3"><i class="ni ni-button-power text-warning text-sm opacity-10"></i> Log out</a>
    </div>
  </aside>
  <main class="main-content max-height-vh-100 h-100">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl  position-sticky top-1 z-index-sticky" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="text-white" href="javascript:;">
                <i class="ni ni-box-2"></i>
              </a>
            </li>
            <!-- <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Account</a></li> -->
            <li class="breadcrumb-item text-sm text-white active" aria-current="page"><?= $nav_header ?></li>
          </ol>
          <h6 class="font-weight-bolder text-white"><?= $nav_header ?></h6>
        </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none me-auto">
          <a href="javascript:;" class="nav-link text-body p-0">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </a>
        </div>
        <div class="collapse navbar-collapse me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">
          <ul class="navbar-nav justify-content-end ms-auto">
            <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>






  <!-- <main class="main-content position-relative border-radius-lg ">
   
    <main class="main-content position-relative border-radius-lg ">
   
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <h3 class="font-weight-bolder mb-0 text-white">...</h3>
        </nav>
        
      </div>
    </nav>


    <main class="main-content position-relative border-radius-lg ">
   
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="text-white" href="javascript:;">
                <i class="ni ni-box-2"></i>
              </a>
            </li>
            <li class="breadcrumb-item text-sm text-white"><a class="opacity-5 text-white" href="javascript:;"></a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page"></li>
          </ol>
          <h6 class="font-weight-bolder mb-0 text-white"></h6>
        </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
          <a href="javascript:;" class="nav-link p-0">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
              <i class="sidenav-toggler-line bg-white"></i>
            </div>
          </a>
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
           
          </div>
          <ul class="navbar-nav  justify-content-end">
            
           
             <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
             
              <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                
                
               
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav> -->
    <!-- End Navbar -->
    <!-- End Navbar -->