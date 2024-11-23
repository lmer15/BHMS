<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="loginStyle.css">
    <script src="../imported_links.js" defer></script>
</head>
<body>
    <div class="wrapper">
        <div class="form">
            <div class="form-left">
                <div class="navs">
                    <a href="../ADVERTISEMENT/index.html">About Us</a>
                    <a href="">Any Concern?</a>
                </div>

                <img src="../IMAGES/GREEN_LOGO.png" alt="LOGO">
                <h1>BHMS</h1>
            </div>

            <form method="POST" action="Back-End/login.php">
                <div class="right-form">
                    <h1>LOG IN</h1>
                    <p>Got a problem with logging in? <a href="../ADVERTISEMENT/register-form.html" id="clickHereLink">Click Here!</a></p>

                    <div class="login-container">
                        <div class="icon-container">
                            <i class='bx bxs-envelope icon'></i>
                            <input type="email" name="email" id="Email" placeholder="Email Address" required 
                            value="<?php echo isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : ''; ?>">
                        </div>
                    </div>

                    <div class="login-container">
                        <div class="icon-container">
                            <i class='bx bxs-lock icon'></i>
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <i class='bx bx-show' id="togglePassword" aria-label="Toggle password visibility"></i>
                        </div>
                    </div>

                    <label for="rememberMe">
                        <input type="checkbox" name="rememberMe" id="rememberMe" <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>> Remember Me
                    </label>
                    
                    <button class="login-button">LOG IN</button>
                    <!-- Error Message, initially hidden -->
                    <p class="error" id="error-message" style="color: red; display: none; font-size: small; font-weight: 500;">Invalid password. Please try again.</p>
                    <p class="error" id="signup-error-message" style="color: red; display: none; font-size: small; font-weight: 300;"></p>
                    <span>Forgot Password?</span>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Listen for the "Click Here!" link to hide the navigation when clicked
        document.addEventListener("DOMContentLoaded", () => {
            const clickHereLink = document.getElementById("clickHereLink");

            if (clickHereLink) {
                clickHereLink.addEventListener("click", function () {
                    localStorage.setItem('hideNavigator', 'true');
                });
            }
        });

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('bx-show');
            togglePassword.classList.toggle('bx-hide');
        });
    </script>

    <script src="Back-End/errorhandlers.js"></script>
</body>
</html>