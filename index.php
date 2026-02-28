<?php
require_once 'db.php';
session_start();

// === Combined Filtering: Level + Search + Published Only ===
$levelFilter = $_GET['level'] ?? '';
$search = trim($_GET['search'] ?? '');

$whereParts = [];
$params = [];

// Level filter (UG = 1, PG = 2)
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

// Only show published programmes to students
$whereParts[] = 'is_published = 1';

// Build WHERE clause
$whereClause = empty($whereParts) ? '' : 'WHERE ' . implode(' AND ', $whereParts);

// Fetch filtered programmes
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
    <title>Student Course Hub – Find Your Programme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-img-top { height: 180px; object-fit: cover; }
        body { background-color: #f8f9fa; }
        .nav-link.active { font-weight: bold; color: #ffc107 !important; border-bottom: 2px solid #ffc107; padding-bottom: 5px; }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Student Course Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Explore Our Programmes</h1>

    <!-- Search Form -->
    <form method="GET" class="mb-5">
        <div class="input-group input-group-lg">
            <input type="text" name="search" class="form-control" placeholder="Search programmes (e.g. Cyber Security, AI, Machine Learning)" 
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info text-center py-4">
            No programmes match your criteria<?php if ($levelFilter || $search): ?> — try different filters or search terms<?php endif; ?>.
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($programmes as $prog): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($prog['Image'])): ?>
                            <img src="<?= htmlspecialchars($prog['Image']) ?>" 
                                 class="card-img-top" 
                                 alt="Programme image: <?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x180?text=<?= urlencode(substr($prog['ProgrammeName'], 0, 20)) ?>" 
                                 class="card-img-top" 
                                 alt="Placeholder for <?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($prog['ProgrammeName']) ?></h5>
                            <p class="card-text flex-grow-1">
                                <?= htmlspecialchars(substr($prog['Description'] ?? 'No description available.', 0, 100)) ?>...
                            </p>
                            <a href="programme-details.php?id=<?= $prog['ProgrammeID'] ?>" 
                               class="btn btn-primary mt-auto">View Details</a>
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