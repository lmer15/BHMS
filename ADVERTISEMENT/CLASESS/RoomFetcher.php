<?php
include_once '../DATABASE/dbConnector.php';

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

    public static function fetchRoomDetails($roomNumber) {
        global $conn;

        $sql = "SELECT room_type, room_size, room_aminities, room_utilities, room_payfre, room_deporate FROM room WHERE room_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $roomNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return []; 
    }
}
 
?>
