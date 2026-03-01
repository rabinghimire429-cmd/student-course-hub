<?php
require_once 'db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$progID = (int)$_GET['id'];

// Fetch programme and leader
$stmt = $pdo->prepare("
    SELECT p.ProgrammeName, p.Description, p.Image, l.LevelName,
           s.Name AS Leader, s.Email AS LeaderEmail, s.Phone AS LeaderPhone, s.Bio AS LeaderBio, s.ProfileImage AS LeaderImage
    FROM Programmes p
    JOIN Levels l ON p.LevelID = l.LevelID
    JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID
    WHERE p.ProgrammeID = ?
");
$stmt->execute([$progID]);
$programme = $stmt->fetch();

if (!$programme) {
    header('Location: index.php');
    exit;
}

// Fetch modules by year (DISTINCT prevents duplicates)
$stmt = $pdo->prepare("
    SELECT DISTINCT pm.Year, m.ModuleName, m.Description, m.Image, s.Name AS ModuleLeader
    FROM ProgrammeModules pm
    JOIN Modules m ON pm.ModuleID = m.ModuleID
    JOIN Staff s ON m.ModuleLeaderID = s.StaffID
    WHERE pm.ProgrammeID = ?
    ORDER BY pm.Year, m.ModuleName
");
$stmt->execute([$progID]);
$modules = $stmt->fetchAll();

$modulesByYear = [];
foreach ($modules as $m) {
    $modulesByYear[$m['Year']][] = $m;
}

// Register interest form
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email) VALUES (?, ?, ?)");
            $stmt->execute([$progID, $name, $email]);
            $msg = '<div class="alert alert-success">Interest registered successfully!</div>';
        } catch (PDOException $e) {
            $msg = '<div class="alert alert-warning">You have already registered interest in this programme.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Please provide a valid name and email.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebird College – <?= htmlspecialchars($programme['ProgrammeName']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand img { max-height: 45px; width: auto; }
        footer img { max-height: 60px; width: auto; }
        .module-img { max-height: 180px; object-fit: cover; }
        .leader-img { max-width: 150px; border-radius: 50%; }
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
                    <a class="nav-link" href="index.php">Back to Programmes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff-directory.php">Staff Directory</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4"><?= htmlspecialchars($programme['ProgrammeName']) ?></h1>

    <?php if (!empty($programme['Image'])): ?>
        <img src="<?= htmlspecialchars($programme['Image']) ?>" class="img-fluid rounded mb-4" alt="Main image for <?= htmlspecialchars($programme['ProgrammeName']) ?>">
    <?php endif; ?>

    <p><strong>Level:</strong> <?= htmlspecialchars($programme['LevelName']) ?></p>

    <h5>Programme Leader</h5>
    <p><strong>Name:</strong> <?= htmlspecialchars($programme['Leader']) ?></p>
    <?php if (!empty($programme['LeaderImage'])): ?>
        <img src="<?= htmlspecialchars($programme['LeaderImage']) ?>" class="leader-img mb-3" alt="Profile photo of <?= htmlspecialchars($programme['Leader']) ?>">
    <?php endif; ?>
    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($programme['LeaderEmail'] ?? 'N/A') ?>">
        <?= htmlspecialchars($programme['LeaderEmail'] ?? 'N/A') ?>
    </a></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($programme['LeaderPhone'] ?? 'N/A') ?></p>
    <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($programme['LeaderBio'] ?? 'No bio available.')) ?></p>

    <h2 class="mt-5">Modules by Year</h2>
    <?php if (empty($modulesByYear)): ?>
        <p class="text-muted">No modules listed yet.</p>
    <?php else: ?>
        <?php foreach ($modulesByYear as $year => $yearModules): ?>
            <h4>Year <?= $year ?></h4>
            <div class="row g-4">
                <?php foreach ($yearModules as $mod): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($mod['Image'])): ?>
                                <img src="<?= htmlspecialchars($mod['Image']) ?>" class="card-img-top module-img" alt="Module image: <?= htmlspecialchars($mod['ModuleName']) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($mod['ModuleName']) ?></h5>
                                <p class="card-text"><strong>Leader:</strong> <?= htmlspecialchars($mod['ModuleLeader']) ?></p>
                                <p class="card-text"><?= htmlspecialchars($mod['Description'] ?? 'No description.') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2 class="mt-5">Register Your Interest</h2>
    <?= $msg ?>
    <form method="POST" class="border p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register Interest</button>
    </form>
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