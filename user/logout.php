<?php
// Start the session
session_start();

// Destroy all session variables
session_unset(); 

// Destroy the session itself
session_destroy(); 


// Redirect to login page
header("Location: ../login/login.php");  // Adjust the path if necessary
exit;
?>
