<?php
include '../db.php';
session_name('client_session');
session_start();
if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}
$id = $_GET['id'];
$link->query("DELETE FROM posts WHERE id=$id");
header("Location: index.php");
exit();
?>
