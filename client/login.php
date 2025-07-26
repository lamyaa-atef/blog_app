<?php
    include '../db.php';
    session_name('client_session');
    session_start();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $password = $_POST['password'];

        $stmt = $link->prepare("SELECT * FROM users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['Password'])) {
            $_SESSION['user_email'] = $result['Email'];
            $_SESSION['user_name'] = $result['Name'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2>Login</h2><hr>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="width: 400px;"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Email:</label><span style="color:red">*</span>
            <input type="email" name="email" class="form-control" required
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="password">Password:</label><span style="color:red">*</span>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>
