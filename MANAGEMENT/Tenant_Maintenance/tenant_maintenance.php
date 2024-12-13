<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="tenant_maintenance.css">
</head>
<body>
    <div class="wrapper">
        
        <div class="navigator">
            <a href="#" id="dashboard">Dashboard</a>
        </div>
        
        <!-- Dashboard Section -->
        <div class="dashboard">
            <div class="nav">
                <div class="nav-con" id="pending">
                    <h1>45</h1>
                    <span>Pending</span>
                </div>

                <div class="nav-con" id="done">
                    <h1>100</h1>
                    <span>Done</span>
                </div>

                <div class="nav-con" id="ongoing">
                    <h1>25</h1>
                    <span>Ongoing</span>
                </div>

                <div class="nav-con" id="declined">
                    <h1>25</h1>
                    <span>Declined</span>
                </div>
            </div>

            <!-- Maintenance Table -->
            <div class="maintenance-table">
                <table>
                    <thead>
                        <tr>
                            <th>Date Requested</th>
                            <th>Tenant</th>
                            <th>Room No.</th>
                            <th>Maintenance Requested</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="maintenance-table-body">
                        <!-- Dynamic rows will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Send Maintenance Request Section -->
        <div class="send_decline_reason" style="display: none;">
            <i id="back-arrow" class="bx bx-arrow-back icon"></i>
            <form action="post">
                <h1>Declined Reason</h1>
                <label for="Item-Name">Tenant Name:</label>
                <p id="Tenant-Name">Elmer Rapon</p>
                <label for="Item-Name">Room Number:</label>
                <p id="Room Number">RM201</p>
                <label for="Item-Name">Maintenance Requested:</label>
                <p id="Tenant-Name">Broken Ceiling</p>
                <label for="Item-Desc">Add Reason:</label>
                <textarea id="Item-Desc" required></textarea>
                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    const dashMaintenance = document.getElementById("dashboard");
    const backArrow = document.getElementById("back-arrow");

    const classDash = document.querySelector(".dashboard");
    const classAddReq = document.querySelector(".send_decline_reason");

    const maintenanceTableBody = document.getElementById("maintenance-table-body");

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
        links.forEach(item => item.classList.remove('active'));
        link.classList.add('active'); // Add active class to clicked link
    }

    // Function to fetch and render maintenance requests
    function renderMaintenanceRequests(filteredRequests) {
        const maintenanceTableBody = document.getElementById("maintenance-table-body");

        maintenanceTableBody.innerHTML = ""; // Clear existing rows

        filteredRequests.forEach(request => {
            // Create a row for each maintenance request
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${request.date_requested}</td>
                <td>${request.tenant_name}</td>
                <td>RM${request.tenant_id}</td>
                <td>${request.item_name}</td>
                <td>${request.item_desc}</td>
                <td>${request.status}</td>
                <td>
                    ${request.status === 'Pending' ? ` 
                        <button class="approve-btn" data-id="${request.id}" data-item-name="${request.item_name}" data-tenant-name="${request.tenant_name}" data-room-number="RM${request.tenant_id}">Approve</button>
                        <button class="decline-btn" data-id="${request.id}" data-item-name="${request.item_name}" data-tenant-name="${request.tenant_name}" data-room-number="RM${request.tenant_id}">Decline</button>
                    ` : ''}
                    ${request.status === 'Ongoing' ? `
                        <button class="done-btn" data-id="${request.id}" data-item-name="${request.item_name}">Mark as Done</button>
                    ` : ''}
                </td>
            `;
            maintenanceTableBody.appendChild(row);
        });

        // Add event listeners for Approve, Decline, and Done buttons after rendering
        document.querySelectorAll('.approve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = button.getAttribute('data-id');
                const itemName = button.getAttribute('data-item-name');
                if (confirm(`Are you sure you want to approve the request for "${itemName}"?`)) {
                    updateRequestStatus(requestId, 'Ongoing', itemName); // Update the request status to Ongoing
                }
            });
        });

        document.querySelectorAll('.decline-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = button.getAttribute('data-id');
                const itemName = button.getAttribute('data-item-name');
                const tenantName = button.getAttribute('data-tenant-name');
                const roomNumber = button.getAttribute('data-room-number');
                if (confirm(`Are you sure you want to decline the request for "${itemName}"?`)) {
                    showDeclineForm(requestId, itemName, tenantName, roomNumber);
                }
            });
        });

        document.querySelectorAll('.done-btn').forEach(button => {
            button.addEventListener('click', function() {
                const requestId = button.getAttribute('data-id');
                const itemName = button.getAttribute('data-item-name');
                if (confirm(`Are you sure you want to mark the request for "${itemName}" as done?`)) {
                    updateRequestStatus(requestId, 'Done', itemName); // Update the request status to Done
                }
            });
        });
    }

    function updateRequestStatus(requestId, newStatus, itemName) {
        console.log('Request ID:', requestId, 'Status:', newStatus, 'Item Name:', itemName);

        fetch('update_request_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: requestId, status: newStatus, item_name: itemName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchMaintenanceRequests(); 
            } else {
                console.error("Failed to update request status:", data.message);
            }
        })
        .catch(error => console.error("Error updating request status:", error));
    }

    function showDeclineForm(requestId, itemName, tenantName, roomNumber) {
        showSection(classAddReq);
        hideSection(classDash);

        document.getElementById('Tenant-Name').textContent = tenantName; 
        document.getElementById('Room Number').textContent = roomNumber; 
        document.getElementById('Item-Desc').setAttribute('data-id', requestId); 
        document.getElementById('Item-Desc').setAttribute('data-item-name', itemName); 
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        const requestId = document.getElementById('Item-Desc').getAttribute('data-id');
        const reason = document.getElementById('Item-Desc').value;
        const itemName = document.getElementById('Item-Desc').getAttribute('data-item-name');
        const tenantName = document.getElementById('Tenant-Name').textContent;

        // Send the reason and other necessary info to the server
        fetch('save_decline_reason.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: requestId, reason: reason, item_name: itemName, tenant_name: tenantName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchMaintenanceRequests(); 
                hideSection(classAddReq);
                showSection(classDash);
            } else {
                console.error("Failed to decline the request.");
            }
        })
        .catch(error => console.error("Error declining the request:", error));
    });

    // Fetch the actual counts from the database
    function fetchRequestCounts() {
        fetch('get_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    document.getElementById("pending").querySelector("h1").textContent = data.pending;
                    document.getElementById("done").querySelector("h1").textContent = data.done;
                    document.getElementById("ongoing").querySelector("h1").textContent = data.ongoing;
                    document.getElementById("declined").querySelector("h1").textContent = data.declined;
                }
            })
            .catch(error => console.error("Error fetching request counts:", error));
    }

    function fetchMaintenanceRequests(status = '') {
        let url = 'get_request.php';
        if (status) {
            url += `?status=${status}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderMaintenanceRequests(data);
            })
            .catch(error => console.error("Error fetching maintenance requests:", error));
    }

    fetchRequestCounts();
    fetchMaintenanceRequests();

    const navConItems = document.querySelectorAll(".nav-con");
    navConItems.forEach(item => {
        item.addEventListener("click", function() {
            const status = item.querySelector("span").textContent.trim();
            fetchMaintenanceRequests(status);
            setActiveLink(item);
        });
    });

    dashMaintenance.addEventListener("click", function(event) {
        event.preventDefault();
        showSection(classDash);
        hideSection(classAddReq);
        setActiveLink(dashMaintenance);
    });

    backArrow.addEventListener("click", function(event) {
        event.preventDefault();
        showSection(classDash);
        hideSection(classAddReq);
        setActiveLink(dashMaintenance);
    });
});
</script>

</body>
</html>
