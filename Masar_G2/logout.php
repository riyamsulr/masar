<?php
session_start();

// Destroy all session variables
session_unset();

// Completely destroy the session
session_destroy();

// Redirect to homepage
header("Location: homepage.php");
exit();
?>


