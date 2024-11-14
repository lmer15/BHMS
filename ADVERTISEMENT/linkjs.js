const links = document.querySelectorAll('.navigator a');

// Loop through each link and check if its href matches the current page URL
links.forEach(link => {
    // If the link's href matches the current page URL, add the 'active' class
    if (link.href === window.location.href) {
        link.classList.add('active'); // Add the 'active' class to the link
    }
});


document.getElementById('room-filter').addEventListener('change', function() {
    var selectedType = this.value; // Get the selected filter type
    var rooms = document.querySelectorAll('.each-room'); // Get all the room elements

    rooms.forEach(function(room) {
        if (selectedType === 'all') {
            // Show all rooms
            room.style.display = 'block';
        } else if (room.getAttribute('data-type') === selectedType) {
            // Show only rooms that match the selected type
            room.style.display = 'block';
        } else {
            // Hide rooms that do not match the selected type
            room.style.display = 'none';
        }
    });
});

