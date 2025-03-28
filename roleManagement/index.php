<?php
 $title = "Role Management | Agent Management System";
 $nav_header = "Roles Assignment";
  include('../includes/header.php');

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
                  <div class="card-body p-3">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter the role name" name="roleName" />
                                </div>
                          	</div>
                      	</div>
                       
                       <div class="container mt-4 mb-4">
                        <div>
                            <h5>User Management</h5>
                           </div>
                        <div class="row">
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" name="createAdmin" type="checkbox" value="1" id="createAdmin">
                            Create Admin
                          </div>
                          <div class="col-md-3 form-check">
                            <input class="form-check-input" type="checkbox" name="createAggregator" value="1" id="createAggregator">
                            Create Aggregator
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
                          <button type="button" class="btn btn-primary btn-sm"> Submit </button>
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
?> 