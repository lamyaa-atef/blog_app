<?php
session_name('client_session');
session_start();
if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}


// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to index page
header("Location: index.php");
exit;
?>
