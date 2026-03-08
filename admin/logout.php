<?php
// =============================================
// Admin Logout Script
// =============================================

// Start session
session_start();

// Clear all session variables
$_SESSION = array();

// If session uses cookies, delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Delete remember me cookie if it exists
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Clear any other cookies
setcookie('PHPSESSID', '', time() - 3600, '/');

// Redirect to homepage with success message
header('Location: ../index.php?logout=success');
exit;
?>