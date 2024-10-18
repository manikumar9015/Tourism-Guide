<?php
// Start the session
session_start();

// Debug: Print session data
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Debug: Print session data after destruction
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Redirect to the login page or any other desired page
header("Location: login1.php"); // Replace with the appropriate URL
exit;
?>
