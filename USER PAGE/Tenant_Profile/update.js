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
            // Display success message and hide the change password form
            document.getElementById('error-message').style.display = 'none';
            document.getElementById('password-mismatch').style.display = 'none';
            alert(data.message); // or display it somewhere in your div
        } else {
            // Show the error message
            document.getElementById('error-message').innerText = data.message;
            document.getElementById('error-message').style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handling the update details form submission with AJAX
document.getElementById('updateForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    let name = document.getElementById('name').value;
    let gender = document.getElementById('gender').value;
    let email = document.getElementById('email').value;
    let contact = document.getElementById('contact').value;
    let religion = document.getElementById('religion').value;
    let nationality = document.getElementById('nationality').value;
    let occupation = document.getElementById('occupation').value;

    // Create the data to send via POST
    let formData = new FormData();
    formData.append('name', name);
    formData.append('gender', gender);
    formData.append('email', email);
    formData.append('contact', contact);
    formData.append('religion', religion);
    formData.append('nationality', nationality);
    formData.append('occupation', occupation);

    // Make the AJAX request
    fetch('updateDetails.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Display success message and update session values
            alert(data.message); // or display it somewhere in your div
            // Optionally, update the profile information on the page
        } else {
            // Show the error message
            document.getElementById('error-message').innerText = data.message;
            document.getElementById('error-message').style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));
});
