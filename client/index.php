<?php
    include '../db.php';
    session_name('client_session');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog App</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <div class="header-flex">
        <div class="header-flex"> 
            <img src="../assets/logo.png" alt="Blog App Logo" style="height:50px; margin-right:10px;">
            <h1 style="margin:0;">Blog App</h1>
        </div>
        <div>
            <?php if (isset($_SESSION['user_name'])): ?>
                <a href="profile.php" class="btn btn-info">
                    Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>
                </a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php else: ?>
                <a href="register.php" class="btn btn-info">Register</a>
                <a href="login.php" class="btn btn-warning">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <hr>
</div>

<div class="container">
    <a href="create.php" class="btn btn-success">Add Post</a><br><br>
    <?php
    $result = $link->query("SELECT * FROM posts ORDER BY created_at DESC");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='panel panel-default'>";
            echo "<div class='panel-heading'><h3 class='panel-title'>" . htmlspecialchars($row['title']) . "</h3></div>";
            echo "<div class='panel-body'>" . nl2br(htmlspecialchars($row['content'])) . "</div>";
            echo "<div class='panel-footer'>";
            echo "<small>Author: " . htmlspecialchars($row['author']) . "</small><br>";
            echo "<small>Created At: " . htmlspecialchars($row['created_at']) . "</small>";

            // Show edit/delete only if current user is the post author
            if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === $row['author']) {
                echo "<br><br>";
                echo "<a href='edit.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a> ";
                echo "<a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this post?\")'>Delete</a>";
            }

            echo "</div></div>";
        }
    } else {
        echo "<div class='alert alert-info'>No posts found.</div>";
    }
    ?>
</div>
</body>
</html>
