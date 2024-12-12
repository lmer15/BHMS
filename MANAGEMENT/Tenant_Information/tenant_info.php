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
            $('.active-tenant').hide();
            fetchGuestAccounts();
            $('#guest-accounts-link').click(function(e) {
                e.preventDefault();  
                $('#guest-accounts-link').addClass('active');
                $('#active-tenant-link').removeClass('active');
                $('.guest-accounts').show();
                $('.active-tenant').hide();
                fetchGuestAccounts();
            });

            $('#active-tenant-link').click(function(e) {
                e.preventDefault(); 
                $('#active-tenant-link').addClass('active');
                $('#guest-accounts-link').removeClass('active');
                $('.active-tenant').show();
                $('.guest-accounts').hide();
                fetchActiveTenants();
            });
        });

        function fetchGuestAccounts() {
            $.ajax({
                url: 'guest-accounts.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    let tableBody = $('#guest-table tbody');
                    tableBody.empty();
                    data.forEach(function(account) {
                        let row = `
                            <tr>
                                <td>${account.fname} ${account.lname}</td>
                                <td>${account.booking_start_date}</td>
                                <td>${account.room_number}</td>
                                <td>${account.email_address}</td>
                                <td>${account.contact_number}</td>
                                <td>${account.status}</td>
                                <td>
                                    <button>Approve</button>
                                    <button>Cancel</button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                }
            });
        }

        function fetchActiveTenants() {
            $.ajax({
                url: 'approved_accounts.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    let tableBody = $('#active-tenant-table tbody');
                    tableBody.empty();
                    data.forEach(function(tenant) {
                        let row = `
                            <tr>
                                <td><img src="path/to/profile-pic.png" alt="DP"></td>
                                <td>${tenant.fname} ${tenant.lname}</td>
                                <td>${tenant.move_in_date}</td>
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
            });
        }

    </script>
</body>
</html>
