<?php
require_once 'db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$progID = (int)$_GET['id'];

// Fetch programme + leader details
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

// Fetch modules by year
$stmt = $pdo->prepare("
    SELECT pm.Year, m.ModuleName, m.Description, m.Image, s.Name AS ModuleLeader
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

// Register interest form handling
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
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Bluebird College</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Back to Programmes</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4"><?= htmlspecialchars($programme['ProgrammeName']) ?></h1>

    <?php if (!empty($programme['Image'])): ?>
        <img src="<?= htmlspecialchars($programme['Image']) ?>" class="img-fluid rounded mb-4" alt="Main image for <?= htmlspecialchars($programme['ProgrammeName']) ?> programme at Bluebird College">
    <?php endif; ?>

    <p><strong>Level:</strong> <?= htmlspecialchars($programme['LevelName']) ?></p>
    <p><strong>Programme Leader:</strong> <?= htmlspecialchars($programme['Leader']) ?></p>
    <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($programme['LeaderEmail'] ?? 'Not available') ?>">
        <?= htmlspecialchars($programme['LeaderEmail'] ?? 'Not available') ?>
    </a></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($programme['LeaderPhone'] ?? 'Not available') ?></p>
    <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($programme['LeaderBio'] ?? 'No bio available.')) ?></p>

    <h2 class="mt-5">Modules by Year</h2>
    <?php if (empty($modulesByYear)): ?>
        <p>No modules listed yet.</p>
    <?php else: ?>
        <?php foreach ($modulesByYear as $year => $yearModules): ?>
            <h4>Year <?= $year ?></h4>
            <ul class="list-group mb-4">
                <?php foreach ($yearModules as $mod): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($mod['ModuleName']) ?></strong> – Leader: <?= htmlspecialchars($mod['ModuleLeader']) ?>
                        <?php if (!empty($mod['Image'])): ?>
                            <img src="<?= htmlspecialchars($mod['Image']) ?>" class="img-fluid rounded mt-2" alt="Illustration for <?= htmlspecialchars($mod['ModuleName']) ?> module" style="max-height: 200px;">
                        <?php endif; ?>
                        <p class="mt-2"><?= htmlspecialchars($mod['Description'] ?? 'No description.') ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>