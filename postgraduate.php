<?php
require_once 'db.php';
session_start();

$search = trim($_GET['search'] ?? '');

$whereParts = ['LevelID = 2', 'is_published = 1'];
$params = [];

if (!empty($search)) {
    $whereParts[] = '(ProgrammeName LIKE :search OR Description LIKE :search)';
    $params['search'] = "%$search%";
}

$whereClause = 'WHERE ' . implode(' AND ', $whereParts);

$stmt = $pdo->prepare("
    SELECT ProgrammeID, ProgrammeName, Description, Image
    FROM Programmes
    $whereClause
    ORDER BY ProgrammeName
");
$stmt->execute($params);
$programmes = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>

<div class="container my-5 pt-5">
    <h1 class="text-center mb-5">Postgraduate Programmes</h1>

    <form method="GET" class="mb-5">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search postgraduate programmes..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (empty($programmes)): ?>
        <div class="alert alert-info text-center">No postgraduate programmes match your search.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($programmes as $prog): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($prog['Image'] ?? 'https://via.placeholder.com/400x180') ?>" 
                             class="card-img-top" alt="<?= htmlspecialchars($prog['ProgrammeName']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($prog['ProgrammeName']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($prog['Description'] ?? 'No description', 0, 120)) ?>...</p>
                            <a href="programme-details.php?id=<?= $prog['ProgrammeID'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>