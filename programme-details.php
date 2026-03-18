<?php
require_once 'db.php';
session_start();

// Check if programme ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$progID = (int)$_GET['id'];

// Get programme details with level and leader details
$sql = "SELECT p.*, 
               l.LevelName,
               s.Name as LeaderName, 
               s.job_title as LeaderTitle, 
               s.Email as LeaderEmail,
               s.photo as LeaderPhoto,
               s.Bio as LeaderBio
        FROM Programmes p
        JOIN Levels l ON p.LevelID = l.LevelID
        LEFT JOIN staff s ON p.ProgrammeLeaderID = s.StaffID
        WHERE p.ProgrammeID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$progID]);
$programme = $stmt->fetch();

// If programme not found, redirect
if (!$programme) {
    header('Location: index.php');
    exit;
}

// Get modules for this programme by year
$sql = "SELECT pm.Year, m.ModuleName, m.Description, s.Name as ModuleLeader
        FROM programme_modules pm
        JOIN Modules m ON pm.ModuleID = m.ModuleID
        LEFT JOIN staff s ON m.module_leader_id = s.StaffID
        WHERE pm.ProgrammeID = ?
        ORDER BY pm.Year, m.ModuleName";
$stmt = $pdo->prepare($sql);
$stmt->execute([$progID]);
$modules = $stmt->fetchAll();

// Group modules by year
$modulesByYear = [];
foreach ($modules as $m) {
    $modulesByYear[$m['Year']][] = $m;
}

// Handle interest registration
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($name) || empty($email)) {
        $msg = '<div class="alert alert-danger">Please fill in all fields.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = '<div class="alert alert-danger">Please enter a valid email address.</div>';
    } else {
        try {
            $sql = "INSERT INTO interested_students (ProgrammeID, StudentName, Email) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$progID, $name, $email]);
            $msg = '<div class="alert alert-success">Thank you! Your interest has been registered. We will contact you soon.</div>';
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-warning">You have already registered interest in this programme.</div>';
        }
    }
}

// Set duration and career info based on level
$duration = ($programme['LevelID'] == 1) ? '3 years (full-time) or 4 years with foundation year' : '1 year (full-time) or 2 years part-time';
$studyMode = ($programme['LevelID'] == 1) ? 'Full-time, Part-time, Sandwich placement' : 'Full-time, Part-time, Distance learning';
$entryRequirements = ($programme['LevelID'] == 1) 
    ? 'A-Levels: BBB - BBC, IB: 28-30 points, BTEC: DDM. IELTS 6.0 (no less than 5.5)' 
    : '2:2 or above in relevant undergraduate degree. IELTS 6.5 (no less than 6.0)';

// Career opportunities based on programme name
$careerPaths = [
    'Computer Science' => [
        'Software Developer', 'Systems Analyst', 'IT Consultant', 'Database Administrator', 'Web Developer'
    ],
    'Software Engineering' => [
        'Software Engineer', 'Application Developer', 'Quality Assurance Engineer', 'DevOps Engineer'
    ],
    'Data Science' => [
        'Data Scientist', 'Data Analyst', 'Business Intelligence Analyst', 'Machine Learning Engineer'
    ],
    'Cyber Security' => [
        'Security Analyst', 'Ethical Hacker', 'Security Consultant', 'Information Security Manager'
    ],
    'Artificial Intelligence' => [
        'AI Engineer', 'Machine Learning Specialist', 'Research Scientist', 'Robotics Engineer'
    ],
    'Business Management' => [
        'Business Analyst', 'Project Manager', 'Operations Manager', 'Management Consultant'
    ],
    'Marketing' => [
        'Marketing Manager', 'Digital Marketing Specialist', 'Brand Manager', 'Social Media Manager'
    ],
    'Finance' => [
        'Financial Analyst', 'Investment Banker', 'Accountant', 'Financial Advisor'
    ],
    'Human Resource Management' => [
        'HR Manager', 'Recruitment Consultant', 'Training Coordinator', 'HR Business Partner'
    ],
    'International Business' => [
        'International Business Developer', 'Export Manager', 'Global Supply Chain Manager'
    ],
    'Accounting' => [
        'Accountant', 'Auditor', 'Financial Controller', 'Tax Consultant'
    ],
    'MBA' => [
        'Senior Manager', 'Director', 'CEO', 'Business Consultant', 'Entrepreneur'
    ]
];

// Find matching career paths
$foundCareers = [];
foreach ($careerPaths as $key => $careers) {
    if (stripos($programme['ProgrammeName'], $key) !== false) {
        $foundCareers = $careers;
        break;
    }
}

// Default careers if no match found
if (empty($foundCareers)) {
    $foundCareers = [
        'Business Analyst', 'Project Manager', 'Consultant', 'Manager', 'Entrepreneur'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($programme['ProgrammeName']); ?> - Bluebird College</title>
    
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
        
        .programme-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        
        .programme-title {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .programme-level {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .info-icon {
            font-size: 24px;
            color: #667eea;
            margin-right: 10px;
        }
        
        .badge-info {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 2px;
            display: inline-block;
        }
        
        .career-badge {
            background: #e8f5e9;
            color: #388e3c;
            padding: 8px 15px;
            border-radius: 30px;
            margin: 5px;
            display: inline-block;
            font-size: 14px;
        }
        
        .module-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .module-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .module-leader {
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .leader-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        
        .interest-form {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
        }
        
        .btn-register:hover {
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="undergraduate.php">Undergraduate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="postgraduate.php">Postgraduate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Programme Header -->
<section class="programme-header">
    <div class="container">
        <div class="programme-level">
            <i class="bi bi-mortarboard"></i> 
            <?php echo htmlspecialchars($programme['LevelName']); ?> Programme
        </div>
        <h1 class="programme-title"><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h1>
        <p class="lead"><?php echo htmlspecialchars(substr($programme['Description'] ?? '', 0, 200)); ?>...</p>
    </div>
</section>
