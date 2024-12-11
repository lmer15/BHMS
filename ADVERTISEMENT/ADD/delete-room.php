<?php
session_start();
include_once '../../DATABASE/dbConnector.php';

if (isset($_GET['room_number'])) {
    $room_number = $_GET['room_number'];

    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        $query = "DELETE FROM room WHERE room_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $room_number);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Room deleted successfully!');
                    var iframe = window.parent.document.getElementById('content-iframe');
                    if (iframe) {
                        iframe.src = 'rooms.php';  
                    }
                  </script>";
        } else {
            
            echo "<script>
                    alert('Failed to delete room.');
                  </script>";
        }
        $stmt->close();
    } else {
          echo "<script>
                if (confirm('Are you sure you want to delete this room?')) {
                    window.location.href = 'delete-room.php?room_number=" . urlencode($room_number) . "&confirm=yes';
                } else {
                    window.location.href = '../rooms.php';
                }
              </script>";
    }
} else {
    header("Location: ../rooms.php?error=Room not found");
}

$conn->close();
exit();
?>
