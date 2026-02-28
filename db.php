<?php
// db.php - Secure database connection using PDO (recommended over mysqli for modern PHP)

$host = 'localhost';
$dbname = 'student_course_hub';
$username = 'root';       // default XAMPP user
$password = '';           // default XAMPP password (empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In production: log error, show friendly message
    die("Connection failed: " . $e->getMessage());
}
?>