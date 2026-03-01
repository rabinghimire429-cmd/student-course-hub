<?php
require_once 'db.php';
session_start();

// === Combined Filtering: Level + Search + Published Only ===
$levelFilter = $_GET['level'] ?? '';
$search = trim($_GET['search'] ?? '');

$whereParts = [];
$params = [];

// Level filter
if ($levelFilter === 'ug') {
    $whereParts[] = 'LevelID = 1';
} elseif ($levelFilter === 'pg') {
    $whereParts[] = 'LevelID = 2';
}

// Keyword search
if (!empty($search)) {
    $whereParts[] = '(ProgrammeName LIKE :search OR Description LIKE :search)';
    $params['search'] = "%$search%";
}

// Only show published programmes
$whereParts[] = 'is_published = 1';

$whereClause = empty($whereParts) ? '' : 'WHERE ' . implode(' AND ', $whereParts);

// Fetch programmes
$stmt = $pdo->prepare("
    SELECT ProgrammeID, ProgrammeName, LevelID, Description, Image
    FROM Programmes
    $whereClause
    ORDER BY ProgrammeName
");
$stmt->execute($params);
$programmes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College – Explore Programmes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top { height: 180px; object-fit: cover; }
        body { background-color: #f8f9fa; }
        .nav-link.active { font-weight: bold; color: #ffc107 !important; border-bottom: 2px solid #ffc107; }
        .navbar-brand img { max-height: 45px; width: auto; }
    </style>
</head>
<body>

<!-- Navbar with logo + Staff Directory link -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                <li class="nav-item">
                    <a class="nav-link <?= $levelFilter === '' ? 'active' : '' ?>" href="index.php">All Programmes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $levelFilter === 'ug' ? 'active' : '' ?>" href="index.php?level=ug">Undergraduate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $levelFilter === 'pg' ? 'active' : '' ?>" href="index.php?level=pg">Postgraduate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff-directory.php">Staff Directory</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-5">Explore Programmes at Bluebird College</h1>

    <!-- Search Form -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search programmes (e.g. Cyber Security, Business Management)" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info text-center">
            No programmes match your criteria — try different filters or search terms.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($programmes as $prog): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($prog['Image'] ?? 'https://via.placeholder.com/400x180?text=' . urlencode(substr($prog['ProgrammeName'], 0, 20))) ?>" 
                             class="card-img-top" 
                             alt="Visual representation of <?= htmlspecialchars($prog['ProgrammeName']) ?> programme at Bluebird College">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($prog['ProgrammeName']) ?></h5>
                            <p class="card-text flex-grow-1">
                                <?= htmlspecialchars(substr($prog['Description'] ?? 'No description available.', 0, 100)) ?>...
                            </p>
                            <a href="programme-details.php?id=<?= $prog['ProgrammeID'] ?>" class="btn btn-primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Footer with logo -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <img src="images/bluebird-logo.png" alt="Bluebird College logo" style="height: 60px; margin-bottom: 10px;">
        <p class="mb-0">© <?= date('Y') ?> Bluebird College. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>