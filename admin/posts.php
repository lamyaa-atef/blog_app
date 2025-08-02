<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'blog_app';
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

session_name('admin_session');
session_start();
if (!isset($_SESSION['admin_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($link, $query);

$adminEmail = $_SESSION['admin_email'];
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Posts</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="clearfix">
            <h1 style="display:inline-block;margin:0;">All Posts</h1>
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
        <a href="Add_Post.php" class="btn btn-success">Add New Post</a><br><br>

        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table class='table table-striped'>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars(substr($row['content'], 0, 100)) . "...</td>
                    <td>" . htmlspecialchars($row['author']) . "</td>
                    <td>{$row['created_at']}</td>
                    <td>
                        <a href='View_Post.php?id={$row['id']}' title='View'><i class='fas fa-eye'></i></a>&nbsp;";
                
                // ✅ Show Edit button only if the logged-in admin is the author
                if ($row['author'] === $adminEmail) {
                    echo "<a href='Edit_Post.php?id={$row['id']}' title='Edit'><i class='fas fa-edit'></i></a>&nbsp;";
                }

                echo "<a href='Delete_Post.php?id={$row['id']}' title='Delete' onclick='return confirm(\"Are you sure you want to delete this post?\")'><i class='fas fa-trash'></i></a>
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

