document.addEventListener("DOMContentLoaded", function() {
    const dashMaintenance = document.getElementById("dashboard");
    const addRequest = document.getElementById("send-request");
    const classDash = document.querySelector(".dashboard");
    const classAddReq = document.querySelector(".send_maintenance_request");
    function showSection(section) {
        section.style.display = "block";
    }
    function hideSection(section) {
        section.style.display = "none";
    }
    showSection(classDash);
    hideSection(classAddReq);
    function setActiveLink(link) {
        const links = document.querySelectorAll('.navigator a');
        links.forEach(link => link.classList.remove('active')); 
        link.classList.add('active');
    }
    dashMaintenance.addEventListener("click", function(event) {
        event.preventDefault();
        showSection(classDash);
        hideSection(classAddReq);
        setActiveLink(dashMaintenance);
    });

    addRequest.addEventListener("click", function(event) {
        event.preventDefault();
        showSection(classAddReq);
        hideSection(classDash);
        setActiveLink(addRequest);
    });
});

