<?php
session_start();
require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle publish/unpublish toggle
if (isset($_POST['toggle_id'])) {
    $id = (int)$_POST['toggle_id'];
    $sql = "UPDATE Programmes SET is_published = NOT is_published WHERE ProgrammeID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header('Location: manage-programmes.php');
    exit;
}

// Handle delete programme
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // First check if programme has modules
    $sql = "SELECT COUNT(*) FROM programme_modules WHERE ProgrammeID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $module_count = $stmt->fetchColumn();
    
    if ($module_count > 0) {
        $error = "Cannot delete: This programme has $module_count module(s) assigned. Remove modules first.";
    } else {
        $sql = "DELETE FROM Programmes WHERE ProgrammeID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $success = "Programme deleted successfully.";
    }
}

// FIXED: Get all programmes WITHOUT joining Levels table
$sql = "SELECT p.*, 
               (SELECT COUNT(*) FROM programme_modules WHERE ProgrammeID = p.ProgrammeID) as module_count
        FROM Programmes p 
        ORDER BY p.ProgrammeName";
$stmt = $pdo->query($sql);
$programmes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programmes - Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        
        .navbar {
            background-color: #2c3e50;
            padding: 15px 0;
        }
        
        .navbar-brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        
        .navbar-brand img {
            height: 35px;
            margin-right: 10px;
        }
        
        .nav-link {
            color: white !important;
        }
        
        .main-container {
            padding: 30px 0;
        }
        
        .page-header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .badge-published {
            background-color: #27ae60;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .badge-unpublished {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .badge-ug {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .badge-pg {
            background-color: #9b59b6;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .btn-action {
            margin: 2px;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="../images/bluebird-logo.png" alt="Logo">
            Bluebird College Admin
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container main-container">
    
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-journal-bookmark-fill"></i> Manage Programmes</h2>
            <p class="text-muted">Add, edit, publish/unpublish programmes</p>
        </div>
        <a href="add-programme.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New Programme
        </a>
    </div>
    
    <!-- Success/Error Messages -->
    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Programmes Table -->
    <div class="table-container">
        <?php if (empty($programmes)): ?>
            <p class="text-center text-muted py-4">No programmes found. <a href="add-programme.php">Add your first programme</a></p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Programme Name</th>
                            <th>Level</th>
                            <th>Modules</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programmes as $p): ?>
                        <tr>
                            <td><?php echo $p['ProgrammeID']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($p['ProgrammeName']); ?></strong>
                            </td>
                            <td>
                                <?php if ($p['LevelID'] == 1): ?>
                                    <span class="badge-ug">Undergraduate</span>
                                <?php else: ?>
                                    <span class="badge-pg">Postgraduate</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo $p['module_count']; ?> modules</span>
                                <a href="manage-modules.php?programme_id=<?php echo $p['ProgrammeID']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-gear"></i> Manage
                                </a>
                            </td>
                            <td>
                                <?php if ($p['is_published']): ?>
                                    <span class="badge-published">Published</span>
                                <?php else: ?>
                                    <span class="badge-unpublished">Unpublished</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Toggle Publish/Unpublish -->
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="toggle_id" value="<?php echo $p['ProgrammeID']; ?>">
                                    <button type="submit" class="btn btn-sm <?php echo $p['is_published'] ? 'btn-warning' : 'btn-success'; ?> btn-action">
                                        <i class="bi bi-<?php echo $p['is_published'] ? 'eye-slash' : 'eye'; ?>"></i>
                                        <?php echo $p['is_published'] ? 'Unpublish' : 'Publish'; ?>
                                    </button>
                                </form>
                                
                                <!-- Edit Button -->
                                <a href="edit-programme.php?id=<?php echo $p['ProgrammeID']; ?>" class="btn btn-sm btn-primary btn-action">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                
                                <!-- Delete Button -->
                                <a href="?delete=<?php echo $p['ProgrammeID']; ?>" 
                                   class="btn btn-sm btn-danger btn-action"
                                   onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($p['ProgrammeName']); ?>?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Back to Dashboard -->
    <div class="mt-3">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>