<?php
include '../db.php';
$id = $_GET['id'];
$link->query("DELETE FROM posts WHERE id=$id");
header("Location: index.php");
exit();
?>
