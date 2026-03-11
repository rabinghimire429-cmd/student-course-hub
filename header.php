<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Basic page setup */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding-top: 80px; /* Space for fixed navbar */
        }
        
        /* Navbar styles */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand img {
            height: 50px;
            margin-right: 10px;
        }
        
        .navbar-brand {
            color: #2c3e50;
            font-weight: bold;
            font-size: 24px;
            text-decoration: none;
        }
        
        .nav-link {
            color: #2c3e50 !important;
            font-weight: 500;
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: #667eea !important;
        }
        
        /* Dropdown menu styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 10px 0;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            color: #2c3e50;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Footer styles */
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
        
        .footer p {
            margin: 5px 0;
            color: rgba(255,255,255,0.8);
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <!-- Logo and Brand Name -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/bluebird-logo.png" alt="Bluebird College Logo">
            Bluebird College
        </a>
        
        <!-- Mobile Menu Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                
                <!-- Our College Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="collegeDropdown" role="button" data-bs-toggle="dropdown">
                        Our College
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="collegeDropdown">
                        <li><a class="dropdown-item" href="our-college.php#vision-mission"><i class="bi bi-eye"></i> Vision & Mission</a></li>
                        <li><a class="dropdown-item" href="our-college.php#about-us"><i class="bi bi-building"></i> About Us</a></li>
                    </ul>
                </li>
                
                <!-- Facilities Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="facilitiesDropdown" role="button" data-bs-toggle="dropdown">
                        Facilities
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="facilitiesDropdown">
                        <li><a class="dropdown-item" href="facilities.php#eca"><i class="bi bi-trophy"></i> ECA Programmes</a></li>
                        <li><a class="dropdown-item" href="facilities.php#cca"><i class="bi bi-book"></i> CCA Programmes</a></li>
                        <li><a class="dropdown-item" href="facilities.php#sports"><i class="bi bi-basketball"></i> Sports Facilities</a></li>
                        <li><a class="dropdown-item" href="facilities.php#library"><i class="bi bi-journal"></i> Library</a></li>
                    </ul>
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
                
                <!-- Admin Login Link - Added here -->
                <li class="nav-item">
                    <a class="nav-link text-danger" href="admin/login.php">
                        <i class="bi bi-shield-lock"></i> Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>