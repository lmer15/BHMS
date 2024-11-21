<?php
// Start session to get the user's session ID
session_start();

include('../DATABASE/db_connection.php');

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to retrieve the user's data and profile image
    $query = "
        SELECT u.first_name, u.last_name, u.email, t.t_img 
        FROM users u
        LEFT JOIN tenant_profile t ON u.user_id = t.tc_ID
        WHERE u.user_id = '$user_id'
    ";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        $user = mysqli_fetch_assoc($result);

        // Store user data in session for later use
        $_SESSION['fname'] = $user['first_name'];
        $_SESSION['lname'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_picture'] = $user['t_img'] ? $user['t_img'] : 'uploads/default.png'; 
    } else {
        // Handle error if the query fails
        echo "Error retrieving user data: " . mysqli_error($conn);
    }
} else {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
