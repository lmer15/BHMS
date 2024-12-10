<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Profile Registration</title>
    <link rel="stylesheet" href="business.css">
</head>
<body>
    <div class="wrapper">
        <form id="businessForm" method="POST" enctype="multipart/form-data">
            <h2>Business Profile Registration</h2>
            <div>
                <label for="business_name">Business Name:</label>
                <input type="text" id="business_name" name="business_name" required placeholder="Enter your business name">
            </div>

            <div>
                <label for="acronym">Business Acronym:</label>
                <input type="text" id="acronym" name="acronym" placeholder="Enter your business acronym">
            </div>

            <div>
                <label for="logo">Upload Business Logo:</label>
                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/gif" required>
            </div>

            <div>
                <label for="email">Business Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your business email">
            </div>

            <div>
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required placeholder="Enter your phone number (10 digits)">
            </div>

            <div>
                <label for="address">Business Address:</label>
                <textarea id="address" name="address" required placeholder="Enter your business address"></textarea>
            </div>

            <div>
                <label for="description">Business Description:</label>
                <textarea id="description" name="description" required placeholder="Provide a brief description of your business"></textarea>
            </div>

            <div id="error-message" style="color:red; display:none;"></div>

            <div>
                <button type="submit" id="submit-btn">Register Business</button>
            </div>

        </form>
    </div>

    <script>
        document.getElementById("businessForm").addEventListener("submit", function (event) {
            event.preventDefault();

            const form = document.getElementById("businessForm");
            const formData = new FormData(form);

            fetch('business_save.php', {
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
                    alert(data.message);  
                    window.location.href = "register.php";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
