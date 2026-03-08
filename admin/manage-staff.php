<?php
require_once '../config.php';
require_once '../db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Check if staff is assigned as programme leader
        $check = $pdo->prepare("SELECT COUNT(*) FROM programmes WHERE ProgrammeLeaderID = ?");
        $check->execute([$id]);
        $prog_count = $check->fetchColumn();
        
        // Check if staff is assigned as module leader
        $check = $pdo->prepare("SELECT COUNT(*) FROM modules WHERE module_leader_id = ?");
        $check->execute([$id]);
        $mod_count = $check->fetchColumn();
        
        if ($prog_count > 0 || $mod_count > 0) {
            $error = "Cannot delete: Staff member is leader of $prog_count programme(s) and $mod_count module(s). Reassign first.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM staff WHERE StaffID = ?");
            $stmt->execute([$id]);
            $success = "Staff member deleted successfully.";
        }
    } catch (PDOException $e) {
        $error = "Delete failed: " . $e->getMessage();
    }
}

// Fetch all staff
$stmt = $pdo->query("
    SELECT s.*, 
           (SELECT COUNT(*) FROM programmes WHERE ProgrammeLeaderID = s.StaffID) as programme_count,
           (SELECT COUNT(*) FROM modules WHERE module_leader_id = s.StaffID) as module_count
    FROM staff s
    ORDER BY s.Name
");
$staff = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .staff-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Staff Management</h2>
        <a href="add-staff.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New Staff
        </a>
    </div>
    
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($staff)): ?>
        <div class="alert alert-info">No staff members found. <a href="add-staff.php">Add your first staff member</a></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Leadership</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <tr>
                            <td>
                                <?php if (!empty($member['photo'])): ?>
                                    <img src="<?= BASE_URL . '/' . $member['photo'] ?>" class="staff-photo" alt="<?= htmlspecialchars($member['Name']) ?>">
                                <?php else: ?>
                                    <div class="staff-photo bg-secondary text-white d-flex align-items-center justify-content-center">
                                        <?= strtoupper(substr($member['Name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($member['Name']) ?></td>
                            <td><?= htmlspecialchars($member['job_title'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($member['department'] ?? 'N/A') ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($member['Email']) ?>"><?= htmlspecialchars($member['Email'] ?? 'N/A') ?></a></td>
                            <td><?= htmlspecialchars($member['Phone'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($member['programme_count'] > 0): ?>
                                    <span class="badge bg-primary">Programme: <?= $member['programme_count'] ?></span>
                                <?php endif; ?>
                                <?php if ($member['module_count'] > 0): ?>
                                    <span class="badge bg-info">Module: <?= $member['module_count'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit-staff.php?id=<?= $member['StaffID'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="?delete=<?= $member['StaffID'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($member['Name']) ?>?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>