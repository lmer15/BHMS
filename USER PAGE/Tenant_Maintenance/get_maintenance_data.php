<?php
include_once '../../DATABASE/dbConnector.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM maintenance_requests";
if ($result = $conn->query($query)) {

    $maintenanceData = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $maintenanceData[] = [
                'id' => $row['id'],  
                'dateRequested' => date('Y-m-d H:i:s', strtotime($row['date_requested'])), 
                'itemRequested' => htmlspecialchars($row['item_name']), 
                'details' => htmlspecialchars($row['item_desc']),      
                'status' => $row['status'],
            ];
        }
    }
    $result->free();
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($maintenanceData);
} else {
    $conn->close();
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch maintenance requests. Please try again later.'
    ]);
}
?>
