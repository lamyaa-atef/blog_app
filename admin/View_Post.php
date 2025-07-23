<?php
include '../db.php';

// Fetch post data for viewing
$postID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM posts WHERE id = $postID";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $postData = mysqli_fetch_assoc($result);
} else {
    die("<p style='color: darkred; 
                font-weight: bold; 
                background-color: #f8d7da; 
                padding: 10px; 
                border-left: 5px solid red; 
                border-radius: 5px;'><span style='color: red;'>Error:</span> Post not found.</p>");
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .post-info {
            margin-top: 25px;
        }
        .post-info div {
            margin-bottom: 20px;
        }
        .post-info label {
            font-weight: bold;
            width: 150px;
            margin-bottom: 15px;
            display: block;
        }
        .post-info span {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Post Record</h1><hr>
        
        <div class="post-info">
            <div>
                <label>Title:</label>
                <span><?php echo htmlspecialchars($postData['title']); ?></span>
            </div>
            <div>
                <label>Content:</label>
                <span><?php echo nl2br(htmlspecialchars($postData['content'])); ?></span>
            </div>
            <div>
                <label>Created At:</label>
                <span><?php echo htmlspecialchars($postData['created_at']); ?></span>
            </div>
        </div>

        <a href="./posts.php" class="btn btn-primary">Back</a>
    </div>
</body>
</html>
