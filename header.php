<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- your custom style -->
</head>
<body>

<!-- Navbar (public version – admin can have different one if needed) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="../index.php">
            <img src="../images/bluebird-logo.png" alt="Bluebird College logo" class="me-2">
            Bluebird College
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Our College</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../our-college.php#vision-mission">Vision & Mission</a></li>
                        <li><a class="dropdown-item" href="../our-college.php#about-us">About Us</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Facilities</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../facilities.php#eca">ECA Programmes</a></li>
                        <li><a class="dropdown-item" href="../facilities.php#cca">CCA Programmes</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="../gallery.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="../contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="../staff-directory.php">Staff Directory</a></li>
            </ul>
        </div>
    </div>
</nav>

<div style="padding-top: 80px;"></div> <!-- Spacer for fixed navbar -->