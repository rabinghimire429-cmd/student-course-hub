<?php
require_once 'db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$progID = (int)$_GET['id'];

// Get programme + leader + level
$stmt = $pdo->prepare("
    SELECT p.ProgrammeName, p.Description, p.Image, l.LevelName, s.Name AS Leader
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

// Get modules grouped by year
$stmt = $pdo->prepare("
    SELECT pm.Year, m.ModuleName, m.Description, s.Name AS ModuleLeader
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

// Handle register interest
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$progID, $name, $email]);
            $msg = '<div class="alert alert-success">Interest registered! You will receive updates.</div>';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // duplicate key
                $msg = '<div class="alert alert-warning">You have already registered interest in this programme.</div>';
            } else {
                $msg = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
    } else {
        $msg = '<div class="alert alert-danger">Please enter a valid name and email.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($programme['ProgrammeName']) ?> – Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <li class="nav-item"><a class="nav-link" href="index.php">Back to Programmes</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="mb-4"><?= htmlspecialchars($programme['ProgrammeName']) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <p><strong>Level:</strong> <?= htmlspecialchars($programme['LevelName']) ?></p>
            <p><strong>Programme Leader:</strong> <?= htmlspecialchars($programme['Leader']) ?></p>
            <p><?= nl2br(htmlspecialchars($programme['Description'] ?? 'No detailed description available.')) ?></p>

            <?php if (!empty($programme['Image'])): ?>
                <img src="<?= htmlspecialchars($programme['Image']) ?>" class="img-fluid rounded mb-4" alt="Visual for <?= htmlspecialchars($programme['ProgrammeName']) ?>">
            <?php endif; ?>
        </div>
    </div>

    <h2 class="mt-5">Modules by Year</h2>
    <?php if (empty($modulesByYear)): ?>
        <p>No modules listed yet.</p>
    <?php else: ?>
        <?php foreach ($modulesByYear as $year => $yearModules): ?>
            <h4>Year <?= $year ?></h4>
            <div class="accordion mb-4" id="year<?= $year ?>">
                <?php foreach ($yearModules as $idx => $mod): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?= $idx > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#mod<?= $year ?>-<?= $idx ?>">
                                <?= htmlspecialchars($mod['ModuleName']) ?> – Leader: <?= htmlspecialchars($mod['ModuleLeader']) ?>
                            </button>
                        </h2>
                        <div id="mod<?= $year ?>-<?= $idx ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>">
                            <div class="accordion-body">
                                <?= htmlspecialchars($mod['Description'] ?? 'No description.') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h2 class="mt-5">Register Your Interest</h2>
    <?= $msg ?? '' ?>
    <form method="post" class="border p-4 bg-white rounded">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>