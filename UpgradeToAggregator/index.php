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
                                <form class="upgradeUser" autocomplete="off" method ="POST" id="upgradeUserForm">
                                    <div class="form-group row">
                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                            <label for="loginEmail" class="form-label">Agent Code</label>
                                            <input type="text" class="form-control" id="agentCode" placeholder="Agent code for the agent" required name="agentCode">
                                        </div>
                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                            <label for="loginEmailConfirmation" class="form-label">Email Confirmation</label>
                                            <input type="text" class="form-control" id="loginEmailConfirmation" required name="loginEmailConfirmation" value="<?php echo isset($loginEmailVerify) ? htmlspecialchars($loginEmailVerify) : ''; ?>" readonly />
                                        </div>
                                        <div class="col-sm-4 mb-3 mb-sm-0">
                                            <label for="agentFullName" class="form-label">Agent Name</label>
                                            <input type="text" class="form-control" id="agentFullName" required name="agentFullName" value="<?php echo isset($agentFullName) ? htmlspecialchars($agentFullName) : ''; ?>" readonly />
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="loginID" name="loginID" value="<?= htmlspecialchars($userSessionData['emailAddress']); ?>">
                                    <button type="submit" class="btn btn-primary w-100 mt-4" id="submitButton">CLICK TO UPGRADE</button>
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

    $("#agentCode").on("keyup", function () {
        clearTimeout(typingTimer); // Cancel previous timer
        let agentCode = $.trim($("#agentCode").val());

        if (agentCode === '') {
            $("#loginEmailConfirmation").val('').prop("disabled", false);
            $("#agentFullName").val('').prop("disabled", false);
            return;
        }

        typingTimer = setTimeout(function () {
            callLockedEmailAPI(agentCode);
        }, doneTypingInterval);
    });

    function callLockedEmailAPI(agentCode) {
        let loginID = $.trim($("#loginID").val());

        $("#loginEmailConfirmation").val("Checking...").prop("disabled", true);
        $("#agentFullName").val("Checking...").prop("disabled", true); // Fixed typo here (was "disable")
        
        $.ajax({
            url: "../Config/_get_agent_code.php",
            type: "POST",
            data: { agentCode: agentCode, loginID: loginID },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    $("#loginEmailConfirmation").val(response.agentEmail || '');
                    $("#agentFullName").val(response.agentName || '');
                    
                    if (!response.agentEmail || !response.agentName) {
                        toastr.error("Agent information incomplete", "Error");
                    }
                } else {
                    $("#loginEmailConfirmation").val('');
                    $("#agentFullName").val('');
                    toastr.error(response.message || "User Not Found.", "Error");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                $("#loginEmailConfirmation").val('').prop("disabled", false);
                $("#agentFullName").val('').prop("disabled", false);
                toastr.error("Failed to fetch agent details. Please try again.", "Error");
            },
            complete: function () {
                $("#loginEmailConfirmation").prop("disabled", false);
                $("#agentFullName").prop("disabled", false);
            }
        });
    }

        $("#upgradeUserForm").submit(function (event) {
            event.preventDefault(); // Prevent default form submission

            let submitButton = $("#submitButton");
            let originalText = submitButton.text(); // Store original text

            //submitButton.prop("disabled", true).text("Unlocking..."); // Disable button & show progress
            submitButton.prop("disabled", true).html(`
                    <span class="button-content">
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                        <span></span>
                    </span>
                `);

            let formData = {
                loginEmailConfirmation: $("#loginEmailConfirmation").val(),
                agentFullName: $("#agentFullName").val(), // Ensure correct ID
                agentCode: $("#agentCode").val(),
                loginID:  $("#loginID").val(),// Ensure correct ID
            };

            // Ensure all fields are filled before making the request
            if (!formData.loginEmailConfirmation || !formData.agentFullName || !formData.agentCode) {
                toastr.error("Please fill all fields before submitting.", "Error");
                submitButton.prop("disabled", false).text(originalText);
                return;
            }

            $.ajax({
                url: "../Config/_upgrade_to_aggregator.php", // PHP file to handle unlocking
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
