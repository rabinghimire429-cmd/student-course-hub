<?php
session_start();
require_once '../db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get all interested students
$sql = "SELECT p.ProgrammeName, i.StudentName, i.Email, i.RegisteredAt 
        FROM interested_students i 
        JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID 
        ORDER BY i.RegisteredAt DESC";
$stmt = $pdo->query($sql);
$interests = $stmt->fetchAll();

// Count total
$total = count($interests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interested Students - Admin</title>
    
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
        
        .content-box {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 30px 0;
        }
        
        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .table th {
            background-color: #f8f9fa;
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
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2><i class="bi bi-people-fill"></i> Interested Students</h2>
        <div>
            <a href="export-csv.php" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export to CSV
            </a>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="stats-box">
        <div class="row">
            <div class="col-md-6">
                <h3><?php echo $total; ?></h3>
                <p class="mb-0">Total Interested Students</p>
            </div>
            <div class="col-md-6 text-end">
                <i class="bi bi-envelope-paper" style="font-size: 48px;"></i>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="content-box">
        <?php if (empty($interests)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                <p class="text-muted mt-3">No interested students yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Programme</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Registered Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($interests as $row): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($row['ProgrammeName']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($row['StudentName']); ?></td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>">
                                    <?php echo htmlspecialchars($row['Email']); ?>
                                </a>
                            </td>
                            <td>
                                <i class="bi bi-calendar"></i>
                                <?php echo date('d/m/Y H:i', strtotime($row['RegisteredAt'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Export Info -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i>
                Total of <?php echo $total; ?> students have registered interest. 
                Click "Export to CSV" to download the list for mailing.
            </div>
        <?php endif; ?>
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>