<?php
require_once 'db.php';  // include connection
session_start();        // for later (e.g., logged-in messages)

// Fetch all programmes (we'll add visibility filter later)
$stmt = $pdo->prepare("SELECT ProgrammeID, ProgrammeName, LevelID, Description, Image 
                       FROM Programmes 
                       ORDER BY ProgrammeName");
$stmt->execute();
$programmes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Course Hub - Explore Programmes</title>
    <!-- Bootstrap for responsive/mobile-friendly design -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top { height: 180px; object-fit: cover; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Student Course Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Undergraduate</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Postgraduate</a></li>
                <li class="nav-item"><a class="nav-link" href="register-interest.php">Register Interest</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Available Programmes</h1>
    
    <div class="row">
        <?php if (empty($programmes)): ?>
            <p class="text-center">No programmes available yet.</p>
        <?php else: ?>
            <?php foreach ($programmes as $prog): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($prog['Image'])): ?>
                            <img src="<?= htmlspecialchars($prog['Image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($prog['ProgrammeName']) ?> programme">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x180?text=Programme+Image" class="card-img-top" alt="Placeholder for <?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($prog['ProgrammeName']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($prog['Description'] ?? 'No description available.', 0, 120)) ?>...</p>
                            <a href="programme-details.php?id=<?= $prog['ProgrammeID'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>