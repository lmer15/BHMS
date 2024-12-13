<?php

include_once '../../DATABASE/dbConnector.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the status from the query parameter (if provided)
$status = isset($_GET['status']) ? $_GET['status'] : '';

// SQL query to fetch maintenance requests with tenant names (join with tenant_details table)
$sql = "
    SELECT 
        mr.id, 
        mr.tenant_id, 
        mr.date_requested, 
        mr.item_name, 
        mr.item_desc, 
        mr.status, 
        td.fname, 
        td.lname
    FROM maintenance_requests mr
    LEFT JOIN tenant_details td ON mr.tenant_id = td.tc_id
";

if ($status) {
    $sql .= " WHERE mr.status = '$status'";  // Dynamically append WHERE clause for status
}

$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Create an array to store the results
    $requests = array();
    
    // Fetch the results into the array
    while ($row = $result->fetch_assoc()) {
        // Combine the first and last name
        $row['tenant_name'] = $row['fname'] . " " . $row['lname'];
        
        // Remove fname and lname from the result to avoid redundancy
        unset($row['fname'], $row['lname']);
        
        $requests[] = $row;
    }
    
    // Set the response type to JSON
    header('Content-Type: application/json');
    
    // Output the JSON data
    echo json_encode($requests);
} else {
    // If no results, send an empty array
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>
