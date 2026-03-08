<?php
require_once 'db.php';
session_start();

// Get counts for each level
$sql = "SELECT LevelID, COUNT(*) as count FROM Programmes WHERE is_published = 1 GROUP BY LevelID";
$stmt = $pdo->query($sql);
$counts = [];
while ($row = $stmt->fetch()) {
    $counts[$row['LevelID']] = $row['count'];
}

$ug_count = $counts[1] ?? 0;
$pg_count = $counts[2] ?? 0;

// Get featured programmes (optional - show 3 popular ones)
$sql = "SELECT ProgrammeID, ProgrammeName, Description, Image, LevelID 
        FROM Programmes 
        WHERE is_published = 1 
        ORDER BY ProgrammeID DESC 
        LIMIT 6";
$stmt = $pdo->query($sql);
$featured = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College – Explore Programmes</title>
    
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
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .hero-title {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-size: 20px;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .programme-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .programme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .programme-level {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .level-ug {
            background-color: #3498db;
            color: white;
        }
        
        .level-pg {
            background-color: #9b59b6;
            color: white;
        }
        
        .programme-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .programme-desc {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: opacity 0.3s;
        }
        
        .btn-view:hover {
            opacity: 0.9;
            color: white;
        }
        
        .category-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .category-box h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }
        
        .category-box p {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 25px;
        }
        
        .category-btn {
            background-color: white;
            color: #667eea;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: transform 0.3s;
        }
        
        .category-btn:hover {
            transform: scale(1.05);
            color: #764ba2;
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
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/bluebird-logo.png" alt="Bluebird College">
            <span>Bluebird College</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="our-college.php">Our College</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="facilities.php">Facilities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff-directory.php">Staff</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Welcome to Bluebird College</h1>
        <p class="hero-subtitle">Where Learning Comes to Life. Explore our undergraduate and postgraduate programmes designed for your future success.</p>
    </div>
</section>

<!-- Main Content -->
<div class="container">

    <!-- Category Boxes - Two Main Sections -->
    <div class="row mb-5">
        <!-- Undergraduate Section -->
        <div class="col-md-6">
            <div class="category-box">
                <i class="bi bi-mortarboard" style="font-size: 48px;"></i>
                <h2>Undergraduate Programmes</h2>
                <p><?php echo $ug_count; ?> programmes available • 3-4 years duration • Foundation years available</p>
                <p>Bachelor degrees in Computer Science, Business, Marketing, Finance and more.</p>
                <a href="undergraduate.php" class="category-btn">
                    View All Undergraduate <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Postgraduate Section -->
        <div class="col-md-6">
            <div class="category-box" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);">
                <i class="bi bi-award" style="font-size: 48px;"></i>
                <h2>Postgraduate Programmes</h2>
                <p><?php echo $pg_count; ?> programmes available • 1-2 years duration • Research opportunities</p>
                <p>Master degrees and MBAs in Advanced Computer Science, Data Science, MBA and more.</p>
                <a href="postgraduate.php" class="category-btn">
                    View All Postgraduate <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Programmes Section -->
    <h2 class="section-title">Featured Programmes</h2>
    
    <div class="row">
        <?php if (empty($featured)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">No programmes available at the moment.</p>
            </div>
        <?php else: ?>
            <?php foreach ($featured as $prog): ?>
            <div class="col-md-4">
                <div class="programme-card">
                    <?php if ($prog['LevelID'] == 1): ?>
                        <span class="programme-level level-ug">Undergraduate</span>
                    <?php else: ?>
                        <span class="programme-level level-pg">Postgraduate</span>
                    <?php endif; ?>
                    
                    <h3 class="programme-title"><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                    
                    <p class="programme-desc">
                        <?php 
                        $desc = $prog['Description'] ?? 'No description available.';
                        echo htmlspecialchars(substr($desc, 0, 100)) . '...';
                        ?>
                    </p>
                    
                    <a href="programme-details.php?id=<?php echo $prog['ProgrammeID']; ?>" class="btn-view">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Quick Links -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i>
                <strong>Quick Links:</strong> 
                <a href="undergraduate.php" class="alert-link">Undergraduate Programmes</a> | 
                <a href="postgraduate.php" class="alert-link">Postgraduate Programmes</a> | 
                <a href="contact.php" class="alert-link">Contact Admissions</a>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <img src="images/bluebird-logo.png" alt="Bluebird College">
        <p>&copy; <?php echo date('Y'); ?> Bluebird College. All rights reserved.</p>
        <p class="small">CTEC2712 Web Application Development - Group Project</p>
        <p>
            <a href="undergraduate.php" class="text-white">Undergraduate</a> | 
            <a href="postgraduate.php" class="text-white">Postgraduate</a> | 
            <a href="contact.php" class="text-white">Contact</a>
        </p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>