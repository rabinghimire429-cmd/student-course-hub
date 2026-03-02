<?php
session_start();

// Clear remember token in DB if set
if (isset($_SESSION['admin_id'])) {
    require_once '../db.php';
    $stmt = $pdo->prepare("UPDATE Admins SET RememberToken = NULL WHERE AdminID = ?");
    $stmt->execute([$_SESSION['admin_id']]);
}

session_destroy();
setcookie('remember_token', '', time() - 3600, "/", "", true, true); // Delete cookie

header('Location: login.php');
exit;
?>