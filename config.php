<?php
// config.php - Site configuration
session_start(); // Only start session here, not in other files

// Define base paths
define('BASE_PATH', __DIR__);
define('BASE_URL', 'http://localhost/student-course-hub');

// For images and includes
define('IMAGE_PATH', BASE_URL . '/images');
define('CSS_PATH', BASE_URL . '/css');

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>