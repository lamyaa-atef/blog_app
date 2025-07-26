<?php
include '../db.php';
session_name('client_session');
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

// Fetch current user info
$stmt = $link->prepare("SELECT Name, Gender, Mail_Status FROM users WHERE Email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $mail_status = isset($_POST['mail_status']) ? 'yes' : 'no';

    $stmt = $link->prepare("UPDATE users SET Name=?, Gender=?, Mail_Status=? WHERE Email=?");
    $stmt->bind_param("ssss", $name, $gender, $mail_status, $userEmail);
    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name; // Update session name
        header("Location: profile.php");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 20px;">
    <div class="header-flex">
        <h1 style="display:inline-block;margin:0;">Edit Profile</h1>
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
        <a href="profile.php" class="btn btn-default">Cancel</a>
    </form>
</div>
</body>
</html>
