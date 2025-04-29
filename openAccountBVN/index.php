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

      if(!$_SESSION['bvn']){
        echo "<script>
          window.location.href='../AccountOpeningValidation'
        </script>";  
        exit;
      }

      require_once __DIR__ . '/../vendor/autoload.php';
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
                    <form method ="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label>Salutation:<span class="text-danger">*</span></label>
                                    <select name="Salutation" id="Salutation" class="form-control">
                                      <option selected>...</option>
                                      <option value="Mr">Mr</option>
                                      <option value="Miss">Ms</option>
                                      <option value="Mrs">Mrs</option>
                                      <option value="Prof">Prof</option>
                                      <option value="Madam">Madam</option>
                                      <option value="Dr">Dr</option>
                                    </select>
                              </div>
                            </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label>Agent Code:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="AgentCode" name="AgentCode" placeholder="Agent Code" value="<?= $userSessionData['agentCode']; ?>" readonly required>
                            </div>
                            <input type="hidden" class="form-control" id="AggregatorCode" name="AggregatorCode" value="<?= $userSessionData['aggregatorCode']; ?>" readonly required>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label>Bank Name:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="BankCode" id="BankCode" placeholder="Bank" value="<?= $_SESSION['bank']; ?>" readonly required>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                <label>First Name:<span class="text-danger">*</span></label>
                              <input type="text" class="form-control" name="FirstName" id="FirstName" value="<?= $_SESSION['firstName'] ?>" readonly required/>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                                <label>Middle Name:</label>
                              <input type="text" class="form-control" name="MiddleName" id="MiddleName" value="<?= $_SESSION['middleName'] ?>" readonly />
                            </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label>Last name:<span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" name="LastName" id="LastName" value="<?= $_SESSION['lastName']?>" readonly required/>
                              </div>
                            </div>
                        </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>gender:<span class="text-danger">*</span></label>
                                  <select class="form-select" aria-label="Customers Gender" id="Gender" name="Gender" required/>
                                      <option>...</option>
                                      <option value ="M">Male</option>
                                      <option value="F">Female</option>
                                    </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>Date of Birth:<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="DateOfBirth" id="DateOfBirth" value="<?= $_SESSION['dateOfBirth'] ?>" readonly required />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>Street Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="StreetName" name="StreetName" required/>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State of Origin<span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label="State of Origin" id="StateOfOrigin" name="StateOfOrigin" required>
                                        <option value="<?= $_SESSION['stateOfOrigin']; ?>"><?= $_SESSION['stateOfOrigin']; ?></option>
                                      </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                              <div class="form-group">
                                  <label>Local Government:<span class="text-danger">*</span></label>
                                  <select class="form-select" aria-label="Local government" id="LocalGovtOrgin" name="LocalGovtOrgin" required>
                                      <option value="<?= $_SESSION['lgaOfOrigin'] ?>"> <?= $_SESSION['lgaOfOrigin'] ?></option>
                                    </select>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>City:<span class="text-danger">* Correct City Name Must Be Entered</span></label>
                                <input type="text" class="form-control" id="City"  name="City" required/>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Mother Maiden Name:<span class="text-danger"></span></label>
                                  <input type="text" class="form-control" id="MothersMaidenName" name="MothersMaidenName"/>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number<span class="text-danger">*</span></label>
                                  <input type="number" class="form-control" id="PhoneNumber" name="PhoneNumber" value="<?= $_SESSION['phoneNumber']; ?>"  readonly required>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bank Verification Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="BvnNumber" id="BvnNumber" value="<?= $_SESSION['bvn'] ?>" required readonly />
                                </div>
                              </div>
                            </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>NIN</label>
                                  <input type="number" class="form-control" name="NIN" id="NIN" />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>Upload Customer Signature<span class="text-danger">*</span></label>
                                  <input class="form-control" type="file" name="upload_sign" id="upload_sign" accept="image/*" required />
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                  <label>Upload Customer Image<span class="text-danger">*</span></label>
                                  <input class="form-control" type="file" name="upload_image" id="upload_image" accept="image/*" required />
                              </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-md-4" id="gtbank">
                                <div class="form-group">
                                    <label>PC Code<span class="text-danger"> - PCCode of your region *</span></label>
                                    <input class="form-control" type="number" name="PCCode" id="PCCode" />
                                </div>
                            </div>
                            <div class="col-md-4" id="gtbank"> 
                                <div class="form-group">
                                    <label>SMS Consent Code<span class="text-danger"> Enter the code you got via sms during bvn validation *</span></label>
                                  <input type="number" class="form-control" id="ConsentCode" name="ConsentCode" placeholder="Enter the code you got via sms during bvn validation"/>
                                </div>
                              </div>
                          </div>
                          <div class="col-md-4" id="gtbank"> 
                                <div class="form-group">
                                    <label>Agent Account<span class="text-danger"> Enter your agent account *</span></label>
                                  <input type="number" class="form-control" id="AgentAccount" name="AgentAccount"/>
                                </div>
                              </div>
                              <input type="number" class="form-control" id="ReferenceNumber" name="ReferenceNumber" value="<?= $_SESSION['ReferenceNumber']; ?>"/>
                          </div>
                          <div class="row">
                              <div class="col-md-4" id="fcmb_bank">
                                <div class="form-group">
                                    <label>Preferred Phone Number<span class="text-danger"></span></label>
                                    <input class="form-control" type="number" name="PreferredNumber" id="PreferredNumber" oninput="limitInputLength(this)"/>
                                </div>
                            </div>
                              <div class="col-md-4" id="fcmb_bank">
                                <div class="form-group">
                                    <label for ="MaritalStatus">Marital Status</label>
                                        <select class="form-select" aria-label="Marital Status" id="MaritalStatus" name="MaritalStatus">
                                            <option>...</option>
                                            <option value ="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widow">Divorced</option>
                                            <option value="Widower">Divorced</option>
                                          </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Address</label>
                                  <input type="email" class="form-control" id="EmailAddress" name="EmailAddress">
                                </div>
                              </div>
                          </div>
                          <!-- FCMB -->
                          <fieldset class="mt-4" id="fcmb_bank"> 
                            <legend> Next of Kin Details </legend>
                              <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nok_firstName">FirstName<span class="text-danger"></span></label>
                                        <input class="form-control" type="text" name="nok_firstName" id="nok_firstName" />
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nok_surname">Surname</label>
                                      <input type="text" class="form-control" id="nok_surname" name="nok_surname">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nok_middlename">Middle Name</label>
                                      <input type="text" class="form-control" id="nok_middlename" name="nok_middlename" />
                                    </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Gender:<span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Next of kin gender" id="nok_gender" name="nok_gender">
                                            <option>...</option>
                                            <option value ="M">Male</option>
                                            <option value="F">Female</option>
                                          </select>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nok_surname">Date of Birth</label>
                                      <input type="date" class="form-control" id="nok_dob" name="nok_dob">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label>N.O.K Relationship:<span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Relationship with the customer" id="nok_relationship" name="nok_relationship">
                                            <option>...</option>
                                            <option value ="Brother">Brother</option>
                                            <option value="Sister">Sister</option>
                                            <option value="Cousin">Cousin</option>
                                            <option value="Nephew">Nephew</option>
                                            <option value="Niece">Niece</option>
                                            <option value="Uncle">Uncle</option>
                                            <option value="Friend">Friend</option>
                                            <option value="Fiance">Fiance</option>
                                            <option value="Fiancee">Fiancee</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label>N.O.K Phone Number<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="nok_phone" name="nok_phone">
                                  </div>
                                </div>
                              </div>
                          </fieldset> 
                          <hr /> 
                            <h4>NDPR DATA PROTECTION CONSENT</h4>
                            <p>I agree to submit my Biodata and other Personal Identifiable Information (PII) to the Agent as is required for Account Opening, Dispute Resolution etc on the SANEF Platform. I give permission to SANEF and the participating Financial Institutions to securely store and transmit this Personal Identifiable Information and Biometric data for this purpose. I agree that the SANE may also process my personal data through a data processor or provide or make such data available to such companies under the terms and conditions set out by the Nigeria Data Protection Regulation 2019 agree that the consent given is for an unlimited period until it is revoked.</p>
                            <h4>Disclaimer Clause</h4>
                            <p>The Financial Institutions shall not be liable for breaches/disclosures that may occur if compelled by law or regulation to disclose customer data to third parties. However, the Financial Institutions and SANEF shall exercise due care to ensure that the customers biometrics data is secure and protected.</p>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="YES" id="defaultCheck1" name = "defaultCheck1">
                                    I have read the NDPR DATA PROTECTION CONSENT and I accept to proceed
                                </div>
                          </div>
                        <input type="submit" class="btn btn-primary" id="submitAccount" name="submitAccount" value="Submit">
                      </form>
                  </div>
                </div>
              </div>
              <?php include('../Config/_create_account_bvn.php');  ?>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>
<script>
      $(document).ready(function() {
        // Get the current BankCode value
        var bankCode = $('#BankCode').val();
        
        // Hide both bank-specific sections initially
        $('#fcmb_bank, #gtbank').hide();
        
        // Reset all fields to not required initially
        $('input#PCCode, input#ConsentCode, input#PreferredNumber, select#MaritalStatus, ' +
          'input#nok_firstName, input#nok_surname, input#nok_gender, ' +
          'input#nok_dob, input#nok_phone').prop('required', false);

        // Show the appropriate section based on BankCode
        if (bankCode === "000013") {
            $('[id^="gtbank"]').show();
            $('input#PCCode, input#ConsentCode').prop('required', true);
        } 
        else if (bankCode === "000003") { // Example for FCMB (replace with actual code)
            $('[id^="fcmb_bank"]').show();
            $('input#PreferredNumber, select#MaritalStatus, ' +
              'input#nok_firstName, input#nok_surname, input#nok_gender, ' +
              'input#nok_dob, input#nok_phone').prop('required', true);
        }
        // Add more banks as needed with else if
    });
  </script>