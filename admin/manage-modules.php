<?php
require_once '../config.php';
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$programme_id = isset($_GET['programme_id']) ? (int)$_GET['programme_id'] : 0;

// Fetch all modules for dropdown
$all_modules = $pdo->query("SELECT ModuleID, ModuleName FROM modules ORDER BY ModuleName")->fetchAll();

// Handle add module to programme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_module'])) {
    $module_id = (int)$_POST['module_id'];
    $year = (int)$_POST['year'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO programme_modules (ProgrammeID, ModuleID, Year) VALUES (?, ?, ?)");
        $stmt->execute([$programme_id, $module_id, $year]);
        $success = "Module added to programme successfully.";
    } catch (PDOException $e) {
        $error = "Error adding module: " . $e->getMessage();
    }
}

// Handle remove module
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    $stmt = $pdo->prepare("DELETE FROM programme_modules WHERE ProgrammeModuleID = ?");
    $stmt->execute([$remove_id]);
    header("Location: manage-modules.php?programme_id=$programme_id");
    exit;
}

// Fetch programme details
$stmt = $pdo->prepare("SELECT ProgrammeName FROM Programmes WHERE ProgrammeID = ?");
$stmt->execute([$programme_id]);
$programme = $stmt->fetch();

if (!$programme) {
    header('Location: manage-programmes.php');
    exit;
}

// Fetch current modules with details
$stmt = $pdo->prepare("
    SELECT pm.ProgrammeModuleID, pm.Year, m.ModuleName, m.Description, s.Name as ModuleLeader
    FROM programme_modules pm
    JOIN modules m ON pm.ModuleID = m.ModuleID
    LEFT JOIN staff s ON m.module_leader_id = s.StaffID
    WHERE pm.ProgrammeID = ?
    ORDER BY pm.Year, m.ModuleName
");
$stmt->execute([$programme_id]);
$current_modules = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules - <?= htmlspecialchars($programme['ProgrammeName']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-5">
    <h2>Manage Modules: <?= htmlspecialchars($programme['ProgrammeName']) ?></h2>
    <a href="manage-programmes.php" class="btn btn-secondary mb-3">Back to Programmes</a>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5>Add Module to Programme</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Module</label>
                            <select name="module_id" class="form-select" required>
                                <option value="">Choose a module...</option>
                                <?php foreach ($all_modules as $module): ?>
                                    <option value="<?= $module['ModuleID'] ?>">
                                        <?= htmlspecialchars($module['ModuleName']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" required>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="add_module" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add to Programme
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Quick Links</h5>
                </div>
                <div class="card-body">
                    <a href="add-module.php" class="btn btn-success w-100 mb-2">
                        <i class="bi bi-plus-circle"></i> Create New Module
                    </a>
                    <a href="manage-all-modules.php" class="btn btn-info w-100">
                        <i class="bi bi-grid"></i> Manage All Modules
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5>Current Modules by Year</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($current_modules)): ?>
                        <p class="text-muted">No modules assigned yet.</p>
                    <?php else: ?>
                        <?php 
                        $modules_by_year = [];
                        foreach ($current_modules as $mod) {
                            $modules_by_year[$mod['Year']][] = $mod;
                        }
                        ?>
                        
                        <?php for ($year = 1; $year <= 4; $year++): ?>
                            <?php if (isset($modules_by_year[$year])): ?>
                                <h6 class="mt-3">Year <?= $year ?></h6>
                                <ul class="list-group">
                                    <?php foreach ($modules_by_year[$year] as $mod): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?= htmlspecialchars($mod['ModuleName']) ?></strong>
                                                <?php if ($mod['ModuleLeader']): ?>
                                                    <br><small class="text-muted">Leader: <?= htmlspecialchars($mod['ModuleLeader']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <a href="?programme_id=<?= $programme_id ?>&remove=<?= $mod['ProgrammeModuleID'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Remove this module from programme?')">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>