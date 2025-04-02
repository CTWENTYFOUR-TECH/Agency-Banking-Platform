<?php
 $title = "Profile | Agent Management System";
 $nav_header = "Profile";
  include('../includes/header.php');

  if($userSessionData['accountStatus'] == 0){
    echo "<style>
      #delete, #UpdatePassword {
      display: none
    }
    </style>";
  }else if($userSessionData['accountStatus'] == 1){
    echo "<style>
      #activeStatusIs1 {
      display: none
    }
    </style>";
  }
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
                  <?= $userSessionData['fullName']; ?>
                  </h5>
                  <p class="mb-0 font-weight-bold text-sm">
                    <?= @$group_id ." <p>Agent Code:". $userSessionData['agentCode']."</p>"; ?>
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
            <form method ="POST" id="updateProfileForm" autocomplete="off" role="form">
              <div class="row">
                <div class="col-6">
                  <label class="form-label">First Name</label>
                  <div class="input-group">
                    <input id="firstName" name="firstName" class="form-control" type="text" placeholder="Enter Firstname" readonly value = "<?= $userSessionData['firstName'] ?>">
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label">Last Name</label>
                  <div class="input-group">
                    <input id="lastName" name="lastName" class="form-control" type="text" placeholder="Enter Lastname" readonly value = "<?= $userSessionData['lastName'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <label class="form-label mt-4">I'm</label>
                    <input id="gender" name="gender" class="form-control" type="text" placeholder="Gender" readonly value = "<?= $userSessionData['gender'] ?>">
                </div>
                <div class="col-6">
                  <label class="form-label mt-4">Email</label>
                  <div class="input-group">
                    <input id="email" name="email" class="form-control" type="email"  readonly value = "<?= $userSessionData['emailAddress'] ?>">
                  </div>
                </div>
              </div>
              <div class="row">
              <div class="col-6" id="activeStatusIs1">
                  <label class="form-label mt-4">Birth Date<span class="text-danger">*</span></label>
                  <div class="input-group date" id="dateofbirthPicker">
                      <input id="dateofbirth" name="dateofbirth" class="form-control" type="text" placeholder="Enter date of birth" required width ="100%">
                      <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                  </div>
              </div>
                <div class="col-6" id="activeStatusIs1">
                  <label class="form-label mt-4">State<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select name="state" id="state" class="form-select">
                      <option>...</option>
                      <option value="Abia">Abia</option>
                      <option value="Adamawa">Adamawa</option>
                      <option value="Akwa Ibom">Akwa Ibom</option>
                      <option value="Bauchi">Bauchi</option>
                      <option value="Bayelsa">Bayelsa</option>
                      <option value="Benue">Benue</option>
                      <option value="Borno">Borno</option>
                      <option value="Cross River">Cross River</option>
                      <option value="Delta">Delta</option>
                      <option value="Ebonyi">Ebonyi</option>
                      <option value="Edo">Edo</option>
                      <option value="Ekiti">Ekiti</option>
                      <option value="FCT">FCT Abuja</option>
                      <option value="Gombe">Gombe</option>
                      <option value="Imo">Imo</option>
                      <option value="Jigawa">Jigawa</option>
                      <option value="Kaduna">Kaduna</option>
                      <option value="Kano">Kano</option>
                      <option value="Katsina">Katsina</option>
                      <option value="Kebbi">Kebbi</option>
                      <option value="Kogi">Kogi</option>
                      <option value="Kwara">Kwara</option>
                      <option value="Lagos">Lagos</option>
                      <option value="Nasarawa">Nasarawa</option>
                      <option value="Niger">Niger</option>
                      <option value="Ogun">Ogun</option>
                      <option value="Ondo">Ondo</option>
                      <option value="Osun">Osun</option>
                      <option value="Oyo">Oyo</option>
                      <option value="Plateau">Plateau</option>
                      <option value="Rivers">Rivers</option>
                      <option value="Sokoto">Sokoto</option>
                      <option value="Taraba">Taraba</option>
                      <option value="Yobe">Yobe</option>
                      <option value="Zamfara">Zamfara</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6" id="activeStatusIs1">
                  <label class="form-label mt-4">BVN<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="bvn" name="bvn" class="form-control" type="text" placeholder="Enter BVN" required oninput="limitInputLength(this)">
                  </div>
                </div>
                <div class="col-6" id="activeStatusIs1">
                  <label class="form-label mt-4">Phone Number<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="phoneNumber" name="phoneNumber" class="form-control" type="number" value="<?= $userSessionData['phoneNumber'] ?>"  required readonly>
                  </div>
                </div>
              </div>
              <div class="row" id="activeStatusIs1">
                <div class="col-6">
                  <label class="form-label mt-4">Residential Address<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="residentialAddress" name="residentialAddress" class="form-control" type="text" placeholder="Enter Residential Address" required>
                  </div>
                </div>
                <div class="col-6">
                  <label class="form-label mt-4">Business Address<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="businessAddress" name="businessAddress" class="form-control" type="text" placeholder="Enter Business Address" required>
                  </div>
                </div>
              </div>
              <div class="row" id="activeStatusIs1">
                <div class="col-md-6 align-self-center">
                  <label class="form-label mt-4">Business Name<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="businessName" name="businessName" class="form-control" type="text" placeholder="Enter Business Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label mt-4">Business Number/RC Number(optional)</label>
                  <div class="input-group">
                    <input id="registeredNo" name="registeredNo" class="form-control" type="text" placeholder="Enter Business Number">
                  </div>
                </div>
                <div class="col-6" id="activeStatusIs1">
                  <label class="form-label mt-4">City<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="city" name="city" class="form-control" type="text" placeholder="Enter City" required>
                  </div>
                </div>
                <div class="col-6" id="activeStatusIs1">
                  <label class="form-label mt-4">Landmark<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input id="landmark" name="landmark" class="form-control" type="text" placeholder="Closest description of your place" required>
                  </div>
                </div>
              </div>
              <div class="card-body pt-0" id="activeStatusIs1">
                <button class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0" id="updateProfileButton">Update Profile</button>
              </div>
            </div>
          </form>
          </div>
          <!-- Card Change Password -->
          <div class="card mt-4" id="UpdatePassword">
            <div class="card-header">
              <h5>Change Password</h5>
            </div>
                <form method ="POST" id="updatePasswordForm" autocomplete="off" role="form">
                  <div class="card-body pt-0">
                    <label class="form-label">Current password</label>
                    <div class="form-group">
                      <input class="form-control" type="password" placeholder="Current password" name="current_password" id="current_password" required>
                    </div>
                    <label class="form-label">New password</label>
                    <div class="form-group">
                      <input class="form-control" type="password" placeholder="New password" id="new_password" name="new_password" required>
                    </div>
                    <label class="form-label">Confirm new password</label>
                    <div class="form-group">
                      <input class="form-control" type="password" placeholder="Confirm password" id="confirm_password" name="confirm_password" required>
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
                    <button class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0" id="updatePasswordButton">Update password</button>
                  </div>
              </form>
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
        <form method="POST" id="deleteAccountForm">
            <div class="card-body d-sm-flex pt-0">
              <div class="d-flex align-items-center mb-sm-0 mb-4">
                <div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault0" required>
                  </div>
                </div>
                <div class="ms-2">
                  <span class="text-dark font-weight-bold d-block text-sm">Confirm</span>
                  <span class="text-xs d-block">I want to delete my account.</span>
                </div>
              </div>
              <button class="btn btn-outline-secondary mb-0 ms-auto" type="button" name="button" id="deactivateAccountButton">Deactivate</button>
              <button class="btn bg-gradient-danger mb-0 ms-2" type="button" name="button" id="deleteAccountButton">Delete Account</button>
            </div>
          </form>
          </div>
        </div>
      </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>
