<?php
require_once 'db.php';
session_start();


$sql = "SELECT ProgrammeID, ProgrammeName, Description, Image 
        FROM Programmes 
        WHERE LevelID = 1 AND is_published = 1 
        ORDER BY ProgrammeName";
$stmt = $pdo->query($sql);
$programmes = $stmt->fetchAll();

$count = count($programmes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undergraduate Programmes - Bluebird College</title>
    
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
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand img {
            height: 50px;
            margin-right: 10px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .page-header h1 {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .page-header p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .programme-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .programme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .programme-title {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .programme-desc {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: opacity 0.3s;
        }
        
        .btn-view:hover {
            opacity: 0.9;
            color: white;
        }
        
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
            text-align: center;
        }
        
        .footer img {
            height: 50px;
            margin-bottom: 20px;
        }
        
        .back-btn {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/bluebird-logo.png" alt="Bluebird College">
            <span>Bluebird College</span>
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">Home</a>
            <a class="nav-link" href="postgraduate.php">Postgraduate</a>
            <a class="nav-link" href="contact.php">Contact</a>
        </div>
    </div>
</nav>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-mortarboard"></i> Undergraduate Programmes</h1>
        <p><?php echo $count; ?> programmes available • 3 years duration • Foundation year available</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <?php if (empty($programmes)): ?>
        <div class="alert alert-info text-center">
            No undergraduate programmes available at the moment.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($programmes as $prog): ?>
            <div class="col-md-6 col-lg-4">
                <div class="programme-card">
                    <h3 class="programme-title"><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                    <p class="programme-desc">
                        <?php 
                        $desc = $prog['Description'] ?? 'No description available.';
                        echo htmlspecialchars(mb_strimwidth($desc, 0, 120, "..."));
                        ?>
                    </p>
                    <a href="programme-details.php?id=<?php echo $prog['ProgrammeID']; ?>" class="btn-view">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Back Button -->
    <div class="text-center back-btn">
        