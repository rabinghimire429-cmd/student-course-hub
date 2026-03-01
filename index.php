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

<?php include 'header.php'; ?>

<div class="hero">
    <div class="container">
        <h1>Welcome to Bluebird College</h1>
        <p>Where Learning Comes to Life.</p>
        <a href="our-college.php" class="btn btn-primary me-2">About Us →</a>
        <a href="contact.php" class="btn btn-outline-light">Inquiry →</a>
    </div>
</div>

<div class="container my-5">
    <h2 class="text-center mb-5">Our Programmes</h2>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search programmes..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info text-center">No programmes found.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($programmes as $prog): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($prog['Image'] ?? 'https://via.placeholder.com/400x180') ?>" class="card-img-top" alt="<?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($prog['ProgrammeName']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($prog['Description'] ?? 'No description', 0, 100)) ?>...</p>
                            <a href="programme-details.php?id=<?= $prog['ProgrammeID'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>