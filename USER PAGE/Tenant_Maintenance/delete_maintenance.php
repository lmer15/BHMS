<?php
include_once '../../DATABASE/dbConnector.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM maintenance_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Maintenance request deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete the maintenance request.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare delete statement.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No ID provided.']);
    }
}
?>
