<?php
include '../db.php';
session_name('client_session');
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $mail_status = isset($_POST['mail_status']) ? 'yes' : 'no';
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $confirm_password = mysqli_real_escape_string($link, $_POST['confirm_password']);

    


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
    $emailCheckQuery = "SELECT * FROM users WHERE Email = '$email'";
    $result = mysqli_query($link, $emailCheckQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<p style='color: darkred; margin-top: 10px; width: 887px; font-weight: bold; padding: 10px; background-color: #f8d7da; border-left: 5px solid red; border-radius: 5px;'>
                <span style='color:red;'>Error:</span> The email address '<em>$email</em>' is already registered. Please use a different email address.</p>";
        $hasError = true;
    }

    if ($password !== $confirm_password) {
        echo "<p style='color: darkred; margin-top: 10px; width: 887px; font-weight: bold; padding: 10px; background-color: #f8d7da; border-left: 5px solid red; border-radius: 5px;'>
            <span style='color:red;'>Error:</span> Password and Confirm Password do not match.</p>";
        $hasError = true;
    }

    
    
    // Proceed only if there are no errors
    if (!$hasError) {
        $query = "INSERT INTO users (Name, Email, Password, Gender, Mail_Status) VALUES ('$name', '$email', '$hashedPassword', '$gender', '$mail_status')";
        if (mysqli_query($link, $query)) {
            header("Location: ./login.php");
            exit;
        } else {
            echo  "<p>".$query . "<br>" . mysqli_error($link)."</p>";
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
    <title>User Registration Form</title>
</head>
<body>
    <div class="container">
        <h1>Register to the Blog App</h1><hr>
        <p>Please fill this form to register.</p>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name:</label><span style="color: red"> *</span>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label><span style="color: red"> *</span>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label><span style="color: red"> *</span>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label><span style="color: red"> *</span>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label>Gender:</label><span style="color: red"> *</span>
                <div class="radio">
                    <label><input type="radio" name="gender" value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'checked' : ''; ?> required>Female</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="gender" value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'checked' : ''; ?> required>Male</label>
                </div>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="mail_status" <?php echo isset($_POST['mail_status']) ? 'checked' : ''; ?>>Receive E-mails from us.</label>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <button type="reset" class="btn btn-default">Reset</button>
        </form>
    </div>
</body>
</html>

