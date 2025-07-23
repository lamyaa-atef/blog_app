<?php include '../db.php'; ?>

<?php
$message = "";

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $message = "<p style='color: darkred; margin-top: 10px; width: 887px; font-weight: bold; padding: 10px; background-color: #f8d7da; border-left: 5px solid red; border-radius: 5px;'>
            <span style='color:red;'>Error:</span> Title and Content are required.</p>";
    } else {
        $stmt = $link->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        if ($stmt->execute()) {
            //$message = "<div class='alert alert-success' style='width: 400px;'>Post added successfully!</div>";
            header("Location: index.php");
            exit;
        } else {
            $message = "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Create Post</title>
</head>
<body>
    <div class="container">
        <h1>Create New Post</h1><hr>
        <p>Please fill this form to add a post.</p>

        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label for="title">Title:</label><span style="color: red"> *</span>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label><span style="color: red"> *</span>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add Post</button>
            <button type="reset" class="btn btn-default">Reset</button>
        </form>
    </div>
</body>
</html>


