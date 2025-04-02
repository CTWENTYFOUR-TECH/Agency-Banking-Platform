<?php
  $title = "Account Opening | Agent Management System";
  $nav_header = "Account Opening (BVN)";
  include('../includes/header.php');
  
  if (!checkPermissions(PERMISSION_ACCOUNT_OPENING)) {
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
                  <div class="card-body p-3">
                    <div>
                        <h2>OPEN A SAVINGS ACCOUNT</h2>
                        <p>Be rest assure that your information are securely safe with us. Your information will never be divulge to anyone without your consent.</p>
                    </div>
                    <form>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>Agent Code:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Agent Code" value="<?= $userSessionData['agentCode']; ?>" required readonly>
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
                                <label>First Name*:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="exampleFormControlInput1"  required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label>Middle Name:</label>
                              <input type="text" class="form-control" id="exampleFormControlInput1"  >
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Last name:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>gender:<span class="text-danger">*</span></label>
                                  <select class="form-select" aria-label="Default select example" required>
                                      <option selected>Male</option>
                                      <option value="1">Female</option>
                                    </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Date of Birth:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Street Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>City:<span class="text-danger">* Correct City Name Must Be Entered</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Local Government Code22:<span class="text-danger">*</span></label>
                                  <select class="form-select" aria-label="Default select example" required>
                                      <option selected>Select Local Government</option>
                                      <option value="1">one</option>
                                      <option value="1">one</option>
                                      <option value="1">one</option>
                                    </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Phone Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  required>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Bank Verification Number<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>NIN</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1"  >
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Upload Customer Signature<span class="text-danger">*</span></label>
                                  <input class="form-control" type="file" required >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label>Upload Customer Image<span class="text-danger">*</span></label>
                                  <input class="form-control" type="file" required>
                              </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                  <input type="text" class="form-control" id="exampleFormControlInput1">
                                </div>
                              </div>
                          </div>
                          <div>
                            <h4>NDPR DATA PROTECTION CONSENT</h4>
                            <p>I agree to submit my Biodata and other Personal Identifiable Information (PII) to the Agent as is required for Account Opening, Dispute Resolution etc on the SANEF Platform. I give permission to SANEF and the participating Financial Institutions to securely store and transmit this Personal Identifiable Information and Biometric data for this purpose. I agree that the SANE may also process my personal data through a data processor or provide or make such data available to such companies under the terms and conditions set out by the Nigeria Data Protection Regulation 2019 agree that the consent given is for an unlimited period until it is revoked.</p>
                            <h4>Disclaimer Clause</h4>
                            <p>The Financial Institutions and SANEF shall not be liable for breaches/disclosures that may occur if compelled by law or regulation to disclose customer data to third parties. However, the Financial Institutions and SANEF shall exercise due care to ensure that the customers biometrics data is secure and protected.</p>
                        </div>
                          <div class="row">
                            <div class="col-md-12">
                                <div class="form-check ml-2">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                    I have read the NDPR DATA PROTECTION CONSENT and I accept to proceed
                                  </div>
                            </div>
                          </div>
                        </div class="mt-4">
                        <button type="submit" class="btn btn-primary" id="submitAccount"> Submit </button>
                      </form>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>