<?php
session_start();

require_once '../DATABASE/dbConnector.php'; 

$isAdmin = false;
$isLoggedIn = false;

if (isset($_SESSION['username'])) {
    $isLoggedIn = true;

    $username = $_SESSION['username']; 
    $query = "SELECT type FROM user_accounts WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $stmt->bind_result($userType);
    $stmt->fetch();
    $stmt->close();

    $isAdmin = ($userType === 'admin');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css?v=1.10">
    <script src="../imported_links.js" defer></script>
</head>
<body>
    <section id="wrapper">
        <div class="body">
            <div class="left">
                <h1>Discover your <span style="color: #12a15f;">perfect</span> home</h1>
                <p>
                    Welcome to our cozy boarding house - the <span style="color: #12a15f; font-weight: bold;">BHMS</span>, where comfort meets hospitality. 
                    Enjoy a relaxing stay in our thoughtfully designed rooms, complemented by
                    top-notch amenities and a warm, inviting atmosphere.
                </p>
                <?php if ($isAdmin): ?>
                    <div class="navlink admin">
                        <a href="#" class="inquire-button" id="login-link" onclick="redirectToDashboard()">
                            <i class='bx bx-arrow-back'></i>BACK TO DASHBOARD
                        </a>
                    </div>
                <?php elseif ($isLoggedIn): ?>
                    <div class="navlink">
                        <a href="rooms.php" class="inquire-button" id="login-link">
                            <i class='bx bxs-bookmark'></i> INQUIRE ROOM NOW!
                        </a>
                        <a href="../USER PAGE/login.php" class="login-button" id="login-link" target="_parent">
                            <i class='bx bxs-pen'></i> DONE WITH BOOKING? <span>Click Here!</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="navlink">
                        <a href="rooms.php" class="inquire-button" id="login-link">
                            <i class='bx bxs-bookmark'></i> INQUIRE ROOM NOW!
                        </a>
                        <a href="../USER PAGE/login.php" class="login-button" id="login-link" target="_parent">
                            <i class='bx bxs-pen'></i> DONE WITH BOOKING? <span>Click Here!</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="right">
                <img src="images/BH_PIC.png" alt="">
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function redirectToDashboard() {
            window.top.location.href = "../MANAGEMENT/management.php"; 
        }
    </script>
</body>
</html>
