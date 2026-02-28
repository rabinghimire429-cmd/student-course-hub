<?php
// db.php - Secure PDO connection (use this in ALL pages)

$host     = 'localhost';
$dbname   = 'student_course_hub';
$username = 'root';           // XAMPP default
$password = '';               // XAMPP default (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In production: log error, don't show details to users
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
?>