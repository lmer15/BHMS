<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section id="wrapper">
        <div id="room">
        <?php
        // Include the RoomFetcher class
        include_once 'CLASESS/RoomFetcher.php';

        // Fetch rooms using the fetchRooms method
        $rooms = RoomFetcher::fetchRooms();

        // Loop through the rooms and display each room's details
        foreach ($rooms as $room) {
            // Fetch additional details for each room
            $roomDetails = RoomFetcher::fetchRoomDetails($room['room_number']); 
            echo '<div class="each-room">';
            echo '<span class="room-number">' . htmlspecialchars($room['room_number']) . '</span>';
            echo '<span class="price">Price: ₱' . number_format($room['rental_rates'], 2) . ' monthly rental</span>';
            echo '<div class="room-image-wrapper">';
            echo '<img src="ADD/uploads/' . htmlspecialchars($room['room_image']) . '" alt="' . htmlspecialchars($room['room_number']) . '" class="room-image">';
            
            echo '<div class="room-details">';
            echo '<p><strong>Room Type:</strong> ' . htmlspecialchars($roomDetails['room_type']) . '</p>';
            echo '<p><strong>Room Size:</strong> ' . htmlspecialchars($roomDetails['room_size']) . ' m²</p>';
            echo '<p><strong>Amenities:</strong> ' . htmlspecialchars($roomDetails['room_aminities']) . '</p>';
            echo '<p><strong>Utilities:</strong> ' . htmlspecialchars($roomDetails['room_utilities']) . '</p>';
            echo '<p><strong>Payment Frequency:</strong> ' . htmlspecialchars($roomDetails['room_payfre']) . '</p>';
            echo '<p><strong>Deposit Rate:</strong> ₱' . number_format($roomDetails['room_deporate'], 2) . '</p>';
            echo '</div>'; 
            echo '</div>'; 
            
            echo '<div class="btn">';
            echo '<a href="register-form.php?room_number=' . urlencode($room['room_number']) . '"> <i class="bx bxs-bookmark-star"></i>BOOK</a>';
            echo '</div>';
            echo '</div>';
        }
        ?>

            <div class="add">
                <a href="ADD/addRoom.php"><i class='bx bx-plus icon-add'></i></a>
            </div>
        </div>
    </section>
</body>
</html>
