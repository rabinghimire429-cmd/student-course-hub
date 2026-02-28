<?php
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle toggle request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    $id = (int)$_POST['toggle_id'];
    $stmt = $pdo->prepare("UPDATE Programmes SET is_published = NOT is_published WHERE ProgrammeID = ?");
    $stmt->execute([$id]);
    // Refresh page to show changes
    header('Location: manage-programmes.php');
    exit;
}

// Fetch all programmes
$stmt = $pdo->query("SELECT ProgrammeID, ProgrammeName, is_published FROM Programmes ORDER BY ProgrammeName");
$programmes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programmes - Admin</title>
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
    <h2>Manage Programme Visibility</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info">No programmes found.</div>
    <?php else: ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Programme Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programmes as $prog): ?>
                    <tr>
                        <td><?= htmlspecialchars($prog['ProgrammeName']) ?></td>
                        <td>
                            <?php if ($prog['is_published']): ?>
                                <span class="badge bg-success">Published (Visible)</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Unpublished (Hidden)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="toggle_id" value="<?= $prog['ProgrammeID'] ?>">
                                <button type="submit" class="btn btn-sm <?= $prog['is_published'] ? 'btn-warning' : 'btn-success' ?>">
                                    <?= $prog['is_published'] ? 'Unpublish' : 'Publish' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>