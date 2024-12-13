<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Services</title>
    <script src="../../imported_links.js" defer></script> 
    <link rel="stylesheet" href="styleSer.css">
</head>
<body>
    <div class="wrapper">
        <form id="servicesForm">
            <label for="aminities">Title</label>
            <input type="text" name="title" id="aminities" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>

            <button type="submit">Submit</button>
        </form>
        
        <!-- Added the response message container -->
        <div id="responseMessage" style="display:none;"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('servicesForm').addEventListener('submit', function(event) {
                event.preventDefault(); 

                const form = this;
                const formData = new FormData(form);
                const errorMessage = document.getElementById('responseMessage'); // Get the error message container

                // Ensure errorMessage exists before trying to modify it
                if (errorMessage) {
                    errorMessage.style.display = 'none'; 
                }

                let isValid = true; 
                let errorMessages = [];

                const requiredFields = [
                    { id: 'aminities', label: 'Title' },
                    { id: 'description', label: 'Description' }
                ];

                requiredFields.forEach(function(field) {
                    const input = document.getElementById(field.id);
                    if (!input || !input.value.trim()) {
                        isValid = false;
                        errorMessages.push(`Please fill in the ${field.label}.`);
                    }
                });

                if (!isValid) {
                    if (errorMessage) {
                        errorMessage.innerHTML = errorMessages.join('<br>');
                        errorMessage.classList.add('error');
                        errorMessage.classList.remove('success');
                        errorMessage.style.display = 'block';
                    }
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'add-services.php', true);

                xhr.onload = function () {
                    const response = JSON.parse(xhr.responseText); 

                    if (response.errors) {
                        if (errorMessage) {
                            errorMessage.innerHTML = response.errors.join('<br>');
                            errorMessage.classList.add('error');
                            errorMessage.classList.remove('success');
                            errorMessage.style.display = 'block';
                        }
                    } else if (response.success) {
                        alert(response.success);
                        setTimeout(function() {
                            const iframe = window.parent.document.getElementById('content-iframe');
                            if (iframe) {
                                iframe.src = 'services.php'; 
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
