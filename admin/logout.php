<?php
session_name('admin_session');
session_start();
if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>