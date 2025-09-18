<?php
include '../db.php';

if(isset($_POST['username'])){
    $username = trim($_POST['username']);

    $stmt = $link->prepare("SELECT Name, Email, Gender, Role, mail_status FROM users WHERE Name = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        echo json_encode(["success" => true, "user" => $row]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}
?>
