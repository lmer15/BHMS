<?php
// Get the room number from the URL
$room_number = isset($_GET['room_number']) ? $_GET['room_number'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRATION FORM</title>
    <link rel="stylesheet" href="StyleForm.css?v=1.4">
    <script src="../imported_links.js" defer></script>
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <section id="wrapper">
        <section id="forms-section">
            <div class="left-picture">
                <img src="images/BHMS LOGO.png" alt="LOGO">
                <p>
                    Welcome to BHMS, where comfort meets community! Whether you’re a student, 
                    a professional, a corporate worker or simply in need of a cozy place to stay, 
                    our boarding house offers a welcoming environment with all the amenities you need. 
                    Enjoy spacious rooms, communal spaces, and a friendly atmosphere that feels like home. 
                    Just a little types and click, and enjoy the environment of our boarding house.
                </p>
                <div class="cont">
                    <span>You Can Connect Us Through Here!</span>
                </div>
                <div class="icons">
                    <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="images/x.png" alt="X"></a>
                    <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="images/pinterest.png" alt="Pinterest"></a>
                    <a href="#"><img src="images/tiktok.png" alt="TikTok"></a>
                </div>
            </div>

            <div class="login-section">
                <h1>BOOKING FORM</h1>
                <form method="POST" action="back-end/register.php" id="RegForm">
                    <div class="logform">
                        <!-- Room Number Display -->
                        <div class="input-container">
                            <i class='bx bxs-bed icon'></i>
                            <input type="text" name="room_number" id="RoomNumber" value="<?php echo htmlspecialchars($room_number); ?>" readonly>
                        </div>

                        <!-- Number of Occupants -->
                        <div class="input-container">
                            <i class='bx bxs-group icon'></i>
                            <input type="number" name="num_occupants" id="NumOccupants" placeholder="Number of Occupants" required min="1">
                        </div>

                        <!-- Booking Date -->
                        <div class="input-container">
                            <i class='bx bxs-calendar icon'></i>
                            <input type="date" name="booking_date" id="BookingDate" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>

                        <!-- Moving-In Date -->
                        <div class="input-container">
                            <i class='bx bxs-calendar-check icon'></i>
                            <input type="date" name="moving_in_date" id="MovingInDate" placeholder="Moving-in Date" required>
                        </div>

                        <!-- First Name -->
                        <div class="input-container">
                            <i class='bx bxs-user icon'></i>
                            <input type="text" name="fname" id="FName" placeholder="First Name" required>
                        </div>

                        <!-- Last Name -->
                        <div class="input-container">
                            <i class='bx bxs-user icon'></i>
                            <input type="text" name="lname" id="LName" placeholder="Last Name" required>
                        </div>

                        <!-- Contact Number -->
                        <div class="input-container">
                            <i class='bx bxs-phone icon'></i>
                            <input type="number" name="contact_number" id="ConNum" placeholder="Contact Number" required>
                        </div>

                        <!-- Email -->
                        <div class="input-container">
                            <i class='bx bxs-envelope icon'></i>
                            <input type="email" name="email" id="Email" placeholder="Email Address" required>
                        </div>

                        <!-- Username -->
                        <div class="input-container">
                            <i class='bx bxs-user icon'></i>
                            <input type="text" name="username" id="Username" placeholder="Username" required>
                        </div>

                        <!-- Password -->
                        <div class="input-container">
                            <input type="password" name="password" id="Password" placeholder="Password" required>
                            <i class='bx bx-hide icon' id="togglePassword1"></i>
                        </div>

                        <!-- Confirm Password -->
                        <div class="input-container">
                            <input type="password" name="confirm_password" id="ConfirmPassword" placeholder="Confirm Password" required>
                            <i class='bx bx-hide icon' id="togglePassword2"></i>
                        </div>

                        <p id="error-message" style="color: red; display: none; font-size: small; font-weight: 500;">Invalid username or password. Please try again.</p>
                        <p id="signup-error-message" style="color: red; display: none; font-size: small; font-weight: 500;"></p>

                        <a href=""><button type="submit">REGISTER</button></a>
                    </div>
                </form>

            </div>
        </section>
    </section>

    <script>
        // JavaScript to toggle password visibility for both password fields
        const togglePassword1 = document.getElementById("togglePassword1");
        const passwordField1 = document.getElementById("Password");
        const togglePassword2 = document.getElementById("togglePassword2");
        const passwordField2 = document.getElementById("ConfirmPassword");

        togglePassword1.addEventListener("click", function() {
            if (passwordField1.type === "password") {
                passwordField1.type = "text";
                togglePassword1.classList.remove("bx-hide");
                togglePassword1.classList.add("bx-show");
            } else {
                passwordField1.type = "password";
                togglePassword1.classList.remove("bx-show");
                togglePassword1.classList.add("bx-hide");
            }
        });

        togglePassword2.addEventListener("click", function() {
            if (passwordField2.type === "password") {
                passwordField2.type = "text";
                togglePassword2.classList.remove("bx-hide");
                togglePassword2.classList.add("bx-show");
            } else {
                passwordField2.type = "password";
                togglePassword2.classList.remove("bx-show");
                togglePassword2.classList.add("bx-hide");
            }
        });
    </script>

    <script src="back-end/error-handler.js"></script>
</body>
</html>
