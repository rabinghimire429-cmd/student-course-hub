<?php
// security.php - Security functions

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
    return true;
}

function rateLimit($key, $max_attempts = 5, $time_period = 300) {
    $attempts = $_SESSION['rate_limit'][$key] ?? ['count' => 0, 'first_attempt' => time()];
    
    if ($attempts['count'] >= $max_attempts) {
        if (time() - $attempts['first_attempt'] < $time_period) {
            return false;
        } else {
            // Reset
            $attempts = ['count' => 1, 'first_attempt' => time()];
        }
    } else {
        $attempts['count']++;
    }
    
    $_SESSION['rate_limit'][$key] = $attempts;
    return true;
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function logActivity($user_id, $action, $details = '') {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $action, $details, $ip, $user_agent]);
}