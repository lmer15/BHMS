<?php
// CLASESS/RoomFetcher.php

// Include the database connection file
include_once '../DATABASE/dbConnector.php'; // Adjusted the path to dbConnector.php

class RoomFetcher {
    public static function fetchRooms() {
        global $conn; 

        $sql = "SELECT room_number, room_image, rental_rates FROM room WHERE room_status = 'available'";
        $result = $conn->query($sql);

        $rooms = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
        }

        return $rooms; 
    }

    // New method to fetch detailed information for a room by its room_number
    public static function fetchRoomDetails($roomNumber) {
        global $conn;

        // SQL to fetch room details
        $sql = "SELECT room_type, room_size, room_aminities, room_utilities, room_payfre, room_deporate FROM room WHERE room_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $roomNumber);  // Bind room number to SQL query
        $stmt->execute();
        $result = $stmt->get_result();

        // Return the details of the room if available
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return [];  // Return empty if no details found
    }
}
 
?>
