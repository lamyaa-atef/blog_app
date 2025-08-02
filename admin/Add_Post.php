<?php
include '../db.php';
session_name('admin_session');
session_start();
if (!isset($_SESSION['user_email']) && !isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}


$message = "";


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author = $_SESSION['admin_email']; // 👈 use logged-in email as author

    if (empty($title) || empty($content)) {
        $message = "<p style='color: darkred; margin-top: 10px; width: 887px; font-weight: bold; padding: 10px; background-color: #f8d7da; border-left: 5px solid red; border-radius: 5px;'>
            <span style='color:red;'>Error:</span> Title and Content are required.</p>";
    } else {
        $stmt = $link->prepare("INSERT INTO posts (title, content, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $author);
        if ($stmt->execute()) {
            header("Location: posts.php");
            exit;
        } else {
            $message = "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
$adminName = $_SESSION['admin_name'] ?? 'Admin'; // Default if name is missing
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
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="clearfix">
            <h1 style="display:inline-block;margin:0;">Create New Post</h1>
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
            <a href="./posts.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</body>
</html>