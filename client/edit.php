<?php 
    include '../db.php';
    session_name('client_session');
    session_start();
    if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
        header("Location: login.php");
        exit();
    } 
?>
<?php
$id = $_GET['id'];
$post = $link->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Edit Post</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="header-flex">
            <h1 style="display:inline-block;margin:0;">Edit Post</h1>
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
        <form method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Update</button>
            <a href="index.php" class="btn btn-default">Cancel</a>
        </form>
        <br>
        <?php
        if (isset($_POST['update'])) {
            $title = $link->real_escape_string($_POST['title']);
            $content = $link->real_escape_string($_POST['content']);
            
            $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id";
            if ($link->query($sql)) {
                //echo "<div class='alert alert-success'>Post updated successfully!</div>";
                header("Location: index.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Error: " . $link->error . "</div>";
            }
        }
        ?>
    </div>
</body>
</html>
