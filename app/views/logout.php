<?php
// Start the session
session_start();

// Destroy the session to log the user out
session_unset();   // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page with a success message
header("Location: /Hire_Hub/index.php?success=You have been logged out successfully.");
exit();
?>
