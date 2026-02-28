<?php
require_once 'db.php';
session_start(); // for future messages

// Fetch all visible programmes (we'll add publish filter later)
$stmt = $pdo->prepare("
    SELECT ProgrammeID, ProgrammeName, Description, Image
    FROM Programmes
    ORDER BY ProgrammeName
");
$stmt->execute();
$programmes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Course Hub – Find Your Programme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-img-top { height: 180px; object-fit: cover; }
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Student Course Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">All Programmes</a></li>
                <!-- Filters/search added in next steps -->
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-5">Explore Our Programmes</h1>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info text-center">No programmes available at the moment.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($programmes as $prog): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($prog['Image'])): ?>
                            <img src="<?= htmlspecialchars($prog['Image']) ?>" class="card-img-top" alt="Image for <?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x180?text=<?= urlencode($prog['ProgrammeName']) ?>" class="card-img-top" alt="Placeholder for <?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($prog['ProgrammeName']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($prog['Description'] ?? 'No description available.', 0, 100)) ?>...</p>
                            <a href="programme-details.php?id=<?= $prog['ProgrammeID'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>