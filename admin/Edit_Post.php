<?php
include '../db.php';
session_name('admin_session');
session_start();

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminEmail = $_SESSION['admin_email'];

// ✅ Get post ID from URL
$postID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ✅ Fetch the post
$stmt = $link->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $postID);
$stmt->execute();
$postData = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ✅ If no post found
if (!$postData) {
    die("<div class='alert alert-danger'>Post not found.</div>");
}

// ✅ Check if logged-in admin is the author
if ($postData['author'] !== $adminEmail) {
    die("<div class='alert alert-danger'>You are not authorized to edit this post.</div>");
}

$message = "";

// ✅ Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $message = "<div class='alert alert-danger'>Title and Content are required.</div>";
    } else {
        $stmt = $link->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $postID);
        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Error updating post.</div>";
        }
        $stmt->close();
    }
}

mysqli_close($link);
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="clearfix">
            <h1 style="display:inline-block;margin:0;">Edit Post</h1>
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

        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label for="title">Title:</label><span style="color: red">*</span>
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($postData['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label><span style="color: red">*</span>
                <textarea class="form-control" name="content" rows="6" required><?php echo htmlspecialchars($postData['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="posts.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</body>
</html>