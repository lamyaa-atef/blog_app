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

// Fetch post data for viewing
$postID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM posts WHERE id = $postID";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $postData = mysqli_fetch_assoc($result);
} else {
    die("<p style='color: darkred; 
                font-weight: bold; 
                background-color: #f8d7da; 
                padding: 10px; 
                border-left: 5px solid red; 
                border-radius: 5px;'><span style='color: red;'>Error:</span> Post not found.</p>");
}

mysqli_close($link);
$adminName = $_SESSION['admin_name'] ?? 'Admin'; // Default if name is missing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .post-info {
            margin-top: 25px;
        }
        .post-info div {
            margin-bottom: 20px;
        }
        .post-info label {
            font-weight: bold;
            width: 150px;
            margin-bottom: 15px;
            display: block;
        }
        .post-info span {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="clearfix">
            <h1 style="display:inline-block;margin:0;">View Post Record</h1>
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
        
        <div class="post-info">
            <div>
                <label>Title:</label>
                <span><?php echo htmlspecialchars($postData['title']); ?></span>
            </div>
            <div>
                <label>Content:</label>
                <span><?php echo nl2br(htmlspecialchars($postData['content'])); ?></span>
            </div>
            <div>
                <label>Author:</label>
                <span><?php echo htmlspecialchars($postData['author']); ?></span>
            </div>
            <div>
                <label>Created At:</label>
                <span><?php echo htmlspecialchars($postData['created_at']); ?></span>
            </div>
        </div>

        <a href="./posts.php" class="btn btn-primary">Back</a>
    </div>
</body>
</html>

