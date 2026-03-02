<?php
require_once '../db.php';
session_start();

// Check remember me cookie
if (!isset($_SESSION['admin_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $pdo->prepare("SELECT AdminID FROM Admins WHERE RememberToken = ?");
    $stmt->execute([hash('sha256', $token)]);
    $admin = $stmt->fetch();
    if ($admin) {
        $_SESSION['admin_id'] = $admin['AdminID'];
        header('Location: dashboard.php');
        exit;
    }
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT AdminID, PasswordHash FROM Admins WHERE Username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['PasswordHash'])) {
            $_SESSION['admin_id'] = $admin['AdminID'];

            if ($remember) {
                // Generate remember token
                $token = bin2hex(random_bytes(32));
                $hashed_token = hash('sha256', $token);
                $stmt = $pdo->prepare("UPDATE Admins SET RememberToken = ? WHERE AdminID = ?");
                $stmt->execute([$hashed_token, $admin['AdminID']]);
                setcookie('remember_token', $token, time() + (86400 * 30), "/", "", true, true); // 30 days, secure, httponly
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in both fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bluebird College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-box {
            max-width: 420px;
            margin: 120px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .logo { max-height: 80px; margin-bottom: 25px; }
    </style>
</head>
<body>

<div class="login-box text-center">
    <img src="../images/bluebird-logo.png" alt="Bluebird College logo" class="logo">
    <h3 class="mb-4">Admin / Staff Login</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>