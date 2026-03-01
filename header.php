<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand img { max-height: 45px; width: auto; }
        .nav-link { color: #0d6efd !important; }
        .nav-link:hover, .nav-link.active { color: #0056b3 !important; border-bottom: 2px solid #0056b3; }
        .hero { background: linear-gradient(rgba(13,110,253,0.5), rgba(13,110,253,0.5)), url('images/hero-classroom.jpeg') center/cover; color: white; padding: 150px 0; text-align: center; }
        .hero h1 { font-size: 3.5rem; font-weight: bold; }
        .hero p { font-size: 1.6rem; }
        footer { background-color: #004aad; color: white; }
    </style>
</head>
<body>

<!-- Clean Navbar – no extras -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/bluebird-logo.png" alt="Bluebird College logo" class="me-2">
            Bluebird College
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Our College</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="our-college.php#vision-mission">Vision & Mission</a></li>
                        <li><a class="dropdown-item" href="our-college.php#about-us">About Us</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Facilities</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="facilities.php#eca">ECA Programmes</a></li>
                        <li><a class="dropdown-item" href="facilities.php#cca">CCA Programmes</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Programmes</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?level=ug">Undergraduate</a></li>
                        <li><a class="dropdown-item" href="index.php?level=pg">Postgraduate</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="staff-directory.php">Staff Directory</a></li>
            </ul>
        </div>
    </div>
</nav>