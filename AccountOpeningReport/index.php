<?php
$title = "Report Management | Agent Management System";
$nav_header = "Account Opening Report";
include('../includes/header.php');

if (!checkPermissions(PERMISSION_ACCOUNTOPENING_REPORT)) {
//   header('Location: ../Forbidden/?action=403');
//       exit;
    echo "<script> window.location.href ='../Forbidden/?action=403'</script>";
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
<style>
#loading-indicator {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
}

.page-item.disabled .page-link {
    pointer-events: none;
    opacity: 0.6;
}

.page-item.active .page-link {
    font-weight: bold;
}

.modalHeader {
    background-color: #596CFF;
}
.modal-title{
    color: #ffffff ;
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
                        
                    <div id="export-loading" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; color:white;">
                            <div class="spinner-border" style="width:3rem; height:3rem;"></div>
                            <h4 class="mt-3">Preparing your export...</h4>
                            <p>This may take a while for large datasets</p>
                        </div>
                    </div>
                    <!-- Spinner for large download -->

                    <div class="table-responsive">
                            <table id="account-table" class="table table-striped" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ReferenceNo</th>
                                        <th>AgentCode</th>
                                        <th>AggregatorCode</th>
                                        <th>FirstName</th>
                                        <th>LastName</th>
                                        <th>Account No</th>
                                        <th>Account Name</th>
                                        <th>Bank Name</th>
                                        <th>CreatedDate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th>ReferenceNo</th>
                                    <th>AgentCode</th>
                                    <th>AggregatorCode</th>
                                    <th>FirstName</th>
                                    <th>LastName</th>
                                    <th>Account No</th>
                                    <th>Account Name</th>
                                    <th>Bank Name</th>
                                    <th>CreatedDate</th>
                                    <th>Action</th>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Structure -->
        <div class="modal fade" id="agentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modalHeader">
                        <h5 class="modal-title">Account Opening Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Content will be loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class= "fa fa-close"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Structure -->
    </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?> 
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#account-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '../Config/_get_account_opened.php',
            type: 'POST',
            contentType: 'application/json',
            data: function(d) {
                return JSON.stringify(d);
            },
            error: function(xhr, error, thrown) {
                let errorMsg = "Data loading failed";
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                
                $('#error-display').html(`
                    <div class="alert alert-danger">
                        ${errorMsg}
                        ${xhr.status === 429 ? ' Please try again later.' : ''}
                    </div>
                `).show();
            }
        },
        columns: [
            { data: 'ReferenceNo' },
            { data: 'AgentCode' },
            { data: 'Aggregator' },
            { data: 'FirstName' },
            { data: 'LastName' },
            { data: 'Account No' },
            { data: 'Account Name' },
            { data: 'Bank Name' },
            { 
                data: 'CreatedDate',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: 'AgentCode',
                orderable: false,
                render: function(agentCode, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary view-agent" 
                                data-agent-code="${agentCode}">
                            <i class="fas fa-eye"></i> View
                        </button>
                    `;
                }
            }
        ],
        paging: true,
        pageLength: 10,
        lengthMenu: [10, 100, 500, 1000],
        searching: true,
        ordering: true,
        info: true,
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV (All Records)',
                className: 'btn btn-primary',
                action: function(e, dt, node, config) {
                    exportFullData('csv');
                }
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel (All Records)',
                className: 'btn btn-success',
                action: function(e, dt, node, config) {
                    exportFullData('excel');
                }
            }
            // {
            //     extend: 'pdf',
            //     text: '<i class="fas fa-file-pdf"></i> PDF (All Records)',
            //     className: 'btn btn-danger',
            //     action: function(e, dt, node, config) {
            //         exportFullData('pdf');
            //     }
            // },
            // {
            //     extend: 'print',
            //     text: '<i class="fas fa-print"></i> Print (All Records)',
            //     className: 'btn btn-warning',
            //     action: function(e, dt, node, config) {
            //         exportFullData('print');
            //     }
            // }
        ]
    });

    // View Agent Modal Handler - Using API endpoint
    $(document).on('click', '.view-agent', function() {
        const agentCode = $(this).data('agent-code');
        const loginId = $('#session-data').data('login-id'); // From hidden field
        
        // Show loading state
        $('#agentModal .modal-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p>Loading agent details...</p>
            </div>
        `);
        $('#agentModal').modal('show');
        
        // Fetch agent details from API
        $.ajax({
            url: `https://lukeportservice.cpay.ng/lukeportservice/api/getAccountOpened/${loginId}/${agentCode}`,
            type: 'GET',
            headers: {
                'Authorization': 'Bearer ' + $('#session-data').data('api-key')
            },
            success: function(response) {
                if (response.ResponseCode === '00') {
                    populateModal(response.AgentData);
                } else {
                    showError(response.ResponseMessage || 'Failed to load agent details');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.ResponseMessage || 
                               'Server error: ' + xhr.statusText;
                showError(errorMsg);
            }
        });
    });
    
    // Populate modal with agent data
    function populateModal(account) {
        $('#agentModal .modal-title').text(`Reference No: ${account.ReferenceNo}`);
        $('#agentModal .modal-body').html(`
            <div class="row">
                <div class="col-md-6">
                    <h5 style="color: #596CFF">Personal Information</h5>
                    <p><strong>Agent Code:</strong> ${escapeHtml(account.AgentCode)}
                    <p><strong>Name:</strong> ${escapeHtml(account.FirstName)} ${escapeHtml(account.MiddleName || '')} ${escapeHtml(account.LastName)}</p>
                    <p><strong>Gender:</strong> ${escapeHtml(account.Gender)}</p>
                    <p><strong>Date of Birth:</strong> ${account.DateOfBirth || 'N/A'}</p>
                    <p><strong>BVN:</strong> ${account.BVN || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <h5 style="color: #596CFF">Contact Information</h5>
                    <p><strong>Email:</strong> ${escapeHtml(account.EmailAddress)}</p>
                    <p><strong>Phone:</strong> ${escapeHtml(account.PhoneNumber)}</p>
                    <p><strong>State:</strong> ${escapeHtml(account.State)}</p>
                    <p><strong>City:</strong> ${escapeHtml(account.City)}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <h5 style="color: #596CFF">Account Information</h5>
                    <p><strong>Account No:</strong> ${account.AccountNumber || 'N/A'}</p>
                    <p><strong>Bank Name:</strong> ${account.BankName || 'N/A'}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <p><strong>Registered On:</strong> ${new Date(account.CreatedDate).toLocaleString()}</p>
                    <p><strong>Role:</strong> ${account.Roles}</p>
                    <p><strong>Aggregator Code:</strong> ${account.AggregatorCode || 'N/A'}</p>
                </div>
            </div>
        `);
    }

    // Show error in modal
    function showError(message) {
        $('#agentModal .modal-body').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                ${escapeHtml(message)}
            </div>
        `);
    }

    // HTML escaping helper
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

     // Export all records function
     function exportFullData(format) {
        // Show loading indicator
        const loading = $('#export-loading');
        loading.show();
        
        // Get session data
        const sessionData = {
            login_id: $('#session-data').data('login-id'),
            group_id: $('#session-data').data('group-id'),
            agent_code: $('#session-data').data('agent-code')
        };
        
        // Fetch all records
        $.ajax({
            url: 'https://lukeportservice.cpay.ng/lukeportservice/api/exportAccount',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + $('#session-data').data('api-key'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(sessionData),
            success: function(response) {
                if (response.success) {
                    // Create temporary table for export
                    const tempDiv = $('<div style="position:absolute; left:-10000px; top:-10000px;">');
                    const tempTable = $('<table id="temp-export-table">');
                    
                    tempDiv.append(tempTable);
                    $('body').append(tempDiv);
                    
                    // Initialize DataTable with all data
                    tempTable.DataTable({
                        data: response.data,
                        columns: response.columns,
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: format,
                                title: 'All Account Opened',
                                exportOptions: {
                                    columns: ':visible',
                                    modifier: {
                                        search: 'none',
                                        order: 'original'
                                    }
                                },
                                customize: function(doc) {
                                    // PDF customization
                                    if (format === 'pdf') {
                                        doc.defaultStyle.fontSize = 8;
                                        doc.pageMargins = [20, 40, 20, 40];
                                    }
                                }
                            }
                        ],
                        initComplete: function() {
                            // Trigger download
                            this.api().buttons('.buttons-' + format).trigger();
                            
                            // Clean up
                            setTimeout(() => {
                                tempDiv.remove();
                                loading.hide();
                            }, 1000);
                        }
                    });
                } else {
                    throw new Error(response.message || 'Export failed');
                }
            },
            error: function(xhr) {
                loading.hide();
                alert('Export error: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    }
});
</script>
