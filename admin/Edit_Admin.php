<?php
include '../db.php';
session_name('admin_session');
session_start();

// ✅ Allow only logged-in admins
if (!isset($_SESSION['admin_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminEmail = $_SESSION['admin_email'];
$userID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ✅ Ensure admin can edit ONLY their own account
$query = "SELECT * FROM users WHERE ID = $userID AND Email = '$adminEmail'";
$result = mysqli_query($link, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("<p style='color:red; font-weight:bold;'>Access Denied: You can only edit your own admin account.</p>");
}

$userData = mysqli_fetch_assoc($result);
$message = "";

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $mail_status = isset($_POST['mail_status']) ? 'yes' : 'no';

    $hasError = false;

    // Validate Name
    if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
        $message .= "<div class='alert alert-danger'>Name should contain only letters and spaces.</div>";
        $hasError = true;
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message .= "<div class='alert alert-danger'>Invalid email format.</div>";
        $hasError = true;
    }

    // Check if email exists for another user
    $emailCheckQuery = "SELECT * FROM users WHERE Email = '$email' AND ID != $userID";
    $checkResult = mysqli_query($link, $emailCheckQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $message .= "<div class='alert alert-danger'>The email '$email' is already used by another user.</div>";
        $hasError = true;
    }

    // ✅ If no errors, update
    if (!$hasError) {
        $updateQuery = "UPDATE users 
                        SET Name = '$name', Email = '$email', Gender = '$gender', Mail_Status = '$mail_status'
                        WHERE ID = $userID";
        if (mysqli_query($link, $updateQuery)) {
            // Update session values if changed
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $email;

            header("Location: users.php");
            exit;
        } else {
            $message .= "<div class='alert alert-danger'>Error updating: " . mysqli_error($link) . "</div>";
        }
    }
}

$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin Account</title>
    <link rel="icon" type="image/x-icon" href="/blog_app/favicon.ico">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top:20px;">
    <div class="clearfix">
        <h1 style="display:inline-block;margin:0;">Edit Admin Account</h1>
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

    <form action="" method="POST" style="max-width:600px;">
        <div class="form-group">
            <label for="name">Name:</label><span style="color:red;"> *</span>
            <input type="text" class="form-control" name="name" id="name"
                   value="<?php echo htmlspecialchars($userData['Name']); ?>" required>
        </div>
        <div class="form-group">
            <label>Gender:</label><span style="color:red;"> *</span>
            <div class="radio">
                <label>
                    <input type="radio" name="gender" value="Female" <?php echo $userData['Gender'] === 'Female' ? 'checked' : ''; ?>> Female
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="gender" value="Male" <?php echo $userData['Gender'] === 'Male' ? 'checked' : ''; ?>> Male
                </label>
            </div>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="mail_status" <?php echo $userData['Mail_Status'] === 'yes' ? 'checked' : ''; ?>> Receive Emails
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="users.php" class="btn btn-default">Cancel</a>
    </form>
</div>
</body>
</html>
