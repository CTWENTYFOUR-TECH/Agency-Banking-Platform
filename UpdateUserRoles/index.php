<?php
$title = "Edit Role Management | Agent Management System";
$nav_header = "Roles Assignment";
include('../includes/header.php');

if (!checkPermissions(PERMISSION_UPDATE_ROLE)) {
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
                    <form method="POST" autocomplete="off" novalidate id="rolesForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role Name <span class="text-danger">*</span></label>
                                    <!-- <input type="text" class="form-control" placeholder="Enter the role name" name="roleName" id="roleName"/> -->
                                     <select name="roleName" id="roleName" class="form-control" required>
                                     </select>
                                </div>
                          	</div>
                      	</div>
                         <!-- REQUIRED hidden fields -->
                         <input type="hidden" name="GroupID" id="GroupID" value="">
                         <input type="hidden" name="GroupName" id="GroupName" value="">
                         <input type="hidden" id="loginID" name="loginID" value="<?= $userSessionData['emailAddress'] ?>" />
                       
                        <div class="container mt-4 mb-4"  id="roleAssignmentsContainer">

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
    // Initialize the interface
    initRoleManagement();

    function initRoleManagement() {
        loadGroupsDropdown();
        setupEventHandlers();
    }

    function setupEventHandlers() {
        // Group selection change
        $('#roleName').change(function() {
            const groupId = $(this).val();
            const groupName = $(this).find('option:selected').text();
            groupId ? loadRoleAssignments(groupId, groupName) : clearRoleDisplay();
        });

        // Save button click
        $(document).on('click', '#saveRolesBtn', updateRoles);
    }

    function loadGroupsDropdown() {
        $('#roleName').html('<option value="">Select group...</option>');
        
        $.ajax({
            url: '../Config/_getAllGroup.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' && response.options) {
                    $('#roleName').append(response.options);
                }
            },
            error: handleAjaxError('Failed to load groups')
        });
    }

    function loadRoleAssignments(groupId, groupName) {
        showLoadingState();
        
        $.ajax({
            url: '../Config/_get_user_roles.php',
            type: 'POST',
            data: {
                roleName: groupName,
                groupID: groupId,
                loginID: $('#loginID').val()
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    displayRoleCheckboxes(
                        response.roles, 
                        response.groupName,
                        response.groupId,
                        response.roleId
                    );
                } else {
                    toastr.error(response.message || 'Failed to load roles');
                }
            },
            error: handleAjaxError('Error loading roles')
        });
    }

    function displayRoleCheckboxes(roles, groupName, groupId, roleId) {
    console.log("Roles data received:", roles);

    // Set static hidden fields outside dynamic content
    $('#GroupID').val(groupId);
    $('#GroupName').val(groupName);

    let html = `
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0" style="color: #fff">${groupName} Permissions</h5>
            </div>
            <div class="card-body">
                <div class="row">
    `;

    const roleCategories = {
        'User Management': ['CreateUser', 'ViewUser', 'DeleteUser', 'EditUser', 'UnlockUser'],
        'Role Management': ['CreateRole', 'ViewGroup', 'UpdateUserRole'],
        'Agent Management': ['CreateSubAgent', 'UpgradeToAggregator'],
        'Account Operations': ['AccountOpening', 'CardIssuance'],
        'Reports': ['AccountOpeningReport', 'CardIssuanceReport', 'AgentOnboardedReport', 'AggregatorReport'],
        'Status Controls': ['DeactivateUser', 'ReactivateUser']
    };

    Object.entries(roleCategories).forEach(([category, roleList]) => {
        html += `<div class="col-md-6 mb-4"><h6>${category}</h6>`;

        roleList.forEach(role => {
            if (typeof roles[role] !== 'undefined') {
                const isChecked = roles[role] == 1;
                html += `
                <div class="form-check mb-2">
                    <input class="form-check-input role-checkbox" 
                           type="checkbox"
                           name="${role}"
                           id="${role}_${groupId}"
                           value="1"
                           ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="${role}_${groupId}">
                        ${formatRoleName(role)}
                    </label>
                </div>`;
            }
        });

        html += `</div>`;
    });

    html += `</div>
            <div class="mt-4 text-end">
                <button type="button" id="saveRolesBtn" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>`;

    $('#roleAssignmentsContainer').html(html);
}

    function updateRoles() {
        const formData = {
            // GroupID: $('#rolesForm input[name="GroupID"]').val(),
            // GroupName: $('#rolesForm input[name="GroupName"]').val(),
            // LoginID: $('#rolesForm input[name="loginID"]').val()
            GroupID: $('#GroupID').val(),
            GroupName: $('#GroupName').val(),
            LoginID: $('#loginID').val()
        };

     // 4. Add all role values (1 for checked, 0 for unchecked)
    $('#rolesForm').find('.role-checkbox').each(function() {
        const roleName = $(this).attr('name');
        // formData[roleName] = $(this).is(':checked') ? 1 : 0;
        formData[roleName] = $(this).is(':checked') ? 1 : undefined;
    });

    console.log('Submitting:', formData); // Debug output

    $.ajax({
        url: '../Config/_update_user_roles.php',
        type: 'POST',
        contentType: 'application/json', // Important for JSON payload
        data: JSON.stringify(formData), // Convert to JSON string
        dataType: 'json',
        beforeSend: function() {
            $('#saveRolesBtn').prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
        },
        success: function(response) {
            if (response.status === 'success') {
                toastr.success('Roles updated successfully');
                // Optional: Refresh the displayed roles
                loadRoleAssignments(formData.GroupID, formData.GroupName);
                console.loadRolesAssignments(formData.GroupID, formData.GroupName)
            } else {
                toastr.error(response.message || 'Update failed');
            }
        },
        error: function(xhr) {
            console.error('Error:', xhr.responseText);
            toastr.error('Failed to save. Check console for details.');
        },
        complete: function() {
            $('#saveRolesBtn').prop('disabled', false).html('Save Changes');
        }
    });
}

    // Helper functions
    function formatRoleName(role) {
        return role.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase());
    }

    function showLoadingState() {
        $('#roleAssignmentsContainer').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading permissions...</p>
            </div>
        `);
    }

    function clearRoleDisplay() {
        $('#roleAssignmentsContainer').html('');
    }

    function handleAjaxError(defaultMessage) {
        return function(xhr) {
            console.error("AJAX Error:", xhr.responseText);
            const errorMsg = xhr.responseJSON?.message || defaultMessage;
            toastr.error(errorMsg);
        };
    }
});
</script>