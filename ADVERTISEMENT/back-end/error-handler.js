document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('RegForm');
    const signUpErrorMessage = document.getElementById('signup-error-message');
    signUpErrorMessage.style.display = 'none';

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'error') {
                signUpErrorMessage.textContent = data.message;
                signUpErrorMessage.style.display = 'block';
            } else if (data.status === 'success') {
                alert(data.message);
                if (data.receipt_url) {
                    window.location.href = data.receipt_url;
                }
            }
        })
        .catch(() => {
            signUpErrorMessage.textContent = 'An unexpected error occurred. Please try again.';
            signUpErrorMessage.style.display = 'block';
        });
    });
});
