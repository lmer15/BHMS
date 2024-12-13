<?php

include_once '../../DATABASE/dbConnector.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get counts for each status
$sql = "
    SELECT 
        COUNT(CASE WHEN status = 'Pending' THEN 1 END) AS pending,
        COUNT(CASE WHEN status = 'Done' THEN 1 END) AS done,
        COUNT(CASE WHEN status = 'Ongoing' THEN 1 END) AS ongoing,
        COUNT(CASE WHEN status = 'Declined' THEN 1 END) AS declined
    FROM maintenance_requests
";

$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch the counts into an associative array
    $counts = $result->fetch_assoc();
    
    // Set the response type to JSON
    header('Content-Type: application/json');
    
    // Output the counts as JSON
    echo json_encode($counts);
} else {
    // If no counts found, send default zero values
    echo json_encode([
        'pending' => 0,
        'done' => 0,
        'ongoing' => 0,
        'declined' => 0
    ]);
}

// Close the connection
$conn->close();
?>
