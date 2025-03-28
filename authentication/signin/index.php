<?php
 include("./../includes/header.php")
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
                  <h4 class="font-weight-bolder">Sign In</h4>
                  <p class="mb-0">Enter your email and password to Sign In</p>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" novalidate autocomplete="off" id="loginForm">
                    <div class="input-group mb-3">
                      <!-- <label class="form-label" for="emailAddress">Email</label> -->
                      <input type="email" class="form-control" name="emailAddress" id="emailAddress" placeholder="Enter Email Address" required>
                    </div>
                    <div class="input-group mb-3">
                      <!-- <label class="form-label" for="password">Password</label> -->
                      <input type="password" class="form-control" id="password" name="password" required placeholder="Enter Password">
                    </div>
                    <div class="text-center">
                      <button type="submit" id="submitButton" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Sign In</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Don't have an account?
                    <a href="../Signup/" class="text-info text-gradient font-weight-bold">Sign Up</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php
 include("./../includes/footer.php")
?>

<script>
        $(document).ready(function () {
            $("#loginForm").submit(function (event) {
                event.preventDefault(); // Prevent default form submission

                let submitButton = $("#submitButton"); 
                let originalText = submitButton.text(); // Store the original text

                submitButton.prop("disabled", true).text("Please wait...Logging you in"); // Change text & disable button

                let formData = {
                    emailAddress: $("#emailAddress").val(),
                    password: $("#password").val()
                };

                $.ajax({
                    url: "../../Config/_login.php", // PHP file to handle login
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            toastr.success("Login successful! Redirecting...", "Success");
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            toastr.error(response.message, "Login Failed");
                        }
                    },
                    error: function () {
                        toastr.error("Something went wrong. Please try again.", "Error");
                    },
                    complete: function () {
                        submitButton.prop("disabled", false).text(originalText); // Revert text & enable button
                    }
                });
            });
        });
    </script>