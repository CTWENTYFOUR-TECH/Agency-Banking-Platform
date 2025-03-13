<?php
  include('../includes/header.php');
?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          
          <h3 class="font-weight-bolder mb-0 text-white">Card Issuance</h3>
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
                  <div class="card-body p-3 mb-4">
                    <div>
                        <h2>Send OTP (One Time Password)</h2>
                        <p>Be rest assure that your information are securely safe with us. Your information will never be divulge to anyone without your consent</p>
                    </div>
                  </div>
                  <div class="card-body p-3">
                    <form>
                        <h5>All fields in asterics * are important</h5>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>Agent Code:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="013WHTB" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>Bank Name:<span class="text-danger">*</span></label>
                                <select class="form-select" aria-label="Default select example" required>
                                    <option selected>Select Bank</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                  </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <input type="text" class="form-control"placeholder="Bank Name" id="exampleFormControlInput1"  required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <div class="form-check ml-2">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                      <a href="#">I willingly gave out my data</a>
                                    </label>
                                  </div>
                            </div>
                             
                          </div>
                        </div>
                        <button type="button" class="btn btn-primary"> Get OTP </button>
                      </form>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?> 
      
      