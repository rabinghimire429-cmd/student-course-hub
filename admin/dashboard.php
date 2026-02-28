<?php
require_once '../db.php';
session_start();

// Protect this page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Admin Dashboard</h1>
    <p class="lead">You are logged in successfully.</p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
    <!-- We'll add more links here in next steps -->
</div>
</body>
</html>