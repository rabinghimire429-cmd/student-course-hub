<?php
require_once '../db.php';
session_start();

// Protect the page
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
    <title>Admin Dashboard - Student Course Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Admin Dashboard</span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-5">Welcome to the Admin Area</h1>

    <div class="row g-4">
        <!-- Card 1: Manage Programmes -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Programmes</h5>
                    <p class="card-text">Publish/unpublish programmes, edit names, descriptions, images, etc.</p>
                    <a href="manage-programmes.php" class="btn btn-primary">Go to Manage Programmes</a>
                </div>
            </div>
        </div>

        <!-- Card 2: View Interested Students -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Interested Students</h5>
                    <p class="card-text">View and export mailing lists of students who registered interest.</p>
                    <a href="view-interested.php" class="btn btn-success">View Mailing Lists</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Placeholder for future (e.g., Manage Modules) -->
        <div class="col-md-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Modules</h5>
                    <p class="card-text">Add, update or delete modules (coming soon).</p>
                    <button class="btn btn-secondary disabled">Not yet available</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>