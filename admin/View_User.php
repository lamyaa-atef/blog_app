<?php
include '../db.php';
session_name('admin_session');
session_start();

// ✅ Only admins can view this page
if (!isset($_SESSION['admin_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch user data
$userID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM users WHERE ID = $userID";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);
} else {
    die("<p style='color: darkred; font-weight: bold; background-color: #f8d7da; padding: 10px; border-left: 5px solid red; border-radius: 5px;'>
        <span style='color: red;'>Error:</span> User not found.</p>");
}

mysqli_close($link);
$adminName = $_SESSION['admin_name'] ?? 'Admin'; // Default if missing

// ✅ Check if viewed user is admin
$isAdmin = (isset($userData['Role']) && $userData['Role'] === 'admin');
$pageTitle = $isAdmin ? "View Admin Record" : "View User Record";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .user-info {
            margin-top: 25px;
        }
        .user-info div {
            margin-bottom: 20px;
        }
        .user-info label {
            font-weight: bold;
            width: 150px;
            margin-bottom: 15px;
            display: block;
        }
        .user-info span {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="clearfix">
            <h1 style="display:inline-block;margin:0;"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <div class="pull-right">
                <span class="btn btn-default disabled">Welcome, <?php echo htmlspecialchars($adminName); ?></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        <hr>
        <a href="index.php" class="btn btn-default">Home</a>
        <a href="users.php" class="btn btn-info">View Users Data</a>
        <a href="posts.php" class="btn btn-primary">View Posts Data</a>
        <hr>
    </div>
    <div class="container">
        <div class="user-info">
            <div>
                <strong>Name:</strong>
                <?php echo htmlspecialchars($userData['Name']); ?>
            </div>
            <div>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($userData['Email']); ?>
            </div>
            <div>
                <strong>Gender: </strong>
                <?php echo htmlspecialchars($userData['Gender'] == 'Male' ? 'Male' : 'Female'); ?>
            </div>
            <div>
                <strong>Mail Status: </strong>
                <?php echo htmlspecialchars($userData['Mail_Status'] == 'yes'? 'Subscribed': 'Not Subscribed'); ?>
            </div>
        </div>
        <a href="./users.php" class="btn btn-primary">Back</a>
    </div>
</body>
</html>
