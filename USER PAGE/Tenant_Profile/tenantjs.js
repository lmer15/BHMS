document.addEventListener("DOMContentLoaded", function() {
    // Modal Elements
    const updateModal = document.getElementById("updateModal");
    const closeModalButton = document.querySelector(".modal .close");

    // Links and Sections
    const updateLink = document.getElementById("updateLink"); 
    const changePasswordLink = document.getElementById("changePasswordLink"); 
    const roomDetailsLink = document.getElementById("roomDetailsLink");
    const leaseLink = document.getElementById("leaseLink");
    
    const descPro = document.querySelector(".descpro");
    const roomInfo = document.querySelector(".roominfo");
    const changePass = document.querySelector(".change-pass");
    const roomDetails = document.getElementById("roomDetails");
    const leaseAgreement = document.getElementById("leaseAgreement");
    const profile = document.querySelector(".profile");

    // Profile Picture Elements
    const profilePicture = document.querySelector(".dp img");
    const plusIcon = document.querySelector(".dp .icon");
    const profileImageInput = document.getElementById('profileImageInput');
    const uploadForm = document.getElementById('uploadForm');

    // Handle opening of the update modal
    updateLink.addEventListener("click", function(event) {
        event.preventDefault();
        updateModal.style.display = "flex"; // Show the modal
    });

    // Close the modal when clicking the close button
    closeModalButton.addEventListener("click", function() {
        updateModal.style.display = "none"; // Hide the modal
    });

    // Close the modal when clicking outside the modal content
    window.addEventListener("click", function(event) {
        if (event.target === updateModal) {
            updateModal.style.display = "none"; // Close if clicked outside
        }
    });

    // Show "Change Password" form, hide other sections
    changePasswordLink.addEventListener("click", function(event) {
        event.preventDefault();

        // Hide other sections
        descPro.style.display = "none";
        roomInfo.style.display = "none";
        roomDetails.style.display = "none";
        leaseAgreement.style.display = "none";

        // Show the "Change Password" form
        changePass.style.display = "block";
        profile.style.display = "block";
    });

    // Show Room Details and hide other sections
    roomDetailsLink.addEventListener("click", function(event) {
        event.preventDefault();

        // Hide all sections except room details
        descPro.style.display = "none";
        roomInfo.style.display = "none";
        changePass.style.display = "none";
        profile.style.display = "none";
        leaseAgreement.style.display = "none";

        // Show the room details section
        roomDetails.style.display = "block";
    });

    // Show Lease Agreement and hide other sections
    leaseLink.addEventListener("click", function(event) {
        event.preventDefault();

        // Hide all sections except lease agreement
        descPro.style.display = "none";
        roomInfo.style.display = "none";
        changePass.style.display = "none";
        profile.style.display = "none";
        roomDetails.style.display = "none";

        // Show the lease agreement section
        leaseAgreement.style.display = "block";
    });

    // Trigger file input for profile picture upload
    plusIcon.addEventListener("click", function() {
        profileImageInput.click(); // Trigger file input click
    });

    // Preview the selected image before uploading
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePicture.src = e.target.result; // Update image preview
            };
            reader.readAsDataURL(file); // Read file as Data URL
        }
    }

    // Handle the profile picture upload form submission
    uploadForm.addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent normal form submission

        const formData = new FormData(this);

        fetch('profileUpload.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Show success/error message after upload
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Connect the file input's `onchange` to previewImage function
    profileImageInput.addEventListener('change', previewImage);
});
