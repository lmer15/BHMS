<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Amenities</title>
    <link rel="stylesheet" href="styleAmi.css">
</head>
<body>
    <div class="wrapper">
        <form id="amenitiesForm">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>

            <button type="submit">Submit</button>
        </form>
        <div id="responseMessage" class="message"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('amenitiesForm').addEventListener('submit', function(event) {
                event.preventDefault(); 

                const form = this;
                const formData = new FormData(form);
                const errorMessage = document.getElementById('responseMessage');

                errorMessage.style.display = 'none'; 
                let isValid = true; 
                let errorMessages = [];

                const requiredFields = [
                    { id: 'title', label: 'Title' },
                    { id: 'description', label: 'Description' }
                ];

                requiredFields.forEach(function(field) {
                    const input = document.getElementById(field.id);
                    if (!input.value.trim()) {
                        isValid = false;
                        errorMessages.push(`Please fill in the ${field.label}.`);
                    }
                });

                if (!isValid) {
                    errorMessage.innerHTML = errorMessages.join('<br>');
                    errorMessage.classList.add('error');
                    errorMessage.classList.remove('success');
                    errorMessage.style.display = 'block';
                    return;
                }
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'add-aminities.php', true);

                xhr.onload = function () {
                    const response = JSON.parse(xhr.responseText); 

                    if (response.errors) {
                        errorMessage.innerHTML = response.errors.join('<br>');
                        errorMessage.classList.add('error');
                        errorMessage.classList.remove('success');
                        errorMessage.style.display = 'block';
                    } else if (response.success) {
                        alert(response.success);
                        setTimeout(function() {
                            const iframe = window.parent.document.getElementById('content-iframe');
                            if (iframe) {
                                iframe.src = 'aminities.php'; 
                            }
                        }, 2000);
                    }
                };

                xhr.send(formData);
            });
        });


    </script>
</body>
</html>
