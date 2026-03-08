<?php
echo "<h2>Login System Test</h2>";

// Check files
$files = [
    'config.php',
    'db.php',
    'includes/security.php',
    'admin/login.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ $file exists<br>";
    } else {
        echo "✗ $file MISSING<br>";
    }
}

// Check folders
$folders = ['logs', 'includes'];
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        echo "✓ $folder folder exists<br>";
    } else {
        echo "✗ $folder folder MISSING<br>";
    }
}

// Check database connection
try {
    require_once 'db.php';
    echo "✓ Database connected<br>";
    
    // Check if remember_token column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM Admins LIKE 'RememberToken'");
    if ($stmt->rowCount() > 0) {
        echo "✓ RememberToken column exists<br>";
    } else {
        echo "✗ RememberToken column MISSING - run SQL ALTER TABLE<br>";
    }
    
    // Check if activity_log table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'activity_log'");
    if ($stmt->rowCount() > 0) {
        echo "✓ activity_log table exists<br>";
    } else {
        echo "✗ activity_log table MISSING - run SQL CREATE TABLE<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
}

echo "<h3>Next Steps:</h3>";
echo "1. Visit <a href='admin/login.php'>admin/login.php</a><br>";
echo "2. Test with correct credentials<br>";
echo "3. Test rate limiting with wrong credentials<br>";
echo "4. Check logs folder after testing<br>";
?>