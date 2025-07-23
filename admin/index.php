<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'blog_app';
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            padding: 30px;
        }
        .dashboard-btn {
            width: 200px;
            margin: 10px;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container text-center">
    <h1>Admin Dashboard</h1>
    <br><br>
    <button onclick="window.location.href='users.php'" class="btn btn-primary dashboard-btn">View Users Data</button>
    <button onclick="window.location.href='posts.php'" class="btn btn-success dashboard-btn">View Posts Data</button>
</div>
</body>
</html>
