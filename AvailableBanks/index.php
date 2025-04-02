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
                    <table>
                            <tr>
                                <td><img src="./../assets/img/banks/gtbank.png" alt="gtbank" width="150" style="margin: 30px;"></td>
                                <td><img src="./../assets/img/banks/fcmb.png" alt="fcmb" width="150" style="margin: 30px;"></td>
                            </tr>
                            <tr>
                                <td><img src="./../assets/img/banks/optimus.jpg" alt="optimus" width="150" style="margin: 30px;"></td>
                                <td><img src="./../assets/img/banks/wema.png" alt="wema" width="150" style="margin: 30px;"></td>
                            </tr>
                        </table>
                  </div>
                </div>
              </div>
        </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>