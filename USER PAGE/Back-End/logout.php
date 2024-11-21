<?php
// Start the session to access session variables
session_start();

// Destroy session
session_unset();
session_destroy();

// Clear the remember_username cookie by setting its expiration time in the past
setcookie("remember_username", "", time() - 3600, "/");  // Expire the cookie

// Redirect to the login page (or wherever you want)
header("Location: ../login.php");
exit();
?>
