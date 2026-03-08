<?php
require_once 'config.php';
echo "<h2>Path Testing</h2>";
echo "BASE_URL: " . BASE_URL . "<br>";
echo "Current script: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Test if files exist
$files_to_test = [
    'index.php',
    'header.php',
    'footer.php',
    'db.php',
    'config.php',
    'images/bluebird-logo.png'
];

foreach ($files_to_test as $file) {
    $full_path = BASE_PATH . '/' . $file;
    if (file_exists($full_path)) {
        echo "✓ $file exists at: $full_path<br>";
    } else {
        echo "✗ $file NOT found at: $full_path<br>";
    }
}
?>