<script>
$(document).ready(function() {
    $("#updateProfileForm").submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        
        // Get form elements
        let submitButton = $("#updateProfileButton");
        let originalText = submitButton.html();
        let form = $(this);
        
        // Validate required fields
        let requiredFields = ['dateofbirth', 'state', 'city', 'phoneNumber', 'residentialAddress'];
        let isValid = true;
        
        requiredFields.forEach(function(field) {
            if (!$("#" + field).val()) {
                toastr.error(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field`, "Validation Error");
                isValid = false;
            }
        });
        
        if (!isValid) return;
        
        // Show loading state
        submitButton.prop("disabled", true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
        );
        
        // Prepare form data
        let formData = {
            dateofbirth: $("#dateofbirth").val(),
            state: $("#state").val(),
            city: $("#city").val(),
            bvn: $("#bvn").val(),
            phoneNumber: $("#phoneNumber").val(),
            residentialAddress: $("#residentialAddress").val(),
            businessAddress: $("#businessAddress").val(),
            businessName: $("#businessName").val(),
            landmark: $("#landmark").val(),
            registeredNo: $("#registeredNo").val(),
            email: $("#email").val()
        };
        
        // Phone number validation
        let phoneRegex = /^[0-9]{11}$/;
        if (!phoneRegex.test(formData.phoneNumber)) {
            toastr.error("Please enter a valid 11-digit phone number", "Validation Error");
            submitButton.prop("disabled", false).html(originalText);
            return;
        }
        
        // Date of birth validation
        let dob = new Date(formData.dateofbirth);
        let today = new Date();
        let minAgeDate = new Date();
        minAgeDate.setFullYear(today.getFullYear() - 18);
        
        if (dob > minAgeDate) {
            toastr.error("You must be at least 18 years old", "Validation Error");
            submitButton.prop("disabled", false).html(originalText);
            return;
        }
        
        // AJAX request
        $.ajax({
            url: "../Config/_sign_up_continuation.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    toastr.success(response.message || "Profile updated successfully", "Success");
                    
                    // Redirect if URL provided
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);
                    }
                } else {
                    toastr.error(response.message || "Profile could not be updated", "Error");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.log("Response Text:", xhr.responseText);
                
                let errorMessage = "An error occurred while updating your profile";
                try {
                    let jsonResponse = JSON.parse(xhr.responseText);
                    if (jsonResponse.message) {
                        errorMessage = jsonResponse.message;
                    }
                } catch (e) {
                    console.log("Couldn't parse error response");
                }
                
                toastr.error(errorMessage, "Error");
            },
            complete: function() {
                submitButton.prop("disabled", false).html(originalText);
            }
        });
    });
    
    // Initialize datepicker with constraints
    $("#dateofbirth").attr("max", new Date().toISOString().split('T')[0]);

        $('#dateofbirth').datepicker({
        format: 'yyyy-mm-dd',
        endDate: '-18y', // Must be at least 18 years old
        startDate: '-100y', // Maximum 100 years old
        autoclose: true,
        todayHighlight: true,  // Highlight today's date
        startDate: '-100y',    // Allow selection up to 100 years ago
        orientation: 'bottom auto' // Position of the datepicker
    });


    // Update Password

    $("#deleteAccountButton").click(function(event) {
    event.preventDefault(); // Prevent default form submission

    // Confirm deletion with user
    if (!confirm("Are you sure you want to permanently delete your account? This action cannot be undone.")) {
        return false;
    }
    
    // Get form elements
    let submitButton = $(this);
    let originalText = submitButton.html();
    let form = $("#deleteAccountForm");
    
    // Show loading state
    submitButton.prop("disabled", true).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting Account...'
    );
    
    // Check if the toggle button is checked
    let isToggleChecked = $("#flexSwitchCheckDefault0").is(":checked");
    
    if (!isToggleChecked) {
        toastr.error("Please check the confirmation checkbox to proceed", "Validation Error");
        submitButton.prop("disabled", false).html(originalText);
        return;
    }
    
    // Prepare form data
    let formData = {
        flexSwitchCheckDefault0: isToggleChecked ? "1" : "0"
    };

    // AJAX request
    $.ajax({
        url: "../Config/_delete_account.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                toastr.success(response.message || "Account deleted successfully.", "Success");
                
                // Redirect if URL provided
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            } else {
                toastr.error(response.message || "Unable to delete account. Please try again later.", "Error");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log("Response Text:", xhr.responseText);
            
            let errorMessage = "An error occurred while deleting your account";
            try {
                let jsonResponse = JSON.parse(xhr.responseText);
                if (jsonResponse.message) {
                    errorMessage = jsonResponse.message;
                }
            } catch (e) {
                console.log("Couldn't parse error response");
            }
            
            toastr.error(errorMessage, "Error");
        },
        complete: function() {
            submitButton.prop("disabled", false).html(originalText);
        }
      });
  });

  // Deactivate Account
  $("#deactivateAccountButton").click(function(event) {
    event.preventDefault(); // Prevent default form submission

    // Get form elements
    let submitButton = $(this);
    let originalText = submitButton.html();
    let form = $("#deleteAccountForm");
    
    // Show loading state
    submitButton.prop("disabled", true).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deactivating Account...'
    );
    
    // Get form data
    let formData = form.serialize(); // Correctly serialize the form data

    // Optional: Add confirmation dialog (uncomment if needed)
    // if (!confirm("Are you sure you want to deactivate your account?")) {
    //     submitButton.prop("disabled", false).html(originalText);
    //     return false;
    // }

    // AJAX request
    $.ajax({
        url: "../Config/_deactivate_account.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                toastr.success(response.message || "Account deactivated successfully.", "Success");
                
                // Redirect if URL provided
                if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                }
            } else {
                toastr.error(response.message || "Unable to deactivate account. Please try again later.", "Error");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log("Response Text:", xhr.responseText);
            
            let errorMessage = "An error occurred while deactivating your account";
            try {
                let jsonResponse = JSON.parse(xhr.responseText);
                if (jsonResponse.message) {
                    errorMessage = jsonResponse.message;
                }
            } catch (e) {
                console.log("Couldn't parse error response");
            }
            
            toastr.error(errorMessage, "Error");
        },
        complete: function() {
            submitButton.prop("disabled", false).html(originalText);
        }
    });
});

// UPDATE PASSWORD

$("#updatePasswordForm").submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        let submitButton = $("#updatePasswordButton");
        let originalText = submitButton.text(); // Store original text

        // submitButton.prop("disabled", true).text("Please wait...while user is created"); // Disable button & show progress
        submitButton.prop("disabled", true).html(`
                    <span class="button-content">
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                        <span> Updating password...</span>
                    </span>
                `);
        let formData = {
                    current_password: $("#current_password").val(),
                    new_password: $("#new_password").val(),
                    confirm_password: $("#confirm_password").val()
                };
        $.ajax({
            url: "../Config/_update_password.php", // PHP file to handle role creation
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastr.success("Update Password Successfully!", "Success");
                    setTimeout(() => {
                        window.location.href = response.redirectUrl;
                    }, 2000);
                } else {
                    toastr.error(response.message, "Unable to create user, please try again");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                toastr.error("Something went wrong. Please check the console.", "Error");
            },
            complete: function () {
                submitButton.prop("disabled", false).text(originalText); // Re-enable button
            }
        });
    });

});
      </script>