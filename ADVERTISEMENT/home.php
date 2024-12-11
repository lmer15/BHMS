<?php
require_once '../DATABASE/dbConnector.php';

$query = "SELECT business_name, business_acronym, business_logo FROM business_profiles LIMIT 1";
$result = $conn->query($query);
$businessProfile = $result->fetch_assoc();

$business_name = $businessProfile['business_name'] ?? 'Business Name';
$acronym = $businessProfile['business_acronym'] ?? 'ACRONYM';
$logo = $businessProfile['business_logo'] ?? 'images/default-logo.png';

$logoPath = file_exists('../MANAGEMENT/uploads/' . $logo) ? '../MANAGEMENT/uploads/' . $logo : 'images/default-logo.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>

    <section id="wrappers">
        <div class="header">
            <div class="logo">
                <!-- Display business logo and acronym dynamically -->
                <img src="<?php echo $logoPath; ?>" alt="Business Logo">
                <span><?php echo $acronym; ?></span>
            </div>
            <input type="checkbox" id="burger-toggle">
            <label for="burger-toggle" class="burger-icon"><i class='bx bx-menu'></i></label>
            <div class="navigator">
                <a href="javascript:void(0);" onclick="loadPage('index.php', this)" aria-label="Home">HOME</a>
                <a href="javascript:void(0);" onclick="loadPage('rooms.php', this)" aria-label="Rooms">ROOMS</a>
                <a href="javascript:void(0);" onclick="loadPage('aminities.php', this)" aria-label="Aminities">AMINITIES</a>
                <a href="javascript:void(0);" onclick="loadPage('services.html', this)" aria-label="Services">SERVICES</a>
                <a href="javascript:void(0);" onclick="loadPage('about-us.html', this)" aria-label="About Us">ABOUT US</a>
                <a href="javascript:void(0);" onclick="loadPage('contact-us.html', this)" aria-label="Contact Us">CONTACT US</a>
            </div>
        </div>
        <div class="body">
            <div id="content-container">
                <iframe id="content-iframe" frameborder="0"></iframe>
            </div>
        </div>
    </section>

    <script>
        function loadPage(page, linkElement) {
            var iframe = document.getElementById("content-iframe");
            iframe.src = page;
            var navLinks = document.querySelectorAll('.navigator a');
            navLinks.forEach(link => link.classList.remove('active'));
            linkElement.classList.add('active');
        }

        window.onload = function() {
            var firstNavLink = document.querySelector('.navigator a');
            if (firstNavLink) {
                loadPage('index.php', firstNavLink); 
            }
        };
    </script>

</body>
</html>
