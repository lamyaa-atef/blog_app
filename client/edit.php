<?php include '../db.php'; ?>
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
</head>
<body>
    <div class="container">
        <h2>Edit Post</h2><hr>
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
