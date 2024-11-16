document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("updateModal");
    const updateLink = document.getElementById("updateLink"); 
    const closeModal = document.querySelector(".modal .close");
    const changePasswordLink = document.getElementById("changePasswordLink"); 
    const descPro = document.querySelector(".descpro");
    const roomInfo = document.querySelector(".roominfo");
    const changePass = document.querySelector(".change-pass");
    const roomDetails = document.getElementById("roomDetails");
    const roomDetailsLink = document.getElementById("roomDetailsLink");
    const profile = document.querySelector(".profile"); 
    const leaseAgreement = document.getElementById("leaseAgreement");
    const leaseLink = document.getElementById("leaseLink");

    // Open the modal when the "Update Personal Details" link is clicked
    updateLink.addEventListener("click", function(event) {
        event.preventDefault();
        modal.style.display = "block";
    });

    // Close the modal when the close button (x) is clicked
    closeModal.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Close the modal when clicking outside the modal content
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });

    // Show "Change Password" form, hide other sections
    changePasswordLink.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Hide all other sections
        descPro.style.display = "none";
        roomInfo.style.display = "none";
        roomDetails.style.display = "none";
        leaseAgreement.style.display = "none";

        // Show the "Change Password" form
        changePass.style.display = "block";
        profile.style.display = "block"
    });

    // Show Room Details and hide profile container when "Room Details" link is clicked
    roomDetailsLink.addEventListener("click", function(event) {
        event.preventDefault();

        // Hide all sections except room details
        descPro.style.display = "none";
        roomInfo.style.display = "none";
        changePass.style.display = "none"; // Hide change password form
        profile.style.display = "none"; // Hide profile container
        leaseAgreement.style.display = "none";

        // Show the room details section
        roomDetails.style.display = "block";
    });

    leaseLink.addEventListener("click", function(event) {
        event.preventDefault();

        // Hide all sections except room details
        descPro.style.display = "none";
        roomInfo.style.display = "none";
        changePass.style.display = "none"; // Hide change password form
        profile.style.display = "none"; // Hide profile container
        roomDetails.style.display = "none";

        // Show the room details section
        leaseAgreement.style.display = "block";
    });
});


// Get the elements for profile picture and modal
const profilePicture = document.querySelector(".dp img");
const plusIcon = document.querySelector(".dp .icon");
const updateModal = document.getElementById("updateModal");
const closeModalButton = document.querySelector(".close");
const updateLink = document.getElementById("updateLink");
const updateForm = document.getElementById("updateForm");
const updateButton = document.querySelector("#updateForm button");

// Open the update modal when clicking the edit link
updateLink.addEventListener("click", (event) => {
    event.preventDefault(); // Prevent page reload
    updateModal.style.display = "flex"; // Show the modal
});

// Close the modal when clicking the close button
closeModalButton.addEventListener("click", () => {
    updateModal.style.display = "none"; // Hide the modal
});

// Close the modal if the user clicks outside the modal content
window.addEventListener("click", (event) => {
    if (event.target === updateModal) {
        updateModal.style.display = "none"; // Close if clicked outside
    }
});

// Open the file input for updating profile picture when clicking the plus icon
plusIcon.addEventListener("click", () => {
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = "image/*";
    
    // When a new image is selected, update the profile picture
    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                profilePicture.src = event.target.result; // Update profile picture
            };
            reader.readAsDataURL(file); // Read the selected file as a data URL
        }
    });
    
    fileInput.click(); // Open file input dialog
});

// Handle form submission to update user details
updateForm.addEventListener("submit", (event) => {
    event.preventDefault(); // Prevent form submission to server
    
    // Here you can add code to save the form data or send it to a server
    
    // For now, just close the modal after saving the form data
    updateModal.style.display = "none";
    alert("Profile updated successfully!");
});
