document.addEventListener('DOMContentLoaded', () => {
    const errorMessage = document.getElementById('error-message');
    const signUpErrorMessage = document.getElementById('signup-error-message');

    // Handle error messages based on URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error) {
        const errorMessages = {
            'empty_fields': 'Please fill in all fields.',
            'invalid_password': 'Invalid username or password. Please try again.',
            'user_not_found': 'User not found. Please check your username.',
            'password_mismatch': 'Passwords do not match.',
            'username_exists': 'Username already exists.',
            'duplicate_email': 'Email already exists. Please use a different email.',
            'server_error': 'A server error occurred. Please try again later.',
            'invalid_email_format': 'Please enter a valid email address.',
            'email_not_found': 'Email not found.'
        };

        const errorMessageText = errorMessages[error] || 'An unknown error occurred.';

        // Show the appropriate error message
        if (error === 'invalid_password' || error === 'user_not_found') {
            errorMessage.textContent = errorMessageText;
            errorMessage.style.display = 'block'; // Show sign-in error message
        } else {
            signUpErrorMessage.textContent = errorMessageText;
            signUpErrorMessage.style.display = 'block'; // Show sign-up error message
        }
    }
});
