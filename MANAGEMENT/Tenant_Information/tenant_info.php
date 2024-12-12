<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Profile</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="tenant_info.css">
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

            // Cancel Button Click Event
            $('.cancel-btn').click(function(e) {
                e.preventDefault();
                const tenantId = $(this).data('id');
                const roomNumber = $(this).data('room');
                console.log('Cancel button clicked:', tenantId, roomNumber);  // Debug log
                cancelTenant(tenantId, roomNumber);
            });
        },
        error: function(xhr, status, error) {
            console.log('Failed to fetch guest accounts:', error);  // Debug log
        }
    });
}

// Approve Tenant Function
function approveTenant(tenantId, roomNumber) {
    console.log('Sending approval request for tenant:', tenantId, roomNumber);  // Debug log
    $.ajax({
        url: 'approve-tenant.php',
        method: 'POST',
        data: {
            tenant_id: tenantId,
            room_number: roomNumber
        },
        success: function(response) {
            console.log('Approval response:', response);  // Debug log
            const result = JSON.parse(response);
            if (result.status === 'success') {
                alert(result.message);
                fetchGuestAccounts();  // Refresh guest accounts table
            } else {
                alert('Error: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Approval request failed:', error);  // Debug log
        }
    });
}

// Cancel Tenant Function
function cancelTenant(tenantId, roomNumber) {
    console.log('Sending cancel request for tenant:', tenantId, roomNumber);  // Debug log
    $.ajax({
        url: 'cancel-tenant.php',
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
                fetchGuestAccounts();  // Refresh guest accounts table
            } else {
                alert('Error: ' + result.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Cancel request failed:', error);  // Debug log
        }
    });
}

    </script>
</body>
</html>
