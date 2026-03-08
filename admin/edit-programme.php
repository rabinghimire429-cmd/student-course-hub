<?php
require_once '../config.php';
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get programme ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: manage-programmes.php');
    exit;
}

// Fetch programme data
$stmt = $pdo->prepare("SELECT * FROM Programmes WHERE ProgrammeID = ?");
$stmt->execute([$id]);
$programme = $stmt->fetch();

if (!$programme) {
    header('Location: manage-programmes.php');
    exit;
}

// Fetch staff for dropdown
$stmt = $pdo->query("SELECT StaffID, Name, job_title FROM staff ORDER BY Name");
$staff = $stmt->fetchAll();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $programme_name = trim($_POST['programme_name'] ?? '');
    $level_id = (int)($_POST['level_id'] ?? 0);
    $leader_id = !empty($_POST['leader_id']) ? (int)$_POST['leader_id'] : null;
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $alt_text = trim($_POST['alt_text'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    if (empty($programme_name) || $level_id === 0) {
        $error = 'Programme name and level are required.';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE Programmes 
                SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, 
                    Description = ?, Image = ?, alt_text = ?, is_published = ?
                WHERE ProgrammeID = ?
            ");
            $stmt->execute([$programme_name, $level_id, $leader_id, $description, $image, $alt_text, $is_published, $id]);
            $success = 'Programme updated successfully!';
            
            // Refresh data
            $stmt = $pdo->prepare("SELECT * FROM Programmes WHERE ProgrammeID = ?");
            $stmt->execute([$id]);
            $programme = $stmt->fetch();
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Programme - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container my-5">
    <h2>Edit Programme: <?= htmlspecialchars($programme['ProgrammeName']) ?></h2>
    <a href="manage-programmes.php" class="btn btn-secondary mb-3">Back to Programmes</a>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Programme Name *</label>
                    <input type="text" name="programme_name" class="form-control" 
                           value="<?= htmlspecialchars($programme['ProgrammeName']) ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Level *</label>
                        <select name="level_id" class="form-select" required>
                            <option value="1" <?= $programme['LevelID'] == 1 ? 'selected' : '' ?>>Undergraduate</option>
                            <option value="2" <?= $programme['LevelID'] == 2 ? 'selected' : '' ?>>Postgraduate</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Programme Leader</label>
                        <select name="leader_id" class="form-select">
                            <option value="">-- None --</option>
                            <?php foreach ($staff as $s): ?>
                                <option value="<?= $s['StaffID'] ?>" 
                                    <?= $s['StaffID'] == $programme['ProgrammeLeaderID'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['Name']) ?> (<?= htmlspecialchars($s['job_title'] ?? 'Staff') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="summernote" class="form-control" rows="5"><?= htmlspecialchars($programme['Description'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Publishing</h5>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_published" class="form-check-input" id="publish" 
                                   <?= $programme['is_published'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="publish">Published (visible on site)</label>
                        </div>
                        
                        <hr>
                        
                        <h6>Image Settings</h6>
                        <div class="mb-3">
                            <label class="form-label">Image Path</label>
                            <input type="text" name="image" class="form-control" 
                                   value="<?= htmlspecialchars($programme['Image'] ?? '') ?>"
                                   placeholder="images/your-image.jpg">
                            <small class="text-muted">Path relative to root folder</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alt Text</label>
                            <input type="text" name="alt_text" class="form-control" 
                                   value="<?= htmlspecialchars($programme['alt_text'] ?? '') ?>"
                                   placeholder="Image description">
                        </div>
                        
                        <?php if (!empty($programme['Image'])): ?>
                            <div class="mt-3">
                                <img src="<?= BASE_URL . '/' . $programme['Image'] ?>" class="img-fluid rounded" alt="Current image">
                                <p class="text-muted small mt-1">Current image</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Programme</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    $('#summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});
</script>
</body>
</html>