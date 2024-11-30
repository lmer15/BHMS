document.addEventListener("DOMContentLoaded", function() {
    const dashMaintenance = document.getElementById("dashboard");
    const addRequest = document.getElementById("send-declined");
    const backArrow = document.getElementById("back-arrow");

    // Sections for Dashboard and Send Request
    const classDash = document.querySelector(".dashboard");
    const classAddReq = document.querySelector(".send_decline_reason");

    // Function to show a section
    function showSection(section) {
        section.style.display = "block";
    }

    // Function to hide a section
    function hideSection(section) {
        section.style.display = "none";
    }

    // Initially show the Dashboard and hide Add Request
    showSection(classDash);
    hideSection(classAddReq);

    // Function to set the active navigation link
    function setActiveLink(link) {
        const links = document.querySelectorAll('.navigator a');
        links.forEach(link => link.classList.remove('active')); // Remove active class from all links
        link.classList.add('active'); // Add active class to the clicked link
    }

    // Event listener to show the Dashboard and hide Add Request
    dashMaintenance.addEventListener("click", function(event) {
        event.preventDefault();

        // Show Dashboard and hide Add Request
        showSection(classDash);
        hideSection(classAddReq);

        // Highlight the active navigation link
        setActiveLink(dashMaintenance);
    });

    // Event listener to show the Add Request form and hide Dashboard
    addRequest.addEventListener("click", function(event) {
        event.preventDefault();

        // Show Add Request form and hide Dashboard
        showSection(classAddReq);
        hideSection(classDash);

        // Highlight the active navigation link
        setActiveLink(addRequest);
    });

    // Event listener for the back arrow to show the Dashboard and hide Add Request
    backArrow.addEventListener("click", function(event) {
        event.preventDefault();

        // Show Dashboard and hide Add Request
        showSection(classDash);
        hideSection(classAddReq);

        // Highlight the active navigation link
        setActiveLink(dashMaintenance);
    });
});
