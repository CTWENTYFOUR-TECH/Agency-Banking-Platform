<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Argon Dashboard 2 PRO by Creative Tim
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
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/argon-dashboard-pro/pages/dashboards/default.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Argon Dashboard 2 PRO</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="../dashboards/default.html" class="nav-link active" aria-controls="dashboardsExamples" aria-expanded="false">
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
              <li class="nav-item ">
                <a class="nav-link " href="../userManagement/CreateAdmin.php">
                  <span class="sidenav-mini-icon"> W </span>
                  <span class="sidenav-normal">Create Admin</span>
                </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link " href="../userManagement/CreateAggregator.php">
                  <span class="sidenav-mini-icon"> K </span>
                  <span class="sidenav-normal"> Create Aggregators </span>
                </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link " href="../userManagement/CreateAgent.php">
                  <span class="sidenav-mini-icon"> W </span>
                  <span class="sidenav-normal"> Create Sub Agent </span>
                </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link " href="../pages/applications/datatables.php">
                  <span class="sidenav-mini-icon"> D </span>
                  <span class="sidenav-normal"> Upgrade To Aggregator </span>
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

              <li class="nav-item ">
                <a class="nav-link " data-bs-toggle="collapse" aria-expanded="false" href="#AccountOpening">
                  <span class="sidenav-mini-icon"> P </span>
                  <span class="sidenav-normal">Account Opening <b class="caret"></b></span>
                </a>
                  <div class="collapse " id="AccountOpening">
                    <ul class="nav nav-sm flex-column">
                      <li class="nav-item">
                        <a class="nav-link " href="../OpenAccountBVN/index.php">
                          <span class="sidenav-mini-icon text-xs"> N </span>
                          <span class="sidenav-normal">Savings Account (bvn) </span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="../OpenAccountNIN/index.php">
                          <span class="sidenav-mini-icon text-xs"> E </span>
                          <span class="sidenav-normal">Savings Account (VNIN) </span>
                        </a>
                      </li>
                     
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="../CardIssuance/index.php">
                  <span class="sidenav-mini-icon"> P </span>
                  <span class="sidenav-normal"> Card Issuance </span>
                </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link " data-bs-toggle="collapse" aria-expanded="false" href="#IdentityCheck">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Identity Check <b class="caret"></b></span>
                </a>
                <div class="collapse " id="IdentityCheck">
                    <ul class="nav nav-sm flex-column">
                       <li class="nav-item">
                        <a class="nav-link " href="../BVN_Check/index.html">
                          <span class="sidenav-mini-icon text-xs"> N </span>
                          <span class="sidenav-normal"> BVN Check </span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="../NIN_Check/index.html">
                          <span class="sidenav-mini-icon text-xs"> N </span>
                          <span class="sidenav-normal"> NIN Check </span>
                        </a>
                      </li>
                    </ul>
                </div>
              </li>
              
            </ul>
          </div>
        </li>
  
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#ecommerceExamples" class="nav-link " aria-controls="ecommerceExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-archive-2 text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Role Management</span>
          </a>
          <div class="collapse " id="ecommerceExamples">
            <ul class="nav ms-4">
             
              <li class="nav-item ">
                <a class="nav-link " href="../roleManagement/index.php">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> User Role </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#Report" class="nav-link " aria-controls="ecommerceExamples" role="button" aria-expanded="false">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
              <i class="ni ni-archive-2 text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Report</span>
          </a>
          <div class="collapse " id="Report">
            <ul class="nav ms-4">
              <li class="nav-item ">
                <a class="nav-link " href="../roleManagement/tables.html">
                  <span class="sidenav-mini-icon"> R </span>
                  <span class="sidenav-normal"> Aggregator list </span>
                </a>
              </li>
              <li class="nav-item ">
                <a class="nav-link " href="../../pages/applications/calendar.html">
                  <span class="sidenav-mini-icon"> C </span>
                  <span class="sidenav-normal"> Agent List </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../Settings/">
            <span class="sidenav-mini-icon"> P </span>
            <span class="sidenav-normal"> Settings </span>
          </a>
        </li>
        <li class="nav-item">
          <hr class="horizontal dark" />
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 my-3">
      <a href="https://www.creative-tim.com/learning-lab/bootstrap/overview/argon-dashboard" target="_blank" class="btn btn-dark btn-sm w-100 mb-3">Log out</a>
    </div>
  </aside>