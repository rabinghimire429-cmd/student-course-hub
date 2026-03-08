<?php
require_once '../config.php';
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get staff ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: manage-staff.php');
    exit;
}

// Fetch staff data
$stmt = $pdo->prepare("SELECT * FROM staff WHERE StaffID = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();

if (!$member) {
    header('Location: manage-staff.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $photo = trim($_POST['photo'] ?? '');
    
    if (empty($name) || empty($email)) {
        $error = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE staff 
                SET Name = ?, job_title = ?, department = ?, Email = ?, Phone = ?, Bio = ?, photo = ?
                WHERE StaffID = ?
            ");
            $stmt->execute([$name, $job_title, $department, $email, $phone, $bio, $photo, $id]);
            $success = 'Staff member updated successfully!';
            
            // Refresh data
            $stmt = $pdo->prepare("SELECT * FROM staff WHERE StaffID = ?");
            $stmt->execute([$id]);
            $member = $stmt->fetch();
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
    <title>Edit Staff - Admin</title>
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
    <h2>Edit Staff: <?= htmlspecialchars($member['Name']) ?></h2>
    <a href="manage-staff.php" class="btn btn-secondary mb-3">Back to Staff List</a>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($member['Name']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" name="job_title" class="form-control" value="<?= htmlspecialchars($member['job_title'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select">
                        <option value="">Select Department</option>
                        <?php
                        $depts = ['Computer Science', 'Business', 'Management', 'Marketing', 'Finance', 'Academic Leadership'];
                        foreach ($depts as $dept):
                        ?>
                            <option value="<?= $dept ?>" <?= ($member['department'] ?? '') == $dept ? 'selected' : '' ?>>
                                <?= $dept ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($member['Email'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($member['Phone'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Photo Path</label>
                    <input type="text" name="photo" class="form-control" value="<?= htmlspecialchars($member['photo'] ?? '') ?>">
                    <small class="text-muted">Path relative to root folder</small>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Biography</label>
            <textarea name="bio" id="summernote" class="form-control" rows="5"><?= htmlspecialchars($member['Bio'] ?? '') ?></textarea>
        </div>
        
        <?php if (!empty($member['photo'])): ?>
            <div class="mb-3">
                <img src="<?= BASE_URL . '/' . $member['photo'] ?>" class="img-thumbnail" style="max-height: 100px;" alt="Current photo">
                <p class="text-muted small">Current photo</p>
            </div>
        <?php endif; ?>
        
        <button type="submit" class="btn btn-primary">Update Staff Member</button>
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
            ['link', ['link']],
            ['view', ['codeview']]
        ]
    });
});
</script>
</body>
</html>