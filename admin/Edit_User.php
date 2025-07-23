<?php
include '../db.php';

// Fetch user data for editing
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $mail_status = isset($_POST['mail_status']) ? 'yes' : 'no';

    // Initialize error flag
    $hasError = false;

    // Validate Name
    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
        echo "<p style='color: darkred; margin-top: 10px; width: 887px; font-weight: bold; padding: 10px; background-color: #f8d7da; border-left: 5px solid red; border-radius: 5px;'>
                <span style='color:red;'>Error:</span> The Name field should contain only letters and spaces.</p>";
        $hasError = true;
    }

    // Validate Email
    if (!preg_match("/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/", $email)) {
        echo "<p style='color: darkred; margin-top: 10px; width: 887px; font-weight: bold; padding: 10px; background-color: #f8d7da; border-left: 5px solid red; border-radius: 5px;'>
                <span style='color:red;'>Error:</span> Invalid email format.</p>";
        $hasError = true;

    }
    // Check if the email already exists
    $emailCheckQuery = "SELECT * FROM users WHERE Email = '$email' AND ID != $userID";
    $result = mysqli_query($link, $emailCheckQuery);

    if (mysqli_num_rows($result) > 0) {
        // Email already exists
        echo "<p style='color: darkred; 
                        margin-top: 10px;
                        width: 817px;
                        font-weight: bold;
                        padding: 10px; 
                        background-color: #f8d7da; 
                        border-left: 5px solid red; 
                        border-radius: 5px;'>
    <span style='color:red;'>Error: </span>The email address '<em>$email</em>' 
                is belong to another user.
            </p>";
        $hasError = true;

    } 
    // Proceed only if there are no errors
    if (!$hasError) {
        // Update user data
        $updateQuery = "UPDATE users
                        SET Name = '$name', Email = '$email', Gender = '$gender', Mail_Status = '$mail_status' 
                        WHERE ID = $userID";
        if (mysqli_query($link, $updateQuery)) {
            header("Location: ./index.php");
            exit;
        } else {
            echo  "<p>".$updateQuery . "<br>" . mysqli_error($link)."</p>";
        }
    }
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
    <title>Edit User</title>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1><hr>
        <p>Update the details of the user and submit the form.</p>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name:</label><span style="color: red"> *</span>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userData['Name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label><span style="color: red"> *</span>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['Email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Gender:</label><span style="color: red"> *</span>
                <div class="radio">
                    <label for="female">
                        <input type="radio" id="female" name="gender" value="Female" <?php echo $userData['Gender'] === 'Female' ? 'checked' : ''; ?> required>Female
                    </label>
                </div>
                <div class="radio">
                    <label for="male">
                        <input type="radio" id="male" name="gender" value="Male" <?php echo $userData['Gender'] === 'Male' ? 'checked' : ''; ?> required>Male
                    </label>
                </div>
            </div>
            <div class="checkbox">
                <label for="mail_status">
                    <input type="checkbox" name="mail_status" value="yes" <?php echo $userData['Mail_Status'] === 'yes' ? 'checked' : ''; ?>>Receive E-mails from us.
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="./index.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</body>
</html>
