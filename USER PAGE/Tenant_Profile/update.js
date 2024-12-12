document.addEventListener('DOMContentLoaded', function() {
    // Handling the update form submission with AJAX
    document.getElementById('updateForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        let formData = new FormData(this); // Collect the form data

        // Perform the AJAX request
        fetch('updateDetails.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // Change to text() temporarily for debugging
        .then(data => {
            console.log(data); // Log the response to check what it contains
            try {
                const jsonData = JSON.parse(data); // Try parsing the response as JSON
                if (jsonData.success) {
                    alert(jsonData.message);
                    window.location.reload();
                } else {
                    alert(jsonData.message);
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        })
        .catch(error => console.error('Error:', error));
        
    });

    // Handling the change password form submission with AJAX
    document.getElementById('updatePass').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        let oldPassword = document.getElementById('oldPassword').value;
        let newPassword = document.getElementById('newPassword').value;
        let confirmPassword = document.getElementById('confirmPassword').value;

        // Create the data to send via POST
        let formData = new FormData();
        formData.append('oldPassword', oldPassword);
        formData.append('newPassword', newPassword);
        formData.append('confirmPassword', confirmPassword);

        // Make the AJAX request
        fetch('changePass.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('error-message').style.display = 'none';
                document.getElementById('password-mismatch').style.display = 'none';
                alert(data.message); // Show success message
                window.location.reload(); // Reload page after update
            } else {
                document.getElementById('error-message').innerText = data.message;
                document.getElementById('error-message').style.display = 'block';
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
