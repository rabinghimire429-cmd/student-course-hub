<?php
require_once 'config.php';
require_once 'db.php';

echo "<h2>Database Table Check</h2>";

$tables = [
    'programmes',
    'modules',
    'staff',
    'contactmessages',
    'interested_students',
    'admins'
];

foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch()['count'];
        echo "<p style='color:green'>✓ Table '$table' exists - $count rows</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red'>✗ Table '$table' does NOT exist - " . $e->getMessage() . "</p>";
    }
}

echo "<h3>Your database name: " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "</h3>";
?>