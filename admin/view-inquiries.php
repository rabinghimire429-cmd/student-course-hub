<?php
session_start();
require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle mark as read
if (isset($_GET['mark_read'])) {
    $id = (int)$_GET['mark_read'];
    $pdo->prepare("UPDATE contactmessages SET IsRead = 1 WHERE MessageID = ?")->execute([$id]);
    header('Location: view-inquiries.php');
    exit;
}

// Handle mark as unread
if (isset($_GET['mark_unread'])) {
    $id = (int)$_GET['mark_unread'];
    $pdo->prepare("UPDATE contactmessages SET IsRead = 0 WHERE MessageID = ?")->execute([$id]);
    header('Location: view-inquiries.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM contactmessages WHERE MessageID = ?")->execute([$id]);
    header('Location: view-inquiries.php');
    exit;
}

// Get filter
$filter = $_GET['filter'] ?? 'all';

// Build query
$query = "SELECT * FROM contactmessages";
if ($filter === 'read') {
    $query .= " WHERE IsRead = 1";
} elseif ($filter === 'unread') {
    $query .= " WHERE IsRead = 0";
}
$query .= " ORDER BY SubmittedAt DESC";

$stmt = $pdo->query($query);
$messages = $stmt->fetchAll();

// Get counts
$total = $pdo->query("SELECT COUNT(*) FROM contactmessages")->fetchColumn();
$unread = $pdo->query("SELECT COUNT(*) FROM contactmessages WHERE IsRead = 0")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inquiries - Admin</title>
    
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
        }
        
        .admin-navbar .navbar-brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .admin-navbar .navbar-brand img {
            height: 35px;
            margin-right: 10px;
        }
        
        .admin-navbar .nav-link {
            color: rgba(255,255,255,0.8) !important;
            text-decoration: none;
            padding: 8px 15px;
        }
        
        .admin-navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white !important;
        }
        
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .message-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-left: 4px solid;
        }
        
        .message-card.unread {
            border-left-color: #f39c12;
        }
        
        .message-card.read {
            border-left-color: #27ae60;
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .message-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .message-email {
            color: #667eea;
            text-decoration: none;
        }
        
        .message-date {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .message-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .badge-unread {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .badge-read {
            background-color: #27ae60;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .btn-action {
            margin: 0 2px;
        }
        
        .filter-tabs {
            margin-bottom: 20px;
        }
        
        .filter-tabs .btn {
            margin-right: 5px;
        }
        
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
                Admin Panel
            </a>
            <div>
                <a class="nav-link d-inline-block" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link d-inline-block" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
    
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="bi bi-envelope-fill"></i> Contact Inquiries</h2>
            <p class="text-muted">Manage messages from website visitors</p>
        </div>
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    
    <!-- Stats Card -->
    <div class="stats-card">
        <div class="row">
            <div class="col-md-4">
                <h3><?php echo $total; ?></h3>
                <p class="mb-0">Total Messages</p>
            </div>
            <div class="col-md-4">
                <h3><?php echo $unread; ?></h3>
                <p class="mb-0">Unread Messages</p>
            </div>
            <div class="col-md-4">
                <h3><?php echo $total - $unread; ?></h3>
                <p class="mb-0">Read Messages</p>
            </div>
        </div>
    </div>
    
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="?filter=all" class="btn btn-<?php echo $filter === 'all' ? 'primary' : 'outline-secondary'; ?>">
            All (<?php echo $total; ?>)
        </a>
        <a href="?filter=unread" class="btn btn-<?php echo $filter === 'unread' ? 'warning' : 'outline-secondary'; ?>">
            Unread (<?php echo $unread; ?>)
        </a>
        <a href="?filter=read" class="btn btn-<?php echo $filter === 'read' ? 'success' : 'outline-secondary'; ?>">
            Read (<?php echo $total - $unread; ?>)
        </a>
    </div>
    
    <!-- Messages List -->
    <?php if (empty($messages)): ?>
        <div class="alert alert-info text-center">
            No messages found.
        </div>
    <?php else: ?>
        <?php foreach ($messages as $msg): ?>
        <div class="message-card <?php echo $msg['IsRead'] ? 'read' : 'unread'; ?>">
            <div class="message-header">
                <div>
                    <span class="message-name"><?php echo htmlspecialchars($msg['Name']); ?></span>
                    <?php if (!$msg['IsRead']): ?>
                        <span class="badge-unread ms-2">New</span>
                    <?php endif; ?>
                </div>
                <span class="message-date">
                    <i class="bi bi-calendar"></i> <?php echo date('d M Y H:i', strtotime($msg['SubmittedAt'])); ?>
                </span>
            </div>
            
            <div class="mb-2">
                <i class="bi bi-envelope"></i>
                <a href="mailto:<?php echo htmlspecialchars($msg['Email']); ?>" class="message-email">
                    <?php echo htmlspecialchars($msg['Email']); ?>
                </a>
            </div>
            
            <div class="message-content">
                <?php echo nl2br(htmlspecialchars($msg['Message'])); ?>
            </div>
            
            <div class="mt-3">
                <?php if ($msg['IsRead']): ?>
                    <a href="?mark_unread=<?php echo $msg['MessageID']; ?>" class="btn btn-sm btn-warning btn-action">
                        <i class="bi bi-envelope"></i> Mark Unread
                    </a>
                <?php else: ?>
                    <a href="?mark_read=<?php echo $msg['MessageID']; ?>" class="btn btn-sm btn-success btn-action">
                        <i class="bi bi-check-circle"></i> Mark Read
                    </a>
                <?php endif; ?>
                
                <a href="?delete=<?php echo $msg['MessageID']; ?>" class="btn btn-sm btn-danger btn-action" 
                   onclick="return confirm('Delete this message?')">
                    <i class="bi bi-trash"></i> Delete
                </a>
                
                <a href="mailto:<?php echo htmlspecialchars($msg['Email']); ?>" class="btn btn-sm btn-primary btn-action">
                    <i class="bi bi-reply"></i> Reply
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
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