<?php
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
                    <form>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="john" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>Middle Name:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="doe" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Last Name:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Email Address:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  placeholder="jon...@gmail.com"  required>
                              </div>
                            </div>
                        </div>
                         
                        </div>
                        <button type="button" class="btn btn-primary"> Submit </button>
                      </form>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>