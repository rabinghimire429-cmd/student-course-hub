<?php
require_once 'db.php';
session_start();

// === Combined Filtering: Level + Search + Published Only ===
$levelFilter = $_GET['level'] ?? ''; // '' = all, 'ug' = undergraduate, 'pg' = postgraduate
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
        .nav-link.active { font-weight: bold; color: #0d6efd !important; border-bottom: 2px solid #0d6efd; }
        .navbar-brand img { max-height: 45px; width: auto; }
        .hero { background: linear-gradient(rgba(13,110,253,0.5), rgba(13,110,253,0.5)), url('images/hero-classroom.jpeg') center/cover; color: white; padding: 150px 0; text-align: center; }
        .hero h1 { font-size: 3.5rem; font-weight: bold; }
        .hero p { font-size: 1.6rem; }
        footer { background-color: #004aad; color: white; }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- Hero section -->
<section class="hero">
    <div class="container">
        <h1>Welcome to Bluebird College</h1>
        <p>Where Learning Comes to Life.</p>
        <a href="our-college.php" class="btn btn-primary me-2">About Us →</a>
        <a href="contact.php" class="btn btn-outline-light">Inquiry →</a>
    </div>
</section>

<div class="container my-5">
    <h2 class="text-center mb-5">
        <?php if ($levelFilter === 'ug'): ?>
            Undergraduate Programmes
        <?php elseif ($levelFilter === 'pg'): ?>
            Postgraduate Programmes
        <?php else: ?>
            All Programmes
        <?php endif; ?>
    </h2>

    <!-- Search Form -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="hidden" name="level" value="<?= htmlspecialchars($levelFilter) ?>">
            <input type="text" name="search" class="form-control" placeholder="Search programmes..." value="<?= htmlspecialchars($search) ?>">
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

<?php include 'footer.php'; ?>