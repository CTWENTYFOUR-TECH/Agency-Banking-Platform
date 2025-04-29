<?php
<<<<<<< HEAD
$title = "Role Management | Agent Management System";
$nav_header = "Roles Assignment";
include('../includes/header.php');

if (!checkPermissions(PERMISSION_CREATE_ROLE)) {
  header('Location: ../Forbidden/?action=403');
      exit;
  }
  ob_start();

  // Check if the user account status has been updated
  if($userSessionData['accountStatus'] == 0 ){
    echo "<script>
          window.location.href='../Settings'
        </script>";  
    }
?>
=======
  include('../includes/header.php');
?>  
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          
          <h3 class="font-weight-bolder mb-0 text-white">User Roles</h3>
        </nav>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
>>>>>>> 6e26c22824c07efb27e7b83a11577a4fbc861a56
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 mb-4 mb-lg-0">
                <div class="card z-index-2 h-100">
                  <div class="card-header pb-0 pt-3 bg-transparent">
                    
                  </div>
                  <div class="card-body p-3">
                    <form method="POST" autocomplete="off" novalidate id="createRoleForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter the role name" name="roleName" id="roleName"/>
                                </div>
                          	</div>
                      	</div>
                       
                       <div class="container mt-4 mb-4">
                        <div>
                            <h5>User Management</h5>
                           </div>
                        <div class="row">
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="createAdmin" value="1" id="createAdmin">
                            Create Admin
                          </div>
                          <!-- <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="createAggregator" value="1" id="createAggregator">
                            Create Aggregator
                          </div> -->
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="createSubAgent" value="1" id="createSubAgent">
                            Create Subagent
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="updateUserRole" value="1" id="updateUserRole">
                            Update User Role
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="viewUser" value="1" id="viewUser">
                            View User
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="deleteUser" value="1" id="deleteUser">
                            Delete User
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="deactivateUser" value="1" id="deactivateUser">
                            Deactivate User
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="activateUser" value="1" id="activateUser">
                            Activate/Reactivate User
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name ="upgradeAggregator" value="1" id="upgradeAggregator">
                            Upgrade to Aggregator
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name ="unlockUser" value="1" id="unlockUser">
                            Unlock User
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name ="editUser" value="1" id="editUser">
                            Edit User
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name ="createRoles" value="1" id="createRoles">
                              Create Role
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name ="viewRoles" value="1" id="viewRoles">
                              View Roles
                          </div>
                        </div>
                      </div>     
                      <div class="container mt-4 mb-4">
                          <div class="">
                            <h5>Services</h5>
                           </div>
                        <div class="row">
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="accountOpening" value="1" id="accountOpening">
                            Account Opening
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="cardIssuance" value="1" id="cardIssuance">
                            Card Issuance
                          </div>
                        </div>
                      </div>
                      <div class="container mt-4 mb-4">
                        <div>
                          <h5>Report</h5>
                         </div>
                         <div class="row">
                            <div class="col-md-3 form-check">
                              <input class="form-check-input" type="checkbox" name="accountOpeningReport" value="1" id="accountOpeningReport">
                              Account Opening Report
                            </div>
                            <div class="col-md-3 form-check">
                              <input class="form-check-input" type="checkbox" name="cardIssuanceReport" value="1" id="cardIssuanceReport">
                              Card Issuance Report
                            </div>
                            <div class="col-md-3 form-check">
                              <input class="form-check-input" type="checkbox" name="agentOnboardedReport" value="1" id="agentOnboardedReport">
                              Agent Onboarded Report
                            </div>
                            <div class="col-md-3 form-check">
                              <input class="form-check-input" type="checkbox" name="aggregatorReport" value="1" id="aggregatorReport">
                              Aggregator Report
                            </div>
                      	</div> 
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <button type="submit" class="btn btn-primary btn-sm" id="createRoleButton"> Submit </button>
                        </div>
                      </div>
                      </form>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
<<<<<<< HEAD
?> 
<script>
// $(document).ready(function () {
//     $("#createRoleForm").submit(function (event) {
//         event.preventDefault(); // Prevent default form submission

//         let submitButton = $("#createRoleButton");
//         let originalText = submitButton.text(); // Store original text

//         // submitButton.prop("disabled", true).text("Please wait..."); // Disable button & show progress
//         submitButton.prop("disabled", true).html(`
//                     <span class="button-content">
//                         <i class="fa fa-spinner fa-spin fa-fw"></i>
//                         <span> Please wait...</span>
//                     </span>
//                 `);

//         // Ensure unchecked checkboxes submit as "0"
//         $("input[type=checkbox]").each(function () {
//             if (!$(this).is(":checked")) {
//                 $(this).prop("checked", true).val("0");
//             }
//         });

//         let formData = $("#createRoleForm").serialize(); // Serialize form data

//         $.ajax({
//             url: "../Config/_create_roles.php", // PHP file to handle role creation
//             type: "POST",
//             data: formData,
//             dataType: "json",
//             success: function (response) {
//                 if (response.status === "success") {
//                     console.log(response);
//                     toastr.success("Group Roles Created Successfully!", "Success");
//                     setTimeout(() => {
//                         window.location.href = response.redirectUrl;
//                     }, 2000);
//                 } else {
//                     toastr.error(response.message, "Unable to create group role, please try again");
//                 }
//             },
//             error: function (xhr, status, error) {
//                 console.error("AJAX Error:", xhr.responseText);
//                 toastr.error("Something went wrong. Please check the console.", "Error");
//             },
//             complete: function () {
//                 submitButton.prop("disabled", false).text(originalText); // Re-enable button
//             }
//         });
//     });
// });
$(document).ready(function () {
    $("#createRoleForm").submit(function (event) {
        event.preventDefault();

        let submitButton = $("#createRoleButton");
        let originalText = submitButton.text();

        submitButton.prop("disabled", true).html(`
            <span class="button-content">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
                <span> Please wait...</span>
            </span>
        `);

        // Collect all form data including unchecked checkboxes
        let formData = {};
        
        // Add all input fields
        $("#createRoleForm").find("input, select, textarea").each(function() {
            if (this.type === "checkbox") {
                formData[this.name] = $(this).is(":checked") ? "1" : "0";
            } else if (this.name) {
                formData[this.name] = $(this).val();
            }
        });

        $.ajax({
            url: "../Config/_create_roles.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    toastr.success("Group Roles Created Successfully!", "Success");
                    setTimeout(() => {
                        window.location.href = response.redirectUrl;
                    }, 2000);
                } else {
                    toastr.error(response.message, "Unable to create group role, please try again");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                toastr.error("Something went wrong. Please check the console.", "Error");
            },
            complete: function () {
                submitButton.prop("disabled", false).text(originalText);
            }
        });
    });
});
</script>
=======
?>
>>>>>>> 6e26c22824c07efb27e7b83a11577a4fbc861a56
