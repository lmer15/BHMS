<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="maintenance.css?v=1.0">
</head>
<body>
    <div class="wrapper">

        <div class="navigator">
            <a href="#" id="dashboard">Dashboard</a>
            <a href="#" id="send-request">Send Request</a>
        </div>

        <div class="dashboard">
            <div class="nav">
                <div class="nav-con" id="pending">
                    <h1>0</h1>
                    <span>Pending</span>
                </div>

                <div class="nav-con" id="done">
                    <h1>0</h1>
                    <span>Done</span>
                </div>

                <div class="nav-con" id="ongoing">
                    <h1>0</h1>
                    <span>Ongoing</span>
                </div>

                <div class="nav-con" id="declined">
                    <h1>0</h1>
                    <span>Declined</span>
                </div>
            </div>

            <div class="maintenance-table">
                <div class="maintenance-table-header">
                    <div class="maintenance-header-item">Date Requested</div>
                    <div class="maintenance-header-item">Maintenance Requested</div>
                    <div class="maintenance-header-item">Details</div>
                    <div class="maintenance-header-item">Status</div>
                    <div class="maintenance-header-item">Action</div> 
                </div>
                <div id="maintenance-table-body">
                </div>
            </div>
            
        </div>

        <div class="send_maintenance_request" style="display: none;">
            <form id="maintenance-form">
                <h1>Request Form</h1>
                <label for="Item-Name">Item to be Fixed:</label>
                <input type="text" id="Item-Name" required>

                <label for="Item-Desc">Add Brief Description:</label>
                <textarea id="Item-Desc" required></textarea>
                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>

    <script src="maintenancejs.js"></script>

    <script>
        // Fetch all maintenance requests when the page loads
        document.addEventListener("DOMContentLoaded", function() {
            let maintenanceData = []; // Store maintenance data
    
            function updateNavCounts() {
                const pendingCount = maintenanceData.filter(item => item.status === 'Pending').length;
                const doneCount = maintenanceData.filter(item => item.status === 'Done').length;
                const ongoingCount = maintenanceData.filter(item => item.status === 'Ongoing').length;
                const declinedCount = maintenanceData.filter(item => item.status === 'Declined').length;
    
                document.getElementById('pending').querySelector('h1').textContent = pendingCount;
                document.getElementById('done').querySelector('h1').textContent = doneCount;
                document.getElementById('ongoing').querySelector('h1').textContent = ongoingCount;
                document.getElementById('declined').querySelector('h1').textContent = declinedCount;
            }
    
            function populateTable(filterStatus = '') {
                const tableBody = document.getElementById('maintenance-table-body');
                tableBody.innerHTML = '';  // Clear the table before repopulating

                let filteredData = maintenanceData;
                if (filterStatus) {
                    filteredData = maintenanceData.filter(item => item.status === filterStatus);
                }

                // Determine if the "Pending" status is active
                const isPendingActive = filterStatus === 'Pending' || filterStatus === '';

                filteredData.forEach(item => {
                    const row = document.createElement('div');
                    row.classList.add('maintenance-table-row');
                    row.setAttribute('data-id', item.id);  // Set the item ID as a data attribute for reference

                    const deleteIcon = document.createElement('i');
                    deleteIcon.classList.add('bx', 'bx-trash', 'delete-icon'); // Add classes for styling

                    deleteIcon.addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete this maintenance request?')) {
                            const itemId = row.getAttribute('data-id'); // Get ID from the data-id attribute
                            deleteMaintenanceRequest(itemId, row);  // Call function to delete the item
                        }
                    });

                    row.innerHTML = `
                        <div class="maintenance-item">${item.dateRequested}</div>
                        <div class="maintenance-item">${item.itemRequested}</div>
                        <div class="maintenance-item">${item.details}</div>
                        <div class="maintenance-item">${item.status}</div>
                    `;
                    
                    if (isPendingActive) {
                        const buttonCell = document.createElement('div');
                        buttonCell.classList.add('maintenance-item', 'button-cell');
                        buttonCell.appendChild(deleteIcon);
                        row.appendChild(buttonCell);
                    }
                    tableBody.appendChild(row);
                });
                const actionColumnHeader = document.querySelector('.maintenance-table-header .maintenance-header-item:nth-child(5)');
                if (actionColumnHeader) {
                    actionColumnHeader.style.display = isPendingActive ? 'block' : 'none';
                }
            }


            // Function to delete the maintenance request from the database and update the UI
            function deleteMaintenanceRequest(itemId, row) {
                fetch('delete_maintenance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${encodeURIComponent(itemId)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message); // Show success message
                        row.remove();  // Remove the row from the UI
                        updateNavCounts(); // Update the status counts
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Network or server error:', error);
                    alert('There was an error deleting the request. Please try again later.');
                });
            }

    
            fetch('get_maintenance_data.php') 
                .then(response => response.json())
                .then(data => {
                    maintenanceData = data; 
                    updateNavCounts(); 
                    populateTable(); 
                })
                .catch(error => {
                    console.error('Error fetching maintenance data:', error);
                });
    
            document.getElementById('pending').addEventListener('click', function() {
                populateTable('Pending');
            });
    
            document.getElementById('done').addEventListener('click', function() {
                populateTable('Done');
            });
    
            document.getElementById('ongoing').addEventListener('click', function() {
                populateTable('Ongoing');
            });
    
            document.getElementById('declined').addEventListener('click', function() {
                populateTable('Declined');
            });
    
            document.querySelector('form').addEventListener('submit', function(event) {
                event.preventDefault();
    
                let itemName = document.getElementById('Item-Name').value;
                let itemDesc = document.getElementById('Item-Desc').value;
    
                fetch('submit_maintenance.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `item_name=${encodeURIComponent(itemName)}&item_desc=${encodeURIComponent(itemDesc)}`
                })
                .then(response => response.json()) 
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        document.querySelector('form').reset();
                        window.location.reload();
                    } else {
                        alert(`Error: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Network or server error:', error);
                    alert('There was an error submitting your request. Please try again later.');
                });
            });
        });
    </script>
       
</body>
</html>
