<?php
// Start session to destroy it
session_start();

// Include database connection
require_once '../db.php';

// If user is logged in, remove remember token from database
if (isset($_SESSION['admin_id'])) {
    $sql = "UPDATE Admins SET RememberToken = NULL WHERE AdminID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['admin_id']]);
}

// Destroy the session (log out)
session_destroy();

// Delete remember me cookie by setting it to expire in the past
setcookie('remember_token', '', time() - 3600, "/");

// Redirect to login page
header('Location: login.php');
exit;
?>