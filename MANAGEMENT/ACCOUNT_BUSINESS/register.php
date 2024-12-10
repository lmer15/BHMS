<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTRATION FORM</title>
    <link rel="stylesheet" href="accounts.css?v=1.0">
    <script src="../../imported_links.js" defer></script>
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <section id="wrapper">
        <section id="forms-section">
            <div class="login-section">
                <h1>MANAGEMENT ACCOUNT REGISTRATION</h1>

                <form method="POST" action="management_register.php" id="RegForm">
                    <div class="logform">
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
              
                        <p id="error-message" style="color: red; font-size: small; font-weight: 500; display: none; margin-bottom: 10px;"></p>

                        <a href=""><button type="submit">REGISTER</button></a>
                    </div>
                </form>
            </div>
        </section>
    </section>

    <script>
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

    <script>
        document.getElementById("RegForm").addEventListener("submit", function (event) {
            event.preventDefault(); 

            const form = document.getElementById("RegForm");
            const formData = new FormData(form);

            fetch('management_register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const errorMessageElement = document.getElementById("error-message");
                if (data.status === 'error') {
                    errorMessageElement.textContent = data.message;
                    errorMessageElement.style.display = 'block';
                } else if (data.status === 'success') {
                    // Show success alert and then redirect to login
                    alert(data.message);  // Show the success message
                    window.location.href = "../../USER PAGE/login.php";  // Redirect to the login page
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
