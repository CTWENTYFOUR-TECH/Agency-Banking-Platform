<?php
  $title = "Dashboard | Agent Management System";
  $nav_header = "Dashboard";
  include('../includes/header.php');
  // include('../Config/_agent_aggregator_report.php');
  // Check if the user account status has been updated
  if($userSessionData['accountStatus'] == 0 ){
    echo "<script>
          window.location.href='../Settings'
        </script>";  
    }
?>
    <div class="container-fluid py-4">
  <?php
    // if($group_id == "AGENT"){
    //     $accountOpening = $accountOpened ['TotalAccountOpened'];
    //     $cardIssuance = $cardIssuance['TotalCardIssuace'];
    //     // For current time/date action
    //     $accountToday = $accountToday['AccountOpenedToday'];
    //     $cardToday = $accountToday['CardIssuedToday'];
    //     echo "<style> #hideForAgent { display: none }</style>";
    // }else if($group_id != "AGGREGATOR"){
    //   $accountOpening = $accountOpened ['TotalAccountOpened'];
    //   $cardIssuance = $cardIssuance['TotalCardIssuace'];
    //   $agentAcquired = $agentAcquired['TotalAgentAcquired'];
    //   // For current time/date action
    //   $accountToday = $accountToday['AccountOpenedToday'];
    //   $cardToday = $accountToday['CardIssuedToday'];
    //   $agentOnboardToday = $agentAcquiredToday['AgentAcquiredToday'];
    // }else{
    //   $accountOpening = $accountOpened ['TotalAccountOpened'];
    //   $cardIssuance = $cardIssuance['TotalCardIssuace'];
    //   $agentAcquired = $agentAcquired['TotalAgentAcquired'];
    //   $agentAcquired = $aggregatorAcquired['TotalAggregatorAcquired'];
    //   // For current time/date action
    //   $accountToday = $accountToday['AccountOpenedToday'];
    //   $cardToday = $accountToday['CardIssuedToday'];
    //   $agentOnboardToday = $agentAcquiredToday['AgentAcquiredToday'];
    //   $aggregatorToday = $aggregatorAcquiredToday['AggregatorAcquiredToday'];
    // }
  ?>

<div id="session-data" 
     data-login-id="<?php echo $userSessionData['emailAddress']; ?>"
     data-group-id="<?php echo $group_id ?? 'AGENT'; ?>"
     data-agent-code="<?php echo $userSessionData['agentCode']; ?>">
