<?php
require_once '../db.php';
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get admin username
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT Username FROM Admins WHERE AdminID = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();
$username = $admin ? $admin['Username'] : 'Admin';
?>

<?php include '../header.php'; ?>

<div class="container my-5 pt-5">
    <h1 class="text-center mb-5">Admin Dashboard</h1>
    <p class="text-center lead mb-5">Welcome back, <?= htmlspecialchars($username) ?>!</p>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100 border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-journal-bookmark-fill fs-1 text-primary mb-3 d-block"></i>
                    <h5 class="card-title">Manage Programmes</h5>
                    <p class="card-text">Add, edit, delete, publish/unpublish programmes & modules</p>
                    <a href="manage-programmes.php" class="btn btn-primary w-100">Go to Programmes</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100 border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-1 text-success mb-3 d-block"></i>
                    <h5 class="card-title">Interested Students</h5>
                    <p class="card-text">View & export mailing list</p>
                    <a href="view-interested.php" class="btn btn-success w-100">View Mailing List</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100 border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-envelope-fill fs-1 text-info mb-3 d-block"></i>
                    <h5 class="card-title">Contact Inquiries</h5>
                    <p class="card-text">View & manage messages</p>
                    <a href="view-inquiries.php" class="btn btn-info w-100">View Inquiries</a>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4">
            <a href="logout.php" class="btn btn-outline-danger btn-lg w-100">Logout</a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>