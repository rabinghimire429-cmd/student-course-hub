<?php
// Start session to store login information
session_start();

// Include database connection
require_once '../db.php';

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = ''; // Variable to store error messages

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get username and password from form
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']); // Check if remember me is checked
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        
        // Search for admin in database
        $sql = "SELECT AdminID, Username, PasswordHash FROM Admins WHERE Username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        // Check if admin exists and password is correct
        if ($admin && password_verify($password, $admin['PasswordHash'])) {
            
            // Login successful - store admin ID in session
            $_SESSION['admin_id'] = $admin['AdminID'];
            
            // If remember me is checked, set a cookie for 30 days
            if ($remember) {
                // Generate random token
                $token = bin2hex(random_bytes(32));
                $hashed_token = hash('sha256', $token);
                
                // Save token in database
                $sql = "UPDATE Admins SET RememberToken = ? WHERE AdminID = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$hashed_token, $admin['AdminID']]);
                
                // Set cookie (30 days)
                setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
            
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

// Check for remember me cookie (if not logged in)
if (!isset($_SESSION['admin_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $hashed_token = hash('sha256', $token);
    
    $sql = "SELECT AdminID FROM Admins WHERE RememberToken = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hashed_token]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        $_SESSION['admin_id'] = $admin['AdminID'];
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bluebird College</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 60px;
        }
        
        .logo h3 {
            color: #333;
            margin-top: 10px;
        }
        
        .form-control {
            height: 45px;
            border-radius: 5px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            height: 45px;
            font-weight: bold;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-login:hover {
            opacity: 0.9;
        }
        
        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="logo">
        <img src="../images/bluebird-logo.png" alt="Bluebird College">
        <h3>Admin Login</h3>
    </div>
    
    <?php if ($error): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" 
                   class="form-control" 
                   id="username" 
                   name="username" 
                   required 
                   autofocus>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" 
                   class="form-control" 
                   id="password" 
                   name="password" 
                   required>
        </div>
        
        <div class="mb-3 form-check">
            <input type="checkbox" 
                   class="form-check-input" 
                   id="remember" 
                   name="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>
        
        <button type="submit" class="btn-login">Login</button>
    </form>
    
    <p class="text-center text-muted mt-3 small">
        CTEC2712 Web Application Development - Group Project
    </p>
</div>

</body>
</html>