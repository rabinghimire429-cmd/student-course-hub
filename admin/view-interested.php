<?php
require_once '../db.php';
session_start();

// Protect page - only logged-in admins
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all interested students
$stmt = $pdo->query("
    SELECT p.ProgrammeName, i.StudentName, i.Email, i.RegisteredAt
    FROM InterestedStudents i
    JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
    ORDER BY p.ProgrammeName, i.RegisteredAt DESC
");
$interests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College - Interested Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand img { max-height: 45px; width: auto; }
        footer img { max-height: 60px; width: auto; }
    </style>
</head>
<body>

<!-- Navbar with logo -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="../images/bluebird-logo.png" alt="Bluebird College logo" class="me-2">
            Bluebird College Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h2 class="mb-4">Interested Students (Mailing List)</h2>

    <?php if (empty($interests)): ?>
        <div class="alert alert-info">No students have registered interest yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Programme</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($interests as $interest): ?>
                        <tr>
                            <td><?= htmlspecialchars($interest['ProgrammeName']) ?></td>
                            <td><?= htmlspecialchars($interest['StudentName']) ?></td>
                            <td><?= htmlspecialchars($interest['Email']) ?></td>
                            <td><?= htmlspecialchars($interest['RegisteredAt']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- CSV Export Button - this is the new addition -->
        <div class="mt-4 text-end">
            <a href="export-csv.php" class="btn btn-info">Download CSV (Mailing List)</a>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<!-- Footer with logo -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <img src="../images/bluebird-logo.png" alt="Bluebird College logo" style="height: 60px; margin-bottom: 10px;">
        <p class="mb-0">© <?= date('Y') ?> Bluebird College. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>