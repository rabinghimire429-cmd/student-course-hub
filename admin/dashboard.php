<?php
require_once '../db.php';
session_start();

// Protect the page - only logged-in admins can see this
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

<!-- Navigation Bar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="d-flex">
            <span class="text-light me-3">Logged in as Admin</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-5">Admin Controls</h1>

    <div class="row g-4 justify-content-center">
        <!-- Card 1: Manage Programmes -->
        <div class="col-md-5 col-lg-4">
            <div class="card shadow h-100 border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">Manage Programmes</h5>
                    <p class="card-text">Publish or unpublish programmes, edit names, descriptions, images, leaders, etc.</p>
                    <a href="manage-programmes.php" class="btn btn-primary">Go to Manage Programmes</a>
                </div>
            </div>
        </div>

        <!-- Card 2: View Interested Students -->
        <div class="col-md-5 col-lg-4">
            <div class="card shadow h-100 border-success">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">Interested Students</h5>
                    <p class="card-text">View mailing lists of students who registered interest in programmes.</p>
                    <a href="view-interested.php" class="btn btn-success">View Mailing Lists</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <small class="text-muted">More features (e.g., manage modules, staff profiles) can be added later.</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>