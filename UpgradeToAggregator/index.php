<?php
$title = "Unlock User | Agent Management System";
$nav_header = "Unlock User Account";
include('../includes/header.php');

if (!checkPermissions(PERMISSION_UPGRADE_AGGREGATOR)) {
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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 mb-4 mb-lg-0">
                <div class="card z-index-2 h-100">
                  <div class="card-header pb-0 pt-3 bg-transparent">
                    
                  </div>
                  <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-4">
                                <form class="userUnlock" autocomplete="off" method ="POST" id="userUnlock">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="loginEmail" class="form-label">Login Email</label>
                                            <input type="text" class="form-control" id="loginEmail" placeholder="Login Email Address" required name="loginEmail">
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="loginEmailConfirmation" class="form-label">Email Confirmation</label>
                                            <input type="text" class="form-control" id="loginEmailConfirmation" required name="loginEmailConfirmation" value="<?php echo isset($loginEmailVerify) ? htmlspecialchars($loginEmailVerify) : ''; ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="setNewPassword" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="setNewPassword" placeholder="New Password" required name="setNewPassword">
                                        </div>
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required name="confirmPassword" />
                                            <input type="hidden" class="form-control" id="loginID" name="loginID" value="<?= htmlspecialchars($userSessionData['emailAddress']); ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-4" id="submitButton">UNLOCK NOW</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
        let typingTimer;
        let doneTypingInterval = 1500; // 1.5-second delay after typing stops

        $("#loginEmail").on("keyup", function () {
            clearTimeout(typingTimer); // Cancel previous timer
            let loginEmail = $.trim($("#loginEmail").val());

            if (loginEmail === '') {
                $("#loginEmailConfirmation").val('').prop("disabled", false); // Clear and re-enable
                return;
            }

            typingTimer = setTimeout(function () {
                callLockedEmailAPI(loginEmail);
            }, doneTypingInterval);
        });

        function callLockedEmailAPI(loginEmail) {
            let loginID = $.trim($("#loginID").val());

            $("#loginEmailConfirmation").val("Checking...").prop("disabled", true); // Show loading state

            $.ajax({
                url: "../Config/_getLockedEmail.php", // API endpoint
                type: "POST",
                data: { loginEmail: loginEmail, loginID: loginID },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success" && response.lockedEmail) {
                        $("#loginEmailConfirmation").val(response.lockedEmail);
                    } else {
                        $("#loginEmailConfirmation").val(''); // Clear if not found
                        toastr.error(response.message || "Email not locked.", "Error");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText); // Log error response
                    toastr.error("Something went wrong. Please check the console.", "Error");
                },
                complete: function () {
                    $("#loginEmailConfirmation").prop("disabled", false); // Re-enable field
                }
            });
        }

        $("#userUnlock").submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            let submitButton = $("#submitButton");
            let originalText = submitButton.text(); // Store original text

            submitButton.prop("disabled", true).text("Unlocking..."); // Disable button & show progress

            let formData = {
                loginEmailConfirmation: $("#loginEmailConfirmation").val(),
                setNewPassword: $("#setNewPassword").val(), // Ensure correct ID
                confirmPassword: $("#confirmPassword").val(),
                loginID:  $("#loginID").val(),// Ensure correct ID
            };

            // Ensure all fields are filled before making the request
            if (!formData.loginEmailConfirmation || !formData.setNewPassword || !formData.confirmPassword) {
                toastr.error("Please fill all fields before submitting.", "Error");
                submitButton.prop("disabled", false).text(originalText);
                return;
            }

            $.ajax({
                url: "../Config/_unlockLockedEmail.php", // PHP file to handle unlocking
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                  console.log(response);
                    if (response.status === "success") {
                        toastr.success(response.message, "Success");
                        setTimeout(() => {
                            window.location.href = response.redirectUrl;
                        }, 2000);
                    } else {
                        toastr.error(response.message, "Unable to unlock user, please try again");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText); // Log error response
                    toastr.error("Something went wrong. Please check the console.", "Error");
                },
                complete: function () {
                    submitButton.prop("disabled", false).text(originalText); // Re-enable button
                }
            });
        });
    });
</script>
