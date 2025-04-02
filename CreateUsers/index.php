<?php
$title = "Unlock User | Agent Management System";
$nav_header = "User Setup";
include('../includes/header.php');

if (!checkPermissions(PERMISSION_CREATE_ADMIN_USER)) {
  header('Location: ../Forbidden/?action=403');
      exit;
  }
  ob_start();
?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          
          <!-- <h3 class="font-weight-bolder mb-0 text-white">Create Admin</h3> -->
        </nav>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 mb-4 mb-lg-0">
                <div class="card z-index-2 h-100">
                  <div class="card-header pb-0 pt-3 bg-transparent">
                    
                  </div>
                  <div class="card-body p-3">
                    <form method="POST" id="adminCreation" novalidate autocomplete="off">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstName" class="form-label">First Name:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" name="firstName" required />
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="middleName" class="form-label">Middle Name:</label>
                              <input type="text" class="form-control" id="middleName" placeholder="Enter Middle Name" name="middleName" />
                            </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="lastName" class="form-label">Last Name:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required placeholder="Enter Last Name">
                              </div>
                            </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="gender">Gender:<span class="text-danger">*</span></label>
                                  <select class="form-control" id="gender" name="gender" required>
                                    <option value="">...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                  </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>Email Address:<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="staffEmail"  placeholder="jon...@gmail.com" name="staffEmail"  required>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="phoneNumber">Phone Number:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" required oninput="limitInputLength(this)">
                              </div>
                            </div>
                            <div class="col-md-4">
                            <?php if($group_id !== "AGGREGATOR"){ ?>
                            <div class="form-group">
                                  <label for="roles">Assign a role<span class="text-danger">*</span></label>
                                  <select class="form-select form-control" aria-label="Roles assignment" name="roles" id="roles">
                                    <option selected>Choose a role for this user</option>
                                  </select>
                              </div>
                            <?php }else{ ?>
                              <!-- <label for="roles">Assign a role<span class="text-danger">*</span></label> -->
                              <input type="hidden" class="form-control" id="roles" name="roles" value="AGENT" readonly>
                              <input type="text" class="form-control" id="aggregatorCode" name="aggregatorCode" value="<?= $userSessionData['agentCode'] ?>" readonly />
                            <?php } ?>
                            </div>
                          <div class="row">
                            <div class="col-md-4">
                                  <div class="form-group">
                                      <input type="checkbox" class="form-check-input" id="checkbox" name="checkbox" required /><span>I acknowledged that user consented to the creation</span>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitButton"> Submit </button>
                      </form>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>
<script>
$(document).ready(function () {
  $('#roles').html('<option value="" disabled selected>Loading groups...</option>');
    
    $.ajax({
        url: '../Config/_getAllGroup.php',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log('Full response:', response); // Debug log
            
            if (response.status === 'success') {
                $('#roles').html(response.options);
            } else {
                let errorMsg = response.message || 'Unknown error occurred';
                // console.error('Server reported error:', errorMsg);
                toastr.error('Error: ' + errorMsg);
            }
        },
        error: function(xhr, status, error) {
            // console.error('AJAX Error Details:', {
            //     status: xhr.status,
            //     statusText: xhr.statusText,
            //     responseText: xhr.responseText,
            //     error: error
            // });
            
            let errorMsg = 'An error occurred while loading groups. ';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg += 'Details: ' + xhr.responseJSON.message;
            } else {
                errorMsg += 'Please try again later.';
            }
            toastr.error(errorMsg);
        }
    });

  $("#adminCreation").submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        let submitButton = $("#submitButton");
        let originalText = submitButton.text(); // Store original text

        // submitButton.prop("disabled", true).text("Please wait...while user is created"); // Disable button & show progress
        submitButton.prop("disabled", true).html(`
                    <span class="button-content">
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                        <span> Please wait... while admin is created</span>
                    </span>
                `);

        // Ensure unchecked checkboxes submit as "0"
        $("input[type=checkbox]").each(function () {
            if (!$(this).is(":checked")) {
                $(this).prop("checked", true).val("0");
            }
        });

        let formData = {
                    firstName: $("#firstName").val(),
                    lastName: $("#lastName").val(),
                    middleName: $("#middleName").val(),
                    gender: $("#gender").val(),
                    staffEmail: $("#staffEmail").val(),
                    phoneNumber: $("#phoneNumber").val(),
                    roles: $("#roles").val(),
                    aggregatorCode: $("#aggregatorCode").val()
                };

        $.ajax({
            url: "../Config/_adminCreation.php", // PHP file to handle role creation
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastr.success("User Created Successfully!", "Success");
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