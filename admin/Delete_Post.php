<?php
include '../db.php';
session_name('admin_session');
session_start();
if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$link->query("DELETE FROM posts WHERE id=$id");
header("Location: posts.php");
exit();
?>
