<?php
session_start();

if (!isset($_SESSION['tc_id']) || !isset($_SESSION['email_address'])) {
    // If not logged in, redirect the user to the login page
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER DASHBOARD</title>
    <link rel="stylesheet" href="styletDashboard.css">
    <script src="../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <!-- Navigation Bar -->
        <nav class="navigator">
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Dashboard/tDashboard.html', this)" aria-label="Home"><i class='bx bx-home icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Profile/tProfile.php', this)" aria-label="Profile"><i class='bx bx-user icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('ttPayments.html', this)" aria-label="Payments"><i class='bx bx-wallet icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Maintenance/maintenance.html', this)" aria-label="Maintenance"><i class='bx bx-wrench icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Notification/notif.html', this)" aria-label="Notifications">
                <i class='bx bx-bell icon'></i>
                <span class="notification-badge">5</span> <!-- Number of notifications -->
            </a>
            <a href="javascript:void(0);" onclick="loadPage('tSupport.html', this)" aria-label="About Us"><i class='bx bx-support icon'></i></a>
            <a href="Back-End/logout.php" aria-label="Logout"><i class='bx bx-log-out icon'></i></a>
        </nav>        

        <!-- Main Content Section -->
        <div class="main-content">
            <header class="header">
                <!-- Date and Time Container -->
                <div class="date-time" id="date-time">
                    <div class="date"></div>
                    <div class="time"></div>
                </div>

                <!-- Search Bar -->
                <div class="search">
                    <input type="search" name="search" placeholder="Search..." />
                    <i class='bx bx-search icon'></i>
                </div>

                <div class="right-side">
                    <span>Hi! Welcome, <span class="username"><?php echo $_SESSION['fname']; ?></span></span>
                    <div class="profile">
                        <img src="image/DP.png" alt="Profile Image" />
                    </div>
                </div>
            </header>

            <div id="content-container">
                <iframe id="content-iframe" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <script>
        // Function to load the target page into the iframe and update active link
        function loadPage(page, linkElement) {
            // Change iframe source to the clicked page
            var iframe = document.getElementById("content-iframe");
            iframe.src = page;
    
            // Remove 'active' class from all links
            var navLinks = document.querySelectorAll('.navigator a');
            navLinks.forEach(link => link.classList.remove('active'));
    
            // Add 'active' class to the clicked link
            linkElement.classList.add('active');
        }
    
        // Set the initial page and active link on window load
        window.onload = function() {
            var firstNavLink = document.querySelector('.navigator a');
            if (firstNavLink) {
                loadPage('tDashboard.html', firstNavLink);
            }
            updateDateTime();
            setInterval(updateDateTime, 1000); // Update every second
        };

        // Function to update date and time
        function updateDateTime() {
            const dateElement = document.querySelector('.date-time .date');
            const timeElement = document.querySelector('.date-time .time');

            const now = new Date();
            const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
            const date = now.toLocaleDateString('en-US', options);
            const time = now.toLocaleTimeString('en-US', { hour12: true });

            dateElement.textContent = date;
            timeElement.textContent = time;
        }
    </script>

</body>
</html>

