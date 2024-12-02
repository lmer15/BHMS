document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('RegForm');
    const signUpErrorMessage = document.getElementById('signup-error-message');

    form.addEventListener('submit', (e) => {
        const fname = document.getElementById("FName").value;
        const lname = document.getElementById("LName").value;
        const contact_number = document.getElementById("ConNum").value;
        const email = document.getElementById("Email").value;
        const username = document.getElementById("Username").value;
        const password = document.getElementById("Password").value;
        const confirm_password = document.getElementById("ConfirmPassword").value;
        const num_occupants = document.getElementById("NumOccupants").value;
        const moving_in_date = document.getElementById("MovingInDate").value;

        // Clear previous error messages
        signUpErrorMessage.style.display = 'none';

        // Check if any required fields are empty
        if (!fname || !lname || !contact_number || !email || !username || !password || !confirm_password || !num_occupants || !moving_in_date) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Please fill in all fields.';
            signUpErrorMessage.style.display = 'block';
            return;
        }

        // Check if password and confirm password match
        if (password !== confirm_password) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Passwords do not match.';
            signUpErrorMessage.style.display = 'block';
            return;
        }

        // Password validation (length, uppercase, lowercase, number, special character)
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;
        if (!passwordPattern.test(password)) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.';
            signUpErrorMessage.style.display = 'block';
            return;
        }

        // Validate email format
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Please enter a valid email address.';
            signUpErrorMessage.style.display = 'block';
            return;
        }

        // Validate phone number format (example: 10 digits)
        const phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(contact_number)) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Please enter a valid 10-digit phone number.';
            signUpErrorMessage.style.display = 'block';
            return;
        }

        // Check if number of occupants is greater than 0
        if (num_occupants <= 0) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Number of occupants must be greater than 0.';
            signUpErrorMessage.style.display = 'block';
            return;
        }

        // Validate moving-in date (must be today or in the future)
        const today = new Date().toISOString().split('T')[0];
        if (moving_in_date < today) {
            e.preventDefault();
            signUpErrorMessage.textContent = 'Moving-in date cannot be in the past.';
            signUpErrorMessage.style.display = 'block';
            return;
        }
    });
});
