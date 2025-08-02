<?php
include '../db.php';
session_name('client_session');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

// Fetch user info from the database
$stmt = $link->prepare("SELECT ID, Name, Email, Gender, Mail_Status FROM users WHERE Email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch posts created by this user
$postStmt = $link->prepare("SELECT id, title, content, created_at FROM posts WHERE author = ? ORDER BY created_at DESC");
$postStmt->bind_param("s", $userEmail);
$postStmt->execute();
$userPosts = $postStmt->get_result();
$postStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .user-info { margin-top: 25px; }
        .user-info div { margin-bottom: 20px; }
        .user-info label { font-weight: bold; width: 150px; margin-bottom: 15px; display: block; }
        .post-card { margin-top: 20px; }
    </style>
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <div class="header-flex clearfix">
        <h1 style="display:inline-block;margin:0;">Profile</h1>
        <div class="pull-right">
            <a href="profile.php" class="btn btn-info">
                Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <hr>
</div>

<div class="container">
    <div class="user-info">
        <div><strong>Name:</strong> <?= htmlspecialchars($user['Name']) ?></div>
        <div><strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?></div>
        <div><strong>Gender:</strong> <?= htmlspecialchars($user['Gender']) ?></div>
        <div><strong>Mail Status:</strong> <?= $user['Mail_Status'] == 'yes' ? 'Subscribed' : 'Not Subscribed' ?></div>
    </div>

    <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    <a href="delete_account.php" class="btn btn-danger" onclick="return confirm('⚠ Are you sure you want to delete your account? All your posts will be permanently removed!')">Delete Account</a>
    <a href="index.php" class="btn btn-default">Back to Home</a>

    <hr>
    <h2>Your Posts</h2><br>
    <a href="create.php" class="btn btn-success">Add Post</a><br>
    <?php if ($userPosts->num_rows > 0): ?>
        <?php while ($post = $userPosts->fetch_assoc()): ?>
            <div class="panel panel-default post-card">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= htmlspecialchars($post['title']) ?></h3>
                </div>
                <div class="panel-body">
                    <?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>...
                </div>
                <div class="panel-footer">
                    <small>Created At: <?= htmlspecialchars($post['created_at']) ?></small><br><br>
                    <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <br>
        <div class="alert alert-info">You haven't created any posts yet.</div>
    <?php endif; ?>
</div>
</body>
</html>
