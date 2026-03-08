<?php
// Start session
session_start();

// Include database connection
require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get admin info
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT Username FROM Admins WHERE AdminID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();
$username = $admin ? $admin['Username'] : 'Admin';

// Get counts
$programmes = $pdo->query("SELECT COUNT(*) FROM Programmes")->fetchColumn();
$modules = $pdo->query("SELECT COUNT(*) FROM modules")->fetchColumn();
$staff = $pdo->query("SELECT COUNT(*) FROM staff")->fetchColumn();
$interested = $pdo->query("SELECT COUNT(*) FROM interested_students")->fetchColumn();
$messages = $pdo->query("SELECT COUNT(*) FROM contactmessages WHERE IsRead = 0")->fetchColumn();
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
        
        .admin-navbar {
            background-color: #2c3e50;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-navbar .navbar-brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .admin-navbar .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .admin-navbar .nav-link {
            color: rgba(255,255,255,0.8) !important;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
        }
        
        .admin-navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white !important;
        }
        
        .welcome-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
        
        .menu-card {
            background: white;
            border-radius: 10px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            border: 1px solid #e9ecef;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .menu-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .menu-icon.blue { color: #3498db; }
        .menu-icon.green { color: #27ae60; }
        .menu-icon.orange { color: #f39c12; }
        .menu-icon.purple { color: #9b59b6; }
        
        .menu-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        
        .btn-menu {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .btn-menu:hover {
            opacity: 0.9;
            color: white;
        }
        
        .btn-menu.blue { background-color: #3498db; }
        .btn-menu.green { background-color: #27ae60; }
        .btn-menu.orange { background-color: #f39c12; }
        .btn-menu.purple { background-color: #9b59b6; }
        
        .admin-footer {
            background-color: white;
            padding: 20px 0;
            margin-top: 50px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<!-- Admin Navbar -->
<nav class="admin-navbar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="dashboard.php">
                <img src="../images/bluebird-logo.png" alt="Logo">
                Admin Dashboard
            </a>
            <div>
                <span class="text-white me-3">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($username); ?>
                </span>
                <a class="nav-link d-inline-block" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="welcome-box">
    <div class="container">
        <h2>Welcome back, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Manage your college content from here.</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <!-- Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="stat-number"><?php echo $programmes; ?></div>
                <div class="stat-label">Total Programmes</div>
                <small class="text-success"><?php echo $pdo->query("SELECT COUNT(*) FROM Programmes WHERE is_published = 1")->fetchColumn(); ?> published</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card green">
                <div class="stat-number"><?php echo $modules; ?></div>
                <div class="stat-label">Total Modules</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card orange">
                <div class="stat-number"><?php echo $staff; ?></div>
                <div class="stat-label">Staff Members</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card red">
                <div class="stat-number"><?php echo $messages; ?></div>
                <div class="stat-label">Unread Messages</div>
                <small><?php echo $interested; ?> interested students</small>
            </div>
        </div>
    </div>
    
    <!-- Menu Cards -->
    <h3 class="mb-3">Quick Actions</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="menu-card">
                <div class="menu-icon blue">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="menu-title">Manage Programmes</div>
                <p class="text-muted small mb-3">Add, edit, delete programmes and modules</p>
                <a href="manage-programmes.php" class="btn-menu blue">Go to Programmes</a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="menu-card">
                <div class="menu-icon green">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="menu-title">Staff Management</div>
                <p class="text-muted small mb-3">Add, edit, delete staff members</p>
                <a href="manage-staff.php" class="btn-menu green">Manage Staff</a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="menu-card">
                <div class="menu-icon orange">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div class="menu-title">Student Interests</div>
                <p class="text-muted small mb-3">View interested students (<?php echo $interested; ?>)</p>
                <a href="view-interested.php" class="btn-menu orange">View List</a>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="menu-card">
                <div class="menu-icon purple">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div class="menu-title">Contact Inquiries</div>
                <p class="text-muted small mb-3">View messages (<?php echo $messages; ?> unread)</p>
                <a href="view-inquiries.php" class="btn-menu purple">View Messages</a>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats Row -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>System Status:</strong> 
                Everything is running normally. Last login: <?php echo date('d M Y H:i:s'); ?>
            </div>
        </div>
    </div>
    
</div>

<!-- Admin Footer -->
<footer class="admin-footer">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Bluebird College Admin Panel</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>