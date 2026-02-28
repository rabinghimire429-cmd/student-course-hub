<?php
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all interested students grouped by programme
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
    <title>Interested Students - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-5">
    <h2>Interested Students Mailing List</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if (empty($interests)): ?>
        <div class="alert alert-info">No students have registered interest yet.</div>
    <?php else: ?>
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
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>