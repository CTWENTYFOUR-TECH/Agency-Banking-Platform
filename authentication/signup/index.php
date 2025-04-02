<?php
 include("../includes/header.php")
?>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../assets/img/sales-agent.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Sign Up</h4>
                  <p class="mb-0">Enter your email and password to register</p>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" autocomplete="off" id="signForm">
                    <div class="input-group mb-3 input-group-outline">
                      <input type="text" class="form-control" name="firstName" id="firstName" required placeholder="Enter First Name" />
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <input type="text" class="form-control" name="lastName" id="lastName" required placeholder="Enter Last Name" />
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <input type="text" class="form-control" name="middleName" id="middleName" placeholder="Enter Middle Name">
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <select class="form-select" aria-label="Default select example" name="gender" id="gender" required >
                        <option selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <input type="email" class="form-control" name="emailLoginId" id="emailLoginId" required placeholder="example@example.com" />
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <input type="email" class="form-control" name="phoneNumber" id="phoneNumber" required placeholder="Enter phone number" />
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <input type="password" class="form-control" name="passwordLogin" id="passwordLogin" required placeholder="Enter strong password" />
                    </div>
                    <div class="input-group mb-3 input-group-outline">
                      <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required placeholder="Enter strong password" />
                    </div>
                    <div class="form-check form-check-info text-start ps-0">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                      <label class="form-check-label" for="flexCheckDefault">
                        I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                      </label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0" id="signupButton">Sign Up</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Already have an account?
                    <a href="../Signin/" class="text-info text-gradient font-weight-bold">Sign in</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php include './../includes/footer.php'; ?>

  
<script>
     $(document).ready(function () {
            $("#signForm").submit(function (event) {
                event.preventDefault(); // Prevent default form submission

                let submitButton = $("#signupButton"); 
                let originalText = submitButton.text(); // Store the original text

                submitButton.prop("disabled", true).text("Please wait..."); // Change text & disable button

                let formData = {
                    firstName: $("#firstName").val(),
                    lastName: $("#lastName").val(),
                    middleName: $("#middleName").val(),
                    gender: $("#gender").val(),
                    emailLoginId: $("#emailLoginId").val(),
                    phoneNumber: $("#phoneNumber").val(),
                    passwordLogin: $("#passwordLogin").val(),
                    confirmPassword: $("#confirmPassword").val()
                };

                $.ajax({
                    url: "../../Config/_agent_signup.php", // PHP file to handle login
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            toastr.success("Sign up successful! Redirecting...", "Success");
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            toastr.error(response.message, "Agent Signup Failed");
                        }
                    },
                    // error: function () {
                    //     toastr.error("Something went wrong. Please try again.", "Error");
                    // },
                    error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log("Response Text:", xhr.responseText);
                    toastr.error("Failed to send report.", "Error");
                    },
                    complete: function () {
                        submitButton.prop("disabled", false).text(originalText); // Revert text & enable button
                    }
                });
            });
        });
    </script>