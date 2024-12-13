<?php
    session_start();

    if (!isset($_SESSION['tc_id']) || !isset($_SESSION['email_address'])) {
        header("Location: login.php");
        exit();
    }

    require_once '../DATABASE/dbConnector.php';

    $tenant_id = $_SESSION['tc_id'];

    $tenant_query = "SELECT * FROM tenant_details WHERE tc_id = ?";
    $tenant_stmt = mysqli_prepare($conn, $tenant_query);
    mysqli_stmt_bind_param($tenant_stmt, "i", $tenant_id); 
    mysqli_stmt_execute($tenant_stmt);
    $tenant_result = mysqli_stmt_get_result($tenant_stmt);
    $tenant = mysqli_fetch_assoc($tenant_result);

    if (!$tenant) {
        echo "Tenant data not found.";
        exit(); 
    }

    $profile = $tenant['profile'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER DASHBOARD</title>
    <link rel="stylesheet" href="Tenant_Dashboard/styletDashboard.css">
    <script src="../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <!-- Navigation Bar -->
        <nav class="navigator">
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Dashboard/tDashboard.html', this)" aria-label="Home"><i class='bx bx-home icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Profile/tProfile.php', this)" aria-label="Profile"><i class='bx bx-user icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Payment/pay.php', this)" aria-label="Payments"><i class='bx bx-wallet icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Maintenance/maintenance.php', this)" aria-label="Maintenance"><i class='bx bx-wrench icon'></i></a>
            <a href="javascript:void(0);" onclick="loadPage('Tenant_Notification/notif.php', this)" aria-label="Notifications">
                <i class='bx bx-bell icon'></i>
                <span class="notification-badge">
                    <?php echo isset($_SESSION['notification_count']) ? $_SESSION['notification_count'] : '0'; ?>
                </span>
            </a>
            <a href="javascript:void(0);" onclick="loadPage('../ADVERTISEMENT/contact-us.php', this)" aria-label="About Us"><i class='bx bx-support icon'></i></a>
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
                    <span>Hi! Welcome, <span class="username"><?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?></span></span>
                    <div class="profile">
                        <img src="<?php echo !empty($tenant['profile']) ? 'Tenant_Profile/uploads/' . htmlspecialchars($tenant['profile']) : 'image/DP.png'; ?>" alt="Profile Image" onerror="this.src='image/DP.png';" />
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
            var iframe = document.getElementById("content-iframe");
            iframe.src = page;

            var navLinks = document.querySelectorAll('.navigator a');
            navLinks.forEach(link => link.classList.remove('active'));

            linkElement.classList.add('active');
        }

        // Set the initial page and active link on window load
        window.onload = function() {
            var firstNavLink = document.querySelector('.navigator a');
            if (firstNavLink) {
                loadPage('Tenant_Dashboard/tDashboard.html', firstNavLink);
            }
            updateDateTime();
            setInterval(updateDateTime, 1000);
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
