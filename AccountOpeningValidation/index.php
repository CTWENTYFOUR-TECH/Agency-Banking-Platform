<?php
  $title = "Account Opening | Agent Management System";
  $nav_header = "BVN Validation (BVN)";
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

      include('../Config/_validate_bvn.php');

?>
 <style>
        /* Custom styling for logos */
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 5px;
        }

        .dropdown-toggle::after {
          margin-left: auto; /* Pushes caret to the right */
        }
        .bank-logo {
          width: 20px;
          height: 20px;
          object-fit: contain;
        }
        .swal2-popup {
            border-radius: 0.5rem !important;
          }

          .swal2-confirm.btn {
            padding: 0.375rem 1.5rem;
          }

          .swal2-input, .swal2-select, .swal2-textarea {
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
          }
    </style>
   <div class="container-fluid py-4">

<div id="session-data"
    data-login-id="<?php echo $userSessionData['emailAddress']; ?>"
    data-api-key="<?php echo $userSessionData['secretKey']; ?>">
</div>
<div class="row">
    <div class="col-lg-12 mb-4 mb-lg-0">
        <div class="card z-index-2 h-100">
        <div class="card-header pb-0 pt-3 bg-transparent">  
        </div>
            <div class="card-body p-3">
                   <div>
                        <h2>OPEN A SAVINGS ACCOUNT (BVN Validation)</h2>
                        <p>Be rest assure that your information are securely safe with us. Your information will never be divulge to anyone without your consent.</p>
                    </div>
                    <form method="POST" autocomplete="off">
                  <div class="row g-3">
                    <!-- First Name Input -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="bvNumber" class="form-label">BVN: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="bvNumber" placeholder="Enter valid BVN" name="bvNumber" required oninput="limitInputLength(this)"/>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="phoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="phoneNumber" placeholder="Enter Phone number used for your BVN" name="phoneNumber" required oninput="limitInputLength(this)" />
                      </div>
                    </div>
                  </div>
                  <div class="row g-3">
                  <div class="col-md-4" id="pcCode">
                      <div class="form-group">
                        <label for="pcCode" class="form-label">PC Code <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="pcCode" placeholder="Enter your region PC Code" name="pcCode"/>
                      </div>
                    </div>
                    <div class="col-md-4" id="wemaConsent">
                      <div class="form-group">
                        <label for="emailAddress" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="emailAddress" placeholder="Email Address" name="emailAddress"/>
                      </div>
                    </div>
                    <!-- Bank Dropdown -->
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label">Select Bank</label>
                        <div class="dropdown">
                          <button class="form-control form-select text-start dropdown-toggle d-flex align-items-center" type="button" id="bankDropdown" data-bs-toggle="dropdown" style="height: 38px;">
                            <span id="selectedBank" class="text-truncate d-flex align-items-center">
                              <img id="selectedLogo" src="" class="bank-logo me-2" width="20" style="display:none;">
                              <span id="selectedBankText">Select a Bank</span>
                            </span>
                          </button>
                          <ul class="dropdown-menu w-100" aria-labelledby="bankDropdown">
                            <li><button type="button" class="dropdown-item" data-value="000013" data-logo="../assets/img/banks/gtbank.png">
                              <img src="../assets/img/banks/gtbank.png" class="bank-logo me-2" width="20"> GTBank
                            </button></li>
                            <li><button type="button" class="dropdown-item" data-value="000036" data-logo="../assets/img/banks/optimus.jpg">
                              <img src="../assets/img/banks/optimus.jpg" class="bank-logo me-2" width="20"> Optimus Bank
                            </button></li>
                            <li><button type="button" class="dropdown-item" data-value="000017" data-logo="../assets/img/banks/wema.png">
                              <img src="../assets/img/banks/wema.png" class="bank-logo me-2" width="20"> Wema Bank
                            </button></li>
                            <li><button type="button" class="dropdown-item" data-value="000003" data-logo="../assets/img/banks/fcmb.png">
                              <img src="../assets/img/banks/fcmb.png" class="bank-logo me-2" width="20"> FCMB Bank
                            </button></li>
                            <!-- More bank options... -->
                          </ul>
                        </div>
                        <input type="hidden" name="bank" id="bankInput" value="">
                        <input type="hidden" name="agentCode" id="agentCode" value="<?= $userSessionData['agentCode'] ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row g-3  mt-3">
                    <!-- Submit Button -->
                    <div class="col-md-4 d-flex align-items-end">
                      <button class="btn btn-primary w-100" type="submit" id="verifyButton" style="height: 38px;" name="validateBVN">Validate Now!</button>
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
<script>
$(document).ready(function() {
  // Cache DOM elements
  const $dropdownItems = $('.dropdown-item');
  const $selectedBankText = $('#selectedBankText');
  const $selectedLogo = $('#selectedLogo');
  const $bankInput = $('#bankInput');
  const $pcCode = $('#pcCode');
  const $wemaConsent = $('#wemaConsent');
  
  // Hide pcCode initially
  $pcCode.hide().removeAttr('required');
  $wemaConsent.hide().removeAttr('required');

  // Function to toggle pcCode visibility
  const togglePcCode = () => {
    const bankValue = $bankInput.val();
    
    if (bankValue === '000013') {
      $pcCode.show();
      $pcCode.prop('required', true);
    }else if(bankValue === '000017'){
      $wemaConsent.show();
      $wemaConsent.prop('required', true);
    } else {
      $pcCode.hide();
      $wemaConsent.hide();
      $pcCode.prop('required', false);
      $wemaConsent.prop('required', false);
    }
  };

  // Handle dropdown item clicks
  $dropdownItems.on('click', function(e) {
    e.preventDefault();
    const $this = $(this);
    const bankName = $this.text().trim();
    const bankValue = $this.data('value');
    const bankLogo = $this.data('logo');
    
    // Update the display
    $selectedBankText.text(bankName);
    $selectedLogo.attr('src', bankLogo).show();
    
    // Update the hidden input value
    $bankInput.val(bankValue);
    
    // Toggle pcCode visibility and requirement
    togglePcCode();
    
    // Close the dropdown
    const $dropdown = $this.closest('.dropdown');
    const $dropdownToggle = $dropdown.find('.dropdown-toggle');
    const dropdownInstance = bootstrap.Dropdown.getInstance($dropdownToggle[0]) || new bootstrap.Dropdown($dropdownToggle[0]);
    dropdownInstance.hide();
  });
});
</script>