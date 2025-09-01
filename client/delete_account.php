<?php
include '../db.php';
session_name('client_session');
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];

// ✅ Check user role before deleting
$stmt = $link->prepare("SELECT Role FROM users WHERE Email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$stmt->bind_result($role);
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// ✅ Prevent deleting the last admin account
if ($user['Role'] === 'admin') {
    $check = $link->query("SELECT COUNT(*) AS total FROM users WHERE Role='admin'");
    $count = $check->fetch_assoc()['total'];
    if ($count <= 1) {
        header("Location: profile.php?msg=CannotDeleteLastAdmin");
        exit();
    }
}

// ✅ Delete all posts by this user
$stmt = $link->prepare("DELETE FROM posts WHERE author = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$stmt->close();

// ✅ Delete user account
$stmt = $link->prepare("DELETE FROM users WHERE Email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$stmt->close();

// ✅ Destroy client session
session_unset();
session_destroy();

// ✅ If user was admin, destroy admin session too
if ($role === 'admin') {
    session_name('admin_session');
    session_start();
    session_unset();
    session_destroy();
}

// Redirect to register page
header("Location: index.php?msg=AccountDeleted");
exit();
?>

