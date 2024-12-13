<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Profile</title>
    <script src="../../imported_links.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="room.css?v=1.0">
</head>
<body>

    <div class="wrapper">
        <!-- Room Status Filter Dropdown -->
        <div class="room-status-dropdown">
            <span>ROOM STATUS</span>
            <select id="statusFilter">
                <option value="available">Available</option>
                <option value="under-maintenance">Under Maintenance</option>
                <option value="occupied">Occupied</option>
                <option value="reserved">Reserved</option>  <!-- Added the "Reserved" option -->
            </select>
        </div>

        <!-- Room Tables -->
        <div class="guest-accounts">
            <div class="table-container available active">
                <table id="availableRooms">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Room Size</th>
                            <th>Amenities</th>
                            <th>Utilities</th>
                            <th>Rental Rates</th>
                            <th>Payment Frequency</th>
                            <th>Deposit Rate</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Available rooms will be injected here -->
                    </tbody>
                </table>
            </div>

            <div class="table-container under-maintenance">
                <table id="underMaintenanceRooms">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Room Size</th>
                            <th>Amenities</th>
                            <th>Utilities</th>
                            <th>Rental Rates</th>
                            <th>Payment Frequency</th>
                            <th>Deposit Rate</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Under Maintenance rooms will be injected here -->
                    </tbody>
                </table>
            </div>

            <div class="table-container occupied">
                <table id="occupiedRooms">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Room Size</th>
                            <th>Amenities</th>
                            <th>Utilities</th>
                            <th>Rental Rates</th>
                            <th>Payment Frequency</th>
                            <th>Deposit Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Occupied rooms will be injected here -->
                    </tbody>
                </table>
            </div>

            <div class="table-container reserved">
                <table id="reservedRooms">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Room Size</th>
                            <th>Amenities</th>
                            <th>Utilities</th>
                            <th>Rental Rates</th>
                            <th>Payment Frequency</th>
                            <th>Deposit Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Reserved rooms will be injected here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const statusFilter = document.getElementById('statusFilter');
        const allTables = document.querySelectorAll('.table-container');

        // Function to fetch rooms based on status
        function fetchRooms(status) {
            fetch(`fetch_rooms.php?status=${status}`)
                .then(response => response.json())
                .then(data => {
                    // Reset all tables
                    document.getElementById('availableRooms').querySelector('tbody').innerHTML = '';
                    document.getElementById('underMaintenanceRooms').querySelector('tbody').innerHTML = '';
                    document.getElementById('occupiedRooms').querySelector('tbody').innerHTML = '';
                    document.getElementById('reservedRooms').querySelector('tbody').innerHTML = '';

                    // Fill rooms based on status
                    data.forEach(room => {
                        const row = `<tr>
                            <td>${room.room_number}</td>
                            <td>${room.room_type}</td>
                            <td>${room.room_size}</td>
                            <td>${room.room_aminities}</td>
                            <td>${room.room_utilities}</td>
                            <td>${room.rental_rates}</td>
                            <td>${room.room_payfre}</td>
                            <td>${room.room_deporate}</td>
                            <td>${room.room_status}</td>
                            <td>
                                ${room.room_status === 'available' ? 
                                    `<i class='bx bxs-cog' onclick="updateRoomStatus('${room.room_number}', 'under-maintenance')"></i>` : ''}
                                ${room.room_status === 'under-maintenance' ? 
                                    `<i class='bx bxs-cog' onclick="updateRoomStatus('${room.room_number}', 'available')"></i>` : ''}
                            </td>
                        </tr>`;

                        if (room.room_status === 'available') {
                            document.getElementById('availableRooms').querySelector('tbody').innerHTML += row;
                        } else if (room.room_status === 'under-maintenance') {
                            document.getElementById('underMaintenanceRooms').querySelector('tbody').innerHTML += row;
                        } else if (room.room_status === 'occupied') {
                            document.getElementById('occupiedRooms').querySelector('tbody').innerHTML += row;
                        } else if (room.room_status === 'reserved') {
                            document.getElementById('reservedRooms').querySelector('tbody').innerHTML += row;
                        }
                    });
                })
                .catch(error => console.error('Error fetching rooms:', error));
        }

        function updateRoomStatus(roomNumber, newStatus) {
            const statusMessage = newStatus === 'available' 
                ? 'Are you sure you want to change this room status to Available?'
                : 'Are you sure you want to change this room status to Under Maintenance?';

            const userConfirmed = confirm(statusMessage);

            if (userConfirmed) {
                fetch(`update_room_status.php?room_number=${roomNumber}&new_status=${newStatus}`, {
                    method: 'POST',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        fetchRooms(statusFilter.value); 
                    } else {
                        alert('Error updating room status: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                console.log('Status change canceled');
            }
        }

        statusFilter.addEventListener('change', function() {
            const selectedStatus = statusFilter.value;
            fetchRooms(selectedStatus);

            allTables.forEach(function(table) {
                if (table.classList.contains(selectedStatus)) {
                    table.classList.add('active');
                } else {
                    table.classList.remove('active');
                }
            });
        });

        // Load available rooms on page load
        window.onload = () => {
            statusFilter.value = 'available';
            statusFilter.dispatchEvent(new Event('change'));
        };

    </script>

</body>
</html>
