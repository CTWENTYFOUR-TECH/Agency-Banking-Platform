<?php
$title = "Forbidden | Agent Management System";
$nav_header = "Forbidden";
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
                  <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="p-4">
                                <h2><i class="ni ni-fat-remove mb-2" style="color: red; font-size: 32px"></i> 403 Forbidden</h2>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
              </div>
        </div>
    </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?> 