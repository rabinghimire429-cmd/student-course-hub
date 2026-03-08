<?php
session_start();
require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get all staff for programme leader dropdown
$sql = "SELECT StaffID, Name, job_title FROM staff ORDER BY Name";
$stmt = $pdo->query($sql);
$staff = $stmt->fetchAll();

$success = '';
$error = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $programme_name = trim($_POST['programme_name'] ?? '');
    $level_id = (int)($_POST['level_id'] ?? 0);
    $leader_id = !empty($_POST['leader_id']) ? (int)$_POST['leader_id'] : null;
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $alt_text = trim($_POST['alt_text'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    // Validate
    if (empty($programme_name) || $level_id === 0) {
        $error = 'Programme name and level are required.';
    } else {
        // Insert into database
        $sql = "INSERT INTO Programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, alt_text, is_published) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$programme_name, $level_id, $leader_id, $description, $image, $alt_text, $is_published])) {
            $success = 'Programme added successfully!';
        } else {
            $error = 'Error adding programme.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Programme - Admin</title>
    
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
        
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
        
        .form-label {
            font-weight: bold;
        }
        
        .required:after {
            content: " *";
            color: red;
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
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2><i class="bi bi-plus-circle"></i> Add New Programme</h2>
        <a href="manage-programmes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Programmes
        </a>
    </div>
    
    <!-- Success/Error Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Form -->
    <div class="form-container">
        <form method="POST" action="">
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label required">Programme Name</label>
                        <input type="text" name="programme_name" class="form-control" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Level</label>
                            <select name="level_id" class="form-select" required>
                                <option value="">Select Level</option>
                                <option value="1">Undergraduate</option>
                                <option value="2">Postgraduate</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Programme Leader</label>
                            <select name="leader_id" class="form-select">
                                <option value="">-- None --</option>
                                <?php foreach ($staff as $s): ?>
                                    <option value="<?php echo $s['StaffID']; ?>">
                                        <?php echo htmlspecialchars($s['Name']); ?> 
                                        (<?php echo htmlspecialchars($s['job_title'] ?? 'Staff'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5"></textarea>
                        <small class="text-muted">Describe the programme, its objectives, and career opportunities.</small>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>Publishing Options</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_published" class="form-check-input" id="publish" checked>
                                <label class="form-check-label" for="publish">Published (visible on website)</label>
                            </div>
                            
                            <hr>
                            
                            <h6>Image Settings</h6>
                            <div class="mb-3">
                                <label class="form-label">Image Path</label>
                                <input type="text" name="image" class="form-control" placeholder="images/your-image.jpg">
                                <small class="text-muted">Path relative to root folder</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alt Text</label>
                                <input type="text" name="alt_text" class="form-control" placeholder="Image description">
                                <small class="text-muted">For accessibility and SEO</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Programme
                </button>
                <a href="manage-programmes.php" class="btn btn-secondary">Cancel</a>
            </div>
            
        </form>
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>