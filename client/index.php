<?php
include '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="text-primary">All Posts</h2>
    
    <div class="btn-group" role="group" aria-label="Navigation">
        <a href="create.php" class="btn btn-success">Add New Post</a>
        <a href="register.php" class="btn btn-info">Register</a>
        <a href="login.php" class="btn btn-warning">Login</a>
    </div>

    <hr>

    <?php
    $result = $link->query("SELECT * FROM posts ORDER BY created_at DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='panel panel-default'>";
            echo "<div class='panel-heading'><h3 class='panel-title'>{$row['title']}</h3></div>";
            echo "<div class='panel-body'>{$row['content']}</div>";
            echo "<div class='panel-footer'>";
            echo "<a href='edit.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a> ";
            echo "<a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this post?\")'>Delete</a>";
            echo "</div></div>";
        }
    } else {
        echo "<div class='alert alert-info'>No posts found.</div>";
    }
    ?>
</div>
</body>
</html>