</div>

      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
              <div class="card  mb-4">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Account Opened</p>
                        <h5 class="font-weight-bolder" id="">
                          0
                        </h5>
                        <button id="refresh-agent-count" class="btn btn-sm btn-light">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <p class="mb-0">
                          <span class="text-success text-sm font-weight-bolder">0</span>
                          opened today
                        </p>
                      </div>
                    </div>
                    <div class="col-4 text-end">
                      <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                        <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
              <div class="card  mb-4">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Card Issued</p>
                        <h5 class="font-weight-bolder" id="">
                          0
                        </h5>
                        <button id="refresh-agent-count" class="btn btn-sm btn-light">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <p class="mb-0">
                          <span class="text-success text-sm font-weight-bolder">0</span>
                          opened today
                        </p>
                      </div>
                    </div>
                    <div class="col-4 text-end">
                      <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                        <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
              <div class="card  mb-4">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Agents</p>
                        <h5 class="font-weight-bolder" id="total-agent-count">
                          0
                        </h5>
                        <button id="refresh-agent-count" class="btn btn-sm btn-light">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <p class="mb-0">
                          <span class="text-danger text-sm font-weight-bolder" id="total_daily_agent">0</span>
                          acquired today
                        </p>
                      </div>
                    </div>
                    <div class="col-4 text-end">
                      <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                        <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12" id="hideForAgent">
              <div class="card  mb-4">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Aggregators</p>
                        <h5 class="font-weight-bolder"  id="total-aggregator-count">
                          0
                        </h5>
                        <button id="refresh-agent-count" class="btn btn-sm btn-light">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <p class="mb-0">
                          <span class="text-success text-sm font-weight-bolder" id="total-daily-aggregator">0</span> acquired today
                        </p>
                      </div>
                    </div>
                    <div class="col-4 text-end">
                      <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                        <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-7 mb-4 mb-lg-0">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Account Opening Overview</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">4% more</span> in 2021
              </p>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card card-carousel overflow-hidden h-100 p-0">
            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
              <div class="carousel-inner border-radius-lg h-100">
                <div class="carousel-item h-100 active" style="background-image: url('../assets/img/img-2.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-camera-compact text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Get started with Argon</h5>
                    <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('../assets/img/img-1.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Faster way to create web pages</h5>
                    <p>That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('../assets/img/img-3.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-trophy text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Share with us your design tips!</h5>
                    <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12 col-md-12 mb-4 mb-md-0">
          <div class="card">
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Author</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Function</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Technology</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employed</th>
                    <th class="text-secondary opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs">John Michael</h6>
                          <p class="text-xs text-secondary mb-0">john@creative-tim.com</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">Manager</p>
                      <p class="text-xs text-secondary mb-0">Organization</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm badge-success">Online</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                    </td>
                    <td class="align-middle">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-3.jpg" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs">Alexa Liras</h6>
                          <p class="text-xs text-secondary mb-0">alexa@creative-tim.com</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">Programator</p>
                      <p class="text-xs text-secondary mb-0">Developer</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm badge-secondary">Offline</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">11/01/19</span>
                    </td>
                    <td class="align-middle">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-4.jpg" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs">Laurent Perrier</h6>
                          <p class="text-xs text-secondary mb-0">laurent@creative-tim.com</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">Executive</p>
                      <p class="text-xs text-secondary mb-0">Projects</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm badge-success">Online</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">19/09/17</span>
                    </td>
                    <td class="align-middle">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-3.jpg" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs">Michael Levi</h6>
                          <p class="text-xs text-secondary mb-0">michael@creative-tim.com</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">Programator</p>
                      <p class="text-xs text-secondary mb-0">Developer</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm badge-success">Online</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">24/12/08</span>
                    </td>
                    <td class="align-middle">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-xs">Richard Gran</h6>
                          <p class="text-xs text-secondary mb-0">richard@creative-tim.com</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">Manager</p>
                      <p class="text-xs text-secondary mb-0">Executive</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm badge-secondary">Offline</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">04/10/21</span>
                    </td>
                    <td class="align-middle">
                      <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
<?php
  include('../includes/footer.php');
  echo $footer;
?>

<script>
// $(document).ready(function() {
//     // Configuration
//     const config = {
//         refreshInterval: 300000, // 5 minutes
//         countElementId: 'total-agent-count',
//         countDailyElementId: 'total_daily_agent',
//         loadingElementId: 'agent-count-loading',
//         errorElementId: 'agent-count-error',
//         refreshButtonId: 'refresh-agent-count'
//     };

//     // Cache DOM elements
//     const elements = {
//         countDisplay: $('#' + config.countElementId),
//         countDailyDisplay: $('#' + config.countDailyElementId),
//         loadingIndicator: $('#' + config.loadingElementId),
//         errorContainer: $('#' + config.errorElementId),
//         refreshButton: $('#' + config.refreshButtonId)
//     };

//     // Format number with commas
//     function formatNumber(num) {
//         return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//     }

//     // Show loading state
//     function showLoading() {
//         elements.loadingIndicator.show();
//         elements.errorContainer.hide();
//     }

//     // Hide loading state
//     function hideLoading() {
//         elements.loadingIndicator.hide();
//     }

//     // Show error message
//     function showError(message) {
//         elements.errorContainer.html(`
//             <div class="alert alert-danger alert-dismissible fade show" role="alert">
//                 ${message}
//                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//             </div>
//         `).show();
//     }

//     // Update the count display
//     function updateCountDisplay(count) {
//         elements.countDisplay.text(formatNumber(count));
//         $('#last-updated-time').text(new Date().toLocaleTimeString());
//     }

//     function updateCountDailyDisplay(count) {
//         elements.countDailyDisplay.text(formatNumber(count));
//         $('#last-updated-Daily-time').text(new Date().toLocaleTimeString());
//     }

//     // Fetch agent count from server
//     function fetchAgentCount() {
//         showLoading();
        
//         $.ajax({
//             url: '../Config/_agent_count.php',
//             type: 'POST',
//             dataType: 'json',
//             contentType: 'application/json',
//             success: function(response, status, xhr) {
//                 hideLoading();
                
//                 // Check if response is actually JSON
//                 const contentType = xhr.getResponseHeader('Content-Type');
//                 if (contentType && contentType.includes('application/json')) {
//                     if (response.status === 'success') {
//                         updateCountDisplay(response.total_agent);
//                         updateCountDailyDisplay(response.total_daily_agent);
//                     } else {
//                         showError(response.message || 'Failed to load agent count');
//                     }
//                 } else {
//                     // Handle HTML response (likely an error)
//                     showError('Server returned invalid response. Please check console for details.');
//                     console.error('Non-JSON response:', response);
//                 }
//             },
//             error: function(xhr, status, error) {
//                 hideLoading();
                
//                 // Handle JSON parse error specifically
//                 if (status === 'parsererror') {
//                     showError('Invalid server response format. The server might be experiencing issues.');
//                     console.error('JSON parse error. Response was:', xhr.responseText);
//                 } else {
//                     showError('Network error: ' + error);
//                     console.error('Request failed:', status, error);
//                 }
//             }
//         });
//     }

//     // Initialize
//     fetchAgentCount();
    
//     // Set up auto-refresh
//     setInterval(fetchAgentCount, config.refreshInterval);
    
//     // Manual refresh button
//     elements.refreshButton.click(function(e) {
//         e.preventDefault();
//         fetchAgentCount();
//     });
// });

$(document).ready(function() {
    // Configuration with all required elements
    const config = {
        refreshInterval: 300000, // 5 minutes
        elements: {
            // Existing count elements
            totalAgent: '#total-agent-count',
            dailyAgent: '#total-daily-agent',
            totalAggregator: '#total-aggregator-count',
            dailyAggregator: '#total-daily-aggregator',
            
            // New elements for card and account metrics
            totalCardIssuedAgent: '#total-card-issued-agent',
            dailyCardIssuedAgent: '#daily-card-issued-agent',
            totalAccountOpenedAggregator: '#total-account-opened-aggregator',
            dailyAccountOpenedAggregator: '#daily-account-opened-aggregator',
            
            // UI elements
            loading: '#agent-count-loading',
            error: '#agent-count-error',
            refreshBtn: '#refresh-agent-count',
            lastUpdated: '#last-updated-time'
        }
    };

    // Cache DOM elements
    const elements = {
        // Existing count displays
        totalAgent: $(config.elements.totalAgent),
        dailyAgent: $(config.elements.dailyAgent),
        totalAggregator: $(config.elements.totalAggregator),
        dailyAggregator: $(config.elements.dailyAggregator),
        
        // New metric displays
        totalCardIssuedAgent: $(config.elements.totalCardIssuedAgent),
        dailyCardIssuedAgent: $(config.elements.dailyCardIssuedAgent),
        totalAccountOpenedAggregator: $(config.elements.totalAccountOpenedAggregator),
        dailyAccountOpenedAggregator: $(config.elements.dailyAccountOpenedAggregator),
        
        // UI elements
        loading: $(config.elements.loading),
        error: $(config.elements.error),
        refreshBtn: $(config.elements.refreshBtn),
        lastUpdated: $(config.elements.lastUpdated)
    };

    // Format number with commas
    function formatNumber(num) {
        return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : "0";
    }

    // UI State Management
    const ui = {
        showLoading: () => {
            elements.loading.show();
            elements.error.hide();
        },
        hideLoading: () => elements.loading.hide(),
        showError: (message) => {
            elements.error.html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `).show();
        },
        updateDisplay: (data) => {
            // Update existing counts
            elements.totalAgent.text(formatNumber(data.total_agent));
            elements.dailyAgent.text(formatNumber(data.total_daily_agent));
            elements.totalAggregator.text(formatNumber(data.total_aggregator));
            elements.dailyAggregator.text(formatNumber(data.total_daily_aggregator));
            
            // Update new metrics
            elements.totalCardIssuedAgent.text(formatNumber(data.total_card_issued_agent));
            elements.dailyCardIssuedAgent.text(formatNumber(data.daily_card_issued_agent));
            elements.totalAccountOpenedAggregator.text(formatNumber(data.total_account_opened_aggregator));
            elements.dailyAccountOpenedAggregator.text(formatNumber(data.daily_account_opened_aggregator));
            
            elements.lastUpdated.text(new Date().toLocaleString());
            
            // Debug log
            console.log('Updated dashboard metrics:', data);
        }
    };

    // API Service
    const agentService = {
        fetchCounts: () => {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '../Config/_agent_count.php',
                    type: 'GET',
                    dataType: 'json',
                    success: (response) => {
                        if (response.status === 'success') {
                            resolve(response);
                        } else {
                            reject(new Error(response.message || 'Invalid response from server'));
                        }
                    },
                    error: (xhr, status, error) => {
                        let errorMsg = 'Request failed';
                        
                        if (status === 'parsererror') {
                            errorMsg = 'Invalid server response format';
                            console.error('JSON parse error:', xhr.responseText);
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else {
                            errorMsg = `Network error: ${error}`;
                        }
                        
                        reject(new Error(errorMsg));
                    }
                });
            });
        }
    };

    // Main function to load and display counts
    async function loadAgentCounts() {
        try {
            ui.showLoading();
            const response = await agentService.fetchCounts();
            
            // Ensure all expected fields exist in response
            const completeData = {
                total_agent: response.total_agent || 0,
                total_daily_agent: response.total_daily_agent || 0,
                total_aggregator: response.total_aggregator || 0,
                total_daily_aggregator: response.total_daily_aggregator || 0,
                total_card_issued_agent: response.total_card_issued_agent || 0,
                daily_card_issued_agent: response.daily_card_issued_agent || 0,
                total_account_opened_aggregator: response.total_account_opened_aggregator || 0,
                daily_account_opened_aggregator: response.daily_account_opened_aggregator || 0
            };
            
            ui.updateDisplay(completeData);
        } catch (error) {
            ui.showError(error.message);
            console.error('Failed to load agent counts:', error);
        } finally {
            ui.hideLoading();
        }
    }

    // Initialize
    loadAgentCounts();
    
    // Set up auto-refresh
    const refreshInterval = setInterval(loadAgentCounts, config.refreshInterval);
    
    // Manual refresh button
    elements.refreshBtn.on('click', (e) => {
        e.preventDefault();
        loadAgentCounts();
    });

    // Clean up on page unload
    $(window).on('unload', () => {
        clearInterval(refreshInterval);
    });
});
  </script>
