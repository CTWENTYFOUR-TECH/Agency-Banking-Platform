<?php
  include('../includes/header.php');
?>

<main class="main-content max-height-vh-100 h-100">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl ">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          
          <h6 class="font-weight-bolder text-white">Settings</h6>
        </nav>
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
    
    <div class="container-fluid my-5 py-2">
      <div class="row mb-5">
        <div class="col-lg-3">
          <div class="card position-sticky top-1">
            <ul class="nav flex-column bg-white border-radius-lg p-3">
              <li class="nav-item">
                <a class="nav-link text-body d-flex align-items-center" data-scroll="" href="#profile">
                  <i class="ni ni-spaceship me-2 text-dark opacity-6"></i>
                  <span class="text-sm">Profile</span>
                </a>
              </li>
              <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll="" href="#basic-info">
                  <i class="ni ni-books me-2 text-dark opacity-6"></i>
                  <span class="text-sm">Basic Info</span>
                </a>
              </li>
              <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll="" href="#password">
                  <i class="ni ni-atom me-2 text-dark opacity-6"></i>
                  <span class="text-sm">Change Password</span>
                </a>
              </li>
              
              <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll="" href="#delete">
                  <i class="ni ni-settings-gear-65 me-2 text-dark opacity-6"></i>
                  <span class="text-sm">Delete Account</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-9 mt-lg-0 mt-4">
          <!-- Card Profile -->
          <div class="card card-body" id="profile">
            <div class="row justify-content-center align-items-center">
              <div class="col-sm-auto col-4">
                <div class="avatar avatar-xl position-relative">
                  <img src="../assets/img/team-3.jpg" alt="bruce" class="w-100 border-radius-lg shadow-sm">
                </div>
              </div>
              <div class="col-sm-auto col-8 my-auto">
                <div class="h-100">
                  <h5 class="mb-1 font-weight-bolder">
                    Mark Johnson
                  </h5>
                  <p class="mb-0 font-weight-bold text-sm">
                    CEO / Co-Founder
                  </p>
                </div>
              </div>
              <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">
                <label class="form-check-label mb-0">
                  <small id="profileVisibility">
                    Switch to invisible
                  </small>
                </label>
                <div class="form-check form-switch ms-2">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault23" checked onchange="visible()">
                </div>
              </div>
            </div>
          </div>
          <!-- Card Basic Info -->
          <div class="card mt-4" id="basic-info">
            <div class="card-header">
              <h5>Basic Info</h5>
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col-6">
                  <label class="form-label">First Name</label>
                  <div class="input-group">
                    <input id="firstName" name="firstName" class="form-control" type="text" placeholder="Alec" required="required">
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label">Last Name</label>
                  <div class="input-group">
                    <input id="lastName" name="lastName" class="form-control" type="text" placeholder="Thompson" required="required">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label mt-4">I'm</label>
                  <select class="form-control" name="choices-gender" id="choices-gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
                <div class="col-6">
                    <label class="form-label mt-4">Birth Date</label>
                    <!-- <select class="form-control" name="choices-month" id="choices-month"></select> -->
                    <input id="date" name="date" class="form-control" type="date" placeholder="Choose-Month">
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label mt-4">Email</label>
                  <div class="input-group">
                    <input id="email" name="email" class="form-control" type="email" placeholder="example@email.com">
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label mt-4">State</label>
                  <div class="input-group">
                    <input id="confirmation" name="confirmation" class="form-control" type="email" placeholder="example@email.com">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label mt-4">BVN</label>
                  <div class="input-group">
                    <input id="bvn" name="bvn" class="form-control" type="text" placeholder="Enter BVN">
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label mt-4">Phone Number</label>
                  <div class="input-group">
                    <input id="phone" name="phone" class="form-control" type="number" placeholder="+40 735 631 620">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label mt-4">Residential Address</label>
                  <div class="input-group">
                    <input id="Residential-Address" name="Residential-Address" class="form-control" type="text" placeholder="Enter Residential Address">
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label mt-4">Business Address</label>
                  <div class="input-group">
                    <input id="Business-Address" name="Business-Address" class="form-control" type="text" placeholder="Enter Business Address">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 align-self-center">
                  <label class="form-label mt-4">Business Name</label>
                  <div class="input-group">
                    <input id="Business-Name" name="Business-Name" class="form-control" type="text" placeholder="Enter Business Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label mt-4">Business Number/RC Number(optional)</label>
                  <div class="input-group">
                    <input id="Business-Number" name="Business-Number" class="form-control" type="text" placeholder="Enter Business Number">
                  </div>
                </div>
              </div>
            <div class="card-body pt-0">
              <button class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0">Update Profile</button>
            </div>
            </div>
          </div>
          <!-- Card Change Password -->
          <div class="card mt-4" id="password">
            <div class="card-header">
              <h5>Change Password</h5>
            </div>
            <div class="card-body pt-0">
              <label class="form-label">Current password</label>
              <div class="form-group">
                <input class="form-control" type="password" placeholder="Current password">
              </div>
              <label class="form-label">New password</label>
              <div class="form-group">
                <input class="form-control" type="password" placeholder="New password">
              </div>
              <label class="form-label">Confirm new password</label>
              <div class="form-group">
                <input class="form-control" type="password" placeholder="Confirm password">
              </div>
              <h5 class="mt-5">Password requirements</h5>
              <p class="text-muted mb-2">
                Please follow this guide for a strong password:
              </p>
              <ul class="text-muted ps-4 mb-0 float-start">
                <li>
                  <span class="text-sm">One special characters</span>
                </li>
                <li>
                  <span class="text-sm">Min 6 characters</span>
                </li>
                <li>
                  <span class="text-sm">One number (2 are recommended)</span>
                </li>
                <li>
                  <span class="text-sm">Change it often</span>
                </li>
              </ul>
              <button class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0">Update password</button>
            </div>
          </div>
          <!-- Card Change Password -->
          
          <!-- Card Accounts -->
          
          <!-- Card Notifications -->
          
          <!-- Card Sessions -->
         
          <!-- Card Delete Account -->
          <div class="card mt-4" id="delete">
            <div class="card-header">
              <h5>Delete Account</h5>
              <p class="text-sm mb-0">Once you delete your account, there is no going back. Please be certain.</p>
            </div>
            <div class="card-body d-sm-flex pt-0">
              <div class="d-flex align-items-center mb-sm-0 mb-4">
                <div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault0">
                  </div>
                </div>
                <div class="ms-2">
                  <span class="text-dark font-weight-bold d-block text-sm">Confirm</span>
                  <span class="text-xs d-block">I want to delete my account.</span>
                </div>
              </div>
              <button class="btn btn-outline-secondary mb-0 ms-auto" type="button" name="button">Deactivate</button>
              <button class="btn bg-gradient-danger mb-0 ms-2" type="button" name="button">Delete Account</button>
            </div>
          </div>
        </div>
      </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>