<?php
// pages/logout.php
session_start();

// Unset all session variables related to user authentication
unset($_SESSION['user_id']);
unset($_SESSION['username']);

// We do not destroy the entire session (session_destroy()) because the user might
// have a guest cart or other non-auth related session data in a more complex setup,
// but unsetting auth variables logs them out effectively.

// Redirect back to login page
header("Location: login.php");
exit();
?>
