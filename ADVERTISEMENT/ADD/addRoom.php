<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="styleAdd.css">
</head>
<body>
    <div class="wrapper">
        <div class="room-details-form">
            <h2>Add New Room</h2>
        
            <div class="modal-form">
                <form id="addRoomForm" action="add-room.php" method="POST" enctype="multipart/form-data">
                    <!-- Form Fields -->
                    <label for="roomImage">Room Image:</label>
                    <input type="file" id="roomImage" name="roomImage" accept="image/*" required>
                    <div id="imageError" style="color: red; display: none;"></div>
                    
                    <label for="roomNumber">Room Number:</label>
                    <input type="text" id="roomNumberInput" name="roomNumber" required>

                    <label for="roomType">Room Type:</label>
                    <select id="roomTypeInput" name="roomType" required>
                        <option value="" disabled selected>Select Room Type</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                        <option value="Suite">Family</option>
                    </select>

                    <label for="roomSize">Room Size:</label>
                    <input type="number" id="roomSizeInput" name="roomSize" required placeholder="m²">
                
                    <label for="amenities">Amenities:</label>
                    <textarea id="amenitiesInput" name="amenities" required placeholder="Air-conditioning, Mini-Kitchen, Wi-Fi..."></textarea>

                    <label for="utilities">Utilities:</label>
                    <textarea id="utilitiesInput" name="utilities" required placeholder="Electricity, Gas, Water, Sewage..."></textarea>

                    <label for="rentalRates">Rental Rates:</label>
                    <input type="number" id="rentalRatesInput" name="rentalRates" required placeholder="₱">

                    <label for="depositRate">Deposit Rate:</label>
                    <input type="number" id="depositRateInput" name="depositRate" required placeholder="₱">
                        
                    <!-- Display error messages -->
                    <div id="signup-error-message" class="error" style="color: red; font-size: small; font-weight: 300; display: none;"></div>

                    <button type="submit">Add Room</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image Preview JavaScript
        document.getElementById('roomImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const errorMessage = document.getElementById('imageError');
            if (file) {
                const reader = new FileReader();
                const allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxFileSize = 5 * 1024 * 1024; // 5MB

                // Validate file type
                if (!allowedFileTypes.includes(file.type)) {
                    errorMessage.textContent = 'Please upload a valid image file (JPG, PNG, GIF).';
                    errorMessage.style.display = 'block';
                    return;
                }

                // Validate file size
                if (file.size > maxFileSize) {
                    errorMessage.textContent = 'File size should not exceed 5MB.';
                    errorMessage.style.display = 'block';
                    return;
                }

                errorMessage.style.display = 'none';  // Clear error message if validation passes

                reader.onload = function(e) {
                    const previewContainer = document.getElementById('imagePreviewContainer');
                    previewContainer.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = "Room Image Preview";
                    img.style.maxWidth = '100%'; 
                    img.style.maxHeight = '100%';
                    img.style.objectFit = 'cover'; 
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file); 
            }
        });

        // Combine validation and AJAX submission
        document.getElementById('addRoomForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = this;
            const formData = new FormData(form);  // Create FormData object to send the form data
            
            const errorMessage = document.getElementById('signup-error-message');
            errorMessage.style.display = 'none';  // Hide previous error message
            let isValid = true; // Track overall form validity
            let errorMessages = []; // Collect error messages

            // List of required fields with corresponding labels for better feedback
            const requiredFields = [
                { id: 'roomNumberInput', label: 'Room Number' },
                { id: 'roomTypeInput', label: 'Room Type' },
                { id: 'roomSizeInput', label: 'Room Size' },
                { id: 'amenitiesInput', label: 'Amenities' },
                { id: 'utilitiesInput', label: 'Utilities' },
                { id: 'rentalRatesInput', label: 'Rental Rates' },
                { id: 'depositRateInput', label: 'Deposit Rate' }
            ];

            // Validate required fields
            requiredFields.forEach(function(field) {
                const input = document.getElementById(field.id);
                if (!input.value.trim()) {
                    isValid = false;
                    errorMessages.push(`Please fill in the ${field.label}.`);
                }
            });

            if (!isValid) {
                // Display error messages
                errorMessage.innerText = errorMessages.join(' ');
                errorMessage.style.display = 'block';
                return; // Stop form submission
            }

            // Create an AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'add-room.php', true);  // Send data to the PHP script

            // Handle the response when it's received
            xhr.onload = function () {
                const response = JSON.parse(xhr.responseText); // Parse the JSON response
                
                // Display success or error messages
                if (response.errors) {
                    // Display the errors
                    errorMessage.innerHTML = response.errors.join('<br>');
                    errorMessage.style.display = 'block';
                } else if (response.success) {
                    alert(response.success);
                    setTimeout(function() {
                        var iframe = window.parent.document.getElementById('content-iframe');
                        if (iframe) {
                            iframe.src = 'rooms.php';  // Load rooms.php inside the iframe
                        }
                    }, 2000); 
                }
            };

            xhr.send(formData);  // Send the form data via AJAX
        });
    </script>

</body>
</html>