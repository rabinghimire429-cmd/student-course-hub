<?php
// Start the session to check if user is logged in
session_start();

// Include database connection
require_once '../db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get the logged in admin's username from database
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT Username FROM Admins WHERE AdminID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();
$username = $admin ? $admin['Username'] : 'Admin';

// Get all the counts for displaying statistics

// Count total programmes
$sql = "SELECT COUNT(*) FROM Programmes";
$stmt = $pdo->query($sql);
$total_programmes = $stmt->fetchColumn();

// Count published programmes
$sql = "SELECT COUNT(*) FROM Programmes WHERE is_published = 1";
$stmt = $pdo->query($sql);
$published_programmes = $stmt->fetchColumn();

// Count total modules
$sql = "SELECT COUNT(*) FROM modules";
$stmt = $pdo->query($sql);
$total_modules = $stmt->fetchColumn();

// Count total staff
$sql = "SELECT COUNT(*) FROM staff";
$stmt = $pdo->query($sql);
$total_staff = $stmt->fetchColumn();

// Count interested students
$sql = "SELECT COUNT(*) FROM interested_students";
$stmt = $pdo->query($sql);
$interested_students = $stmt->fetchColumn();

// Count unread inquiries
$sql = "SELECT COUNT(*) FROM contactmessages WHERE IsRead = 0";
$stmt = $pdo->query($sql);
$unread_inquiries = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bluebird College</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        
        .navbar {
            background-color: #2c3e50;
            padding: 15px 0;
        }
        
        .navbar-brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .nav-link {
            color: white !important;
        }
        
        .welcome-box {
            background-color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid;
        }
        
        .stat-card.blue { border-left-color: #3498db; }
        .stat-card.green { border-left-color: #27ae60; }
        .stat-card.orange { border-left-color: #f39c12; }
        .stat-card.red { border-left-color: #e74c3c; }
        
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .action-card {
            background-color: white;
            border-radius: 8px;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            height: 100%;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .action-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .action-icon.blue { color: #3498db; }
        .action-icon.green { color: #27ae60; }
        .action-icon.orange { color: #f39c12; }
        .action-icon.purple { color: #9b59b6; }
        
        .action-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .action-desc {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .btn-custom {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-custom.blue { background-color: #3498db; }
        .btn-custom.green { background-color: #27ae60; }
        .btn-custom.orange { background-color: #f39c12; }
        .btn-custom.purple { background-color: #9b59b6; }
        .btn-custom.red { background-color: #e74c3c; }
        
        .btn-custom:hover {
            opacity: 0.9;
            color: white;
        }
        
        .count-badge {
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            margin-left: 5px;
        }
        
        .footer {
            background-color: white;
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="../images/bluebird-logo.png" alt="Logo">
            Bluebird College Admin
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="logout.php">Logout <i class="bi bi-box-arrow-right"></i></a>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="welcome-box">
    <div class="container">
        <h1><i class="bi bi-grid"></i> Admin Dashboard</h1>
        <p class="lead">Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
    </div>
</div>

<!-- Main Container -->
<div class="container">

    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="stat-number"><?php echo $total_programmes; ?></div>
                <div class="stat-label">Total Programmes</div>
                <small class="text-success"><?php echo $published_programmes; ?> published</small>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="stat-number"><?php echo $total_modules; ?></div>
                <div class="stat-label">Total Modules</div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="stat-number"><?php echo $total_staff; ?></div>
                <div class="stat-label">Staff Members</div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card red">
                <div class="stat-number"><?php echo $unread_inquiries; ?></div>
                <div class="stat-label">Unread Messages</div>
                <small><?php echo $interested_students; ?> interested students</small>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row - ONLY THE 4 MAIN CARDS -->
    <h3 class="mb-3">Quick Actions</h3>
    <div class="row mb-5">
        <div class="col-md-3">
            <div class="action-card">
                <div class="action-icon blue">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="action-title">Manage Programmes</div>
                <div class="action-desc">Add, edit, delete programmes and modules</div>
                <a href="manage-programmes.php" class="btn-custom blue">
                    <i class="bi bi-arrow-right-circle"></i> Go to Programmes
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="action-card">
                <div class="action-icon green">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="action-title">Staff Management</div>
                <div class="action-desc">Add, edit, delete staff members</div>
                <a href="manage-staff.php" class="btn-custom green">
                    <i class="bi bi-arrow-right-circle"></i> Manage Staff
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="action-card">
                <div class="action-icon orange">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="action-title">Student Interests</div>
                <div class="action-desc">
                    View interested students 
                    <span class="count-badge"><?php echo $interested_students; ?></span>
                </div>
                <a href="view-interested.php" class="btn-custom orange">
                    <i class="bi bi-arrow-right-circle"></i> View List
                </a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="action-card">
                <div class="action-icon purple">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div class="action-title">Contact Inquiries</div>
                <div class="action-desc">
                    View messages 
                    <span class="count-badge"><?php echo $unread_inquiries; ?> unread</span>
                </div>
                <a href="view-inquiries.php" class="btn-custom purple">
                    <i class="bi bi-arrow-right-circle"></i> View Inquiries
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Info Row - Optional summary -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Quick Summary:</strong> 
                <?php echo $total_programmes; ?> programmes, 
                <?php echo $total_modules; ?> modules, 
                <?php echo $total_staff; ?> staff members,
                <?php echo $interested_students; ?> interested students,
                <?php echo $unread_inquiries; ?> unread messages.
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="logout.php" class="btn btn-danger btn-lg">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Bluebird College. All rights reserved.</p>
        <p class="small">CTEC2712 Web Application Development - Group Project</p>
    </div>
</footer>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>