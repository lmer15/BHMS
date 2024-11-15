document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("updateModal");
    const updateLink = document.getElementById("updateLink"); // The update link
    const closeModal = document.querySelector(".modal .close");
    const changePasswordLink = document.getElementById("changePasswordLink"); // Link for changing password
    const descPro = document.querySelector(".descpro");
    const roomInfo = document.querySelector(".roominfo");
    const changePass = document.querySelector(".change-pass");

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

        // Hide the "Personal Details" and "Room Info" sections
        descPro.style.display = "none";
        roomInfo.style.display = "none";

        // Show the "Change Password" form
        changePass.style.display = "block";
    });
});
