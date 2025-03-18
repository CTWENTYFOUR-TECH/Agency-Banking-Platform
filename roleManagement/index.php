<?php
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
                                    <label>Select User<span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option selected>Select User</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Aggregator</option>
                                        <option value="3">Agent</option>
                                    </select>
                                </div>
                          </div>
                          <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Management<span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label="Default select example" required>
                                        <option selected>Select username</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                          </div>
                      </div>
                       
                       <div class="container  mt-4 mb-4">
                        <div>
                            <h4>user Management</h4>
                           </div>
                        <div class="row">
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1">
                            Create Admin
                          </div>
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="2" id="defaultCheck1">
                            Create Aggregator
                          </div>
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="3" id="defaultCheck1">
                            Create Agent
                          </div>
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="3" id="defaultCheck1">
                            Upgrade to Aggregator
                          </div>
                        </div>
                      </div>
                       
                       <div class="container  mt-4 mb-4">
                        <div class="">
                            <h5>Account Opening</h5>
                           </div>
                        <div class="row">
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1">
                            Saving Account (bvn)
                          </div>
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="2" id="defaultCheck1">
                            Savings Account(nin)
                          </div>
                          <div class="col form-check">
                            <input class="form-check-input" type="checkbox" value="3" id="defaultCheck1">
                            Card Issuance
                          </div>
                        </div>
                      </div>
                      <div class="container mt-4 mb-4">
                        <div >
                            <h5>Identity Check</h5>
                           </div>
                           <div class="row">
                            <div class="col form-check">
                              <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1">
                                BVN Check
                            </div>
                            <div class="col form-check">
                              <input class="form-check-input" type="checkbox" value="2" id="defaultCheck1">
                              NIN Check
                            </div>
                      </div>
                      
                        
                      </div class="mt-4">
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