<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Profile</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="tenant_info.css?v=1.10">
</head>
<body>

    <div class="wrapper">
        <div class="navigator">
            <a href="" id="guest-accounts-link" class="active">Guest Accounts</a>
            <a href="" id="active-tenant-link">Active Tenant</a>
        </div>

        <!-- Default Table: Guest Accounts -->
        <div class="guest-accounts">
            <div class="table-container">
                <table id="guest-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Book</th>
                            <th>Room No.</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Active Tenant Table -->
        <div class="active-tenant">
            <div class="table-container">
                <table id="active-tenant-table">
                    <thead>
                        <tr>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Move-In Date</th>
                            <th>Room No.</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Religion</th>
                            <th>Nationality</th>
                            <th>Occupation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
    $(document).ready(function() {
    // Initialize the guest accounts view
    fetchGuestAccounts();

    // Handle guest accounts link click
    $('#guest-accounts-link').click(function(e) {
        e.preventDefault();
        $('#guest-accounts-link').addClass('active');
        $('#active-tenant-link').removeClass('active');
        $('.guest-accounts').show();
        $('.active-tenant').hide();
        fetchGuestAccounts();
    });

    // Handle active tenants link click
    $('#active-tenant-link').click(function(e) {
        e.preventDefault();
        $('#active-tenant-link').addClass('active');
        $('#guest-accounts-link').removeClass('active');
        $('.active-tenant').show();
        $('.guest-accounts').hide();
        fetchActiveTenants();
    });
});

// Fetch Guest Accounts Data
function fetchGuestAccounts() {
    console.log("Fetching guest accounts...");  // Debug log
    $.ajax({
        url: 'guest-accounts.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let tableBody = $('#guest-table tbody');
            tableBody.empty();  // Clear any existing rows
            data.forEach(function(account) {
                console.log(account);  // Debug log to see what is being fetched
                let row = `
                    <tr>
                        <td>${account.fname} ${account.lname}</td>
                        <td>${account.booking_start_date}</td>
                        <td>${account.room_number}</td>
                        <td>${account.email_address}</td>
                        <td>${account.contact_number}</td>
                        <td>${account.status}</td>
                        <td>
                            <button class="approve-btn" data-id="${account.tc_id || 'N/A'}" data-room="${account.room_number}">Approve</button>
                            <button class="cancel-btn" data-id="${account.tc_id || 'N/A'}" data-room="${account.room_number}">Cancel</button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });


            // Approve Button Click Event
            $('.approve-btn').click(function(e) {
                e.preventDefault();
                const tenantId = $(this).data('id');
                const roomNumber = $(this).data('room');
                console.log('Approve button clicked:', tenantId, roomNumber);  // Debug log
                if (confirm('Please verify the deposit payment before approving. Do you want to approve?')) {
                    approveTenant(tenantId, roomNumber);
                }
            });

            $('.cancel-btn').click(function(e) {
                e.preventDefault();
                const tenantId = $(this).data('id');
                const roomNumber = $(this).data('room');
                console.log('Cancel button clicked:', tenantId, roomNumber);  // Debug log

                if (confirm('Are you sure you want to cancel this tenant? This will remove the tenant and free up the room.')) {
                    cancelTenant(tenantId, roomNumber);
                }
            });
        },
        error: function(xhr, status, error) {
            console.log('Failed to fetch guest accounts:', error);  // Debug log
        }
    });
}


function approveTenant(tenantId, roomNumber) {
    console.log('Sending approval request for tenant:', tenantId, roomNumber);  
    $.ajax({
        url: 'approve-tenant.php',
        method: 'POST',
        data: {
            tenant_id: tenantId,
            room_number: roomNumber
        },
        success: function(response) {
            console.log('Approval response:', response);  
            const result = JSON.parse(response);
            if (result.status === 'success') {
                alert(result.message);
                fetchGuestAccounts();
            } else {
                alert('Error: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Approval request failed:', error); 
        }
    });
}

function cancelTenant(tenantId, roomNumber) {
    console.log('Sending cancel request for tenant:', tenantId, roomNumber);  // Debug log

    $.ajax({
        url: 'cancel-tenant.php', // Ensure this points to the correct PHP file
        method: 'POST',
        data: {
            tenant_id: tenantId,
            room_number: roomNumber
        },
        success: function(response) {
            console.log('Cancel response:', response);  // Debug log
            const result = JSON.parse(response);
            if (result.status === 'success') {
                alert(result.message);
                fetchGuestAccounts(); // Refresh guest accounts table
            } else {
                alert('Error: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Cancel request failed:', error); // Debug log
            alert('There was an error processing the cancellation. Please try again.');
        }
    });
}


function fetchActiveTenants() {
    console.log("Fetching active tenants..."); 
    $.ajax({
        url: 'approved_accounts.php', 
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let tableBody = $('#active-tenant-table tbody');
            tableBody.empty();  
            
            if (data.length === 0) {
                tableBody.append('<tr><td colspan="9">No active tenants found.</td></tr>');
            } else {
                data.forEach(function(tenant) {
                    console.log(tenant);
                    console.log("Tenant Picture:", tenant.profile);

                    let picturePath = tenant.profile
                        ? `../../USER%20PAGE/Tenant_Profile/uploads/${tenant.profile}` 
                        : '../../USER PAGE/image/DP.png';
                    
                    console.log("Picture Path:", picturePath);  
                    
                    let row = `
                        <tr>
                            <td><img src="${picturePath}" alt="Tenant Picture" class="tenant-picture"></td>
                            <td>${tenant.fname} ${tenant.lname}</td>
                            <td>${tenant.booking_end_date}</td>
                            <td>${tenant.room_number}</td>
                            <td>${tenant.email_address}</td>
                            <td>${tenant.contact_number}</td>
                            <td>${tenant.religion}</td>
                            <td>${tenant.nationality}</td>
                            <td>${tenant.occupation}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('Failed to fetch active tenants:', error);  
        }
    });
}



    </script>
</body>
</html>
