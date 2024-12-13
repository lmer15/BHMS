<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'admin') {
    header("Location: ../USER PAGE/login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANAGEMENT DASHBOARD</title>
    <link rel="stylesheet" href="management.css?v=1.0">
    <script src="../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <!-- Navigation Bar -->
        <nav class="navigator">
            <a href="javascript:void(0);" onclick="loadPage('Dashboard/manDash.php', this)" aria-label="Home"><i class='bx bx-home icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Information/tenant_info.php', this)" aria-label="Profile"><i class='bx bx-user icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Room/room.php', this)" aria-label="Profile"><i class='bx bx-door-open icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Payment/pay.html', this)" aria-label="Payments"><i class='bx bx-wallet icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Maintenance/tenant_maintenance.php', this)" aria-label="Maintenance"><i class='bx bx-wrench icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Notification/notification.php', this)" aria-label="Notifications">
                <i class='bx bx-bell icon'></i>
                <span class="notification-badge">5</span> <!-- Number of notifications -->
            </a>
            <a href="../ADVERTISEMENT/home.php" ><i class="bx bxs-briefcase icon"></i></a>
            <a href="../USER PAGE/Back-End/logout.php" aria-label="Logout"><i class='bx bx-log-out icon'></i></a>
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
                    <span class="name">BHMS</span>
                    <div class="profile">
                        <img src="../IMAGES/GREEN_LOGO.png" alt="Profile Image" />
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
                loadPage('Dashboard/manDash.php', firstNavLink);
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

