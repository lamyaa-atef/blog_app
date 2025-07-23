<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'blog_app';
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($link, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
    <h1>All Posts</h1>
    <a href="index.php" class="btn btn-default">Back to Admin</a>
    <a href="../client/create.php" class="btn btn-success">Add New Post</a><br><br>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-striped'>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>" . substr($row['content'], 0, 100) . "...</td>
                <td>{$row['created_at']}</td>
                <td>
                    <a href='View_Post.php?id={$row['id']}' title='View'><i class='fas fa-eye'></i></a>&nbsp;
                    <a href='../client/delete.php?id={$row['id']}' title='Delete' onclick='return confirm(\"Are you sure you want to delete this post?\")'><i class='fas fa-trash'></i></a>
                </td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='alert alert-info text-center'><h3>No posts found.</h3></div>";
    }

    mysqli_close($link);
    ?>
</div>
</body>
</html>
