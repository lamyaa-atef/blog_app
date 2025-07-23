<?php
include '../db.php';

// Fetch user data for viewing
$userID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM users WHERE ID = $userID";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);
} else {
    die("<p style='color: darkred; 
                font-weight: bold; 
                background-color: #f8d7da; 
                padding: 10px; 
                border-left: 5px solid red; 
                border-radius: 5px;'><span style='color: red;'>Error:</span> User not found.</p>");
}
// Close the database connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>View User</title>
    <style>
        .user-info {
            margin-top: 25px;
        }
        .user-info div {
            margin-bottom: 20px;
        }
        .user-info label {
            font-weight: bold;
            width: 150px;
            margin-bottom: 15px;
            display: block;
        }
        .user-info span {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View User Record</h1><hr>
        
        <div class="user-info">
            <div>
                <label>Name:</label>
                <span><?php echo htmlspecialchars($userData['Name']); ?></span>
            </div>
            <div>
                <label>Email:</label>
                <span><?php echo htmlspecialchars($userData['Email']); ?></span>
            </div>
            <div>
                <label>Gender:</label>
                <span><?php echo htmlspecialchars($userData['Gender'] == 'Male' ? 'M' : 'F'); ?></span>
            </div>
            <div>
                <span><?php echo htmlspecialchars($userData['Mail_Status'] == 'yes' ? 'You will receive e-mails from us.' : 'You will not receive e-mails from us.'); ?></span>
            </div>
        </div>
        <a href="./index.php" class="btn btn-primary">Back</a>
    </div>
</body>
</html>
