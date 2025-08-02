<?php
    include '../db.php';
    session_name('admin_session');
    session_start();
    if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
        header("Location: login.php");
        exit();
    }


    $adminName = $_SESSION['admin_name'] ?? 'Admin'; // Default if name is missing
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        
        <!-- Left side: Logo + Title -->
        <div style="display:flex;align-items:center;">
            <img src="../assets/logo.png" alt="Blog App Logo" style="height:50px;margin-right:10px;">
            <h1 style="margin:0;">Admin Dashboard</h1>
        </div>
        
        <!-- Right side: Welcome + Logout -->
        <div>
            <span class="btn btn-default disabled">Welcome, <?php echo htmlspecialchars($adminName); ?></span>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <hr>
    
    <a href="users.php" class="btn btn-info">View Users Data</a>
    <a href="posts.php" class="btn btn-primary">View Posts Data</a>
</div>


</body>
</html>
