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
            window.location.reload(); 
        } else {
            // Show the error message
            document.getElementById('error-message').innerText = data.message;
            document.getElementById('error-message').style.display = 'block';
        }
    })
    .catch(error => console.error('Error:', error));
});

document.getElementById('updateForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission


    let formData = new FormData(this);

    // Perform the AJAX request
    fetch('updateDetails.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and hide the modal or redirect
            alert(data.message);
            // Optionally, close the modal or redirect
            window.location.reload();  // Reload page after update
        } else {
            // Show error message
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
