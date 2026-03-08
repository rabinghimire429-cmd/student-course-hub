<?php
require_once '../config.php';
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? 'general';
    
    $target_dir = "../images/gallery/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . '_' . basename($_FILES['image']['name']);
    $target_file = $target_dir . $file_name;
    $image_path = 'images/gallery/' . $file_name;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image_path, category) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_path, $category]);
        $success = "Image uploaded successfully!";
    } else {
        $error = "Failed to upload image.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image path to delete file
    $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetch();
    
    if ($image && file_exists("../" . $image['image_path'])) {
        unlink("../" . $image['image_path']);
    }
    
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([$id]);
    header('Location: manage-gallery.php');
    exit;
}

// Fetch gallery images
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Admin</title>
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
    <h2>Gallery Management</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Upload Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Upload New Image</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="campus">Campus</option>
                            <option value="students">Students</option>
                            <option value="events">Events</option>
                            <option value="achievements">Achievements</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Image File</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
        </div>
    </div>
    
    <!-- Gallery Grid -->
    <?php if (empty($images)): ?>
        <div class="alert alert-info">No images in gallery yet.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($images as $img): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="<?= '../' . $img['image_path'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($img['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($img['title']) ?></h5>
                            <p class="card-text small"><?= htmlspecialchars($img['description']) ?></p>
                            <p class="text-muted small">
                                Category: <?= $img['category'] ?><br>
                                Uploaded: <?= date('d M Y', strtotime($img['uploaded_at'])) ?>
                            </p>
                            <a href="?delete=<?= $img['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this image?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>