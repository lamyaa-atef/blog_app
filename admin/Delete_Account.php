<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'blog_app';
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

session_name('admin_session');
session_start();

// ✅ Require logged-in admin
if (!isset($_SESSION['admin_email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ✅ Ensure we have a user id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php?msg=InvalidUser");
    exit();
}

$userId = (int) $_GET['id'];

// ✅ Get the user info first (to know if it's an admin)
$stmt = $link->prepare("SELECT ID, Email, Role FROM users WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: users.php?msg=UserNotFound");
    exit();
}

// ✅ Prevent deleting the last admin account
if ($user['Role'] === 'admin') {
    $check = $link->query("SELECT COUNT(*) AS total FROM users WHERE Role='admin'");
    $count = $check->fetch_assoc()['total'];
    if ($count <= 1) {
        header("Location: users.php?msg=CannotDeleteLastAdmin");
        exit();
    }
}

// ✅ Delete user’s posts first (if any)
$stmt = $link->prepare("DELETE FROM posts WHERE author = ?");
$stmt->bind_param("s", $user['Email']);
$stmt->execute();
$stmt->close();

// ✅ Delete user account
$stmt = $link->prepare("DELETE FROM users WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// ✅ If admin deleted their own account, log them out
if ($_SESSION['admin_email'] === $user['Email']) {
    session_unset();
    session_destroy();
    header("Location: login.php?msg=AdminDeletedSelf");
    exit();
}

// ✅ Otherwise, back to users list
header("Location: users.php?msg=UserDeleted");
exit();

