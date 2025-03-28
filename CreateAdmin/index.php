<?php
$title = "Create Admin | Agent Managment System";
  include('../includes/header.php');
?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          
          <h3 class="font-weight-bolder mb-0 text-white">Create Admin</h3>
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
                              <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" required>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label for="middleName" class="form-label">Middle Name:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="middleName" placeholder="Enter Middle Name" required>
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
                                <input type="email" class="form-control" id="exampleFormControlInput1"  placeholder="jon...@gmail.com"  required>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="phoneNumber">Phone Number:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number"  required>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label for="roles">Choose Group Role:<span class="text-danger">*</span></label>
                                  <select class="form-control" id="roles" name="roles" required>
                                    <option value="">Select Roles</option>
                                  </select>
                              </div>
                            </div>
                        </div>
                         
                        </div>
                        <button type="submit" class="btn btn-primary" class="create_admin"> Submit </button>
                      </form>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>