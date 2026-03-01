<?php include 'header.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-5">Staff Directory</h1>

    <?php if (empty($staff)): ?>
        <div class="alert alert-info text-center">No staff members listed yet.</div>
    <?php else: ?>
        <div class="row g-4 justify-content-center">
            <?php foreach ($staff as $member): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 text-center shadow-sm">
                        <?php if (!empty($member['ProfileImage'])): ?>
                            <img src="<?= htmlspecialchars($member['ProfileImage']) ?>" class="staff-img mx-auto mt-3" alt="Profile photo of <?= htmlspecialchars($member['Name']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/120?text=<?= urlencode(substr($member['Name'], 0, 1)) ?>" class="staff-img mx-auto mt-3" alt="Placeholder for <?= htmlspecialchars($member['Name']) ?>">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($member['Name']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($member['JobTitle'] ?? 'Staff Member') ?></p>
                            <?php if (!empty($member['Email'])): ?>
                                <p><a href="mailto:<?= htmlspecialchars($member['Email']) ?>"><?= htmlspecialchars($member['Email']) ?></a></p>
                            <?php endif; ?>
                            <?php if (!empty($member['Phone'])): ?>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($member['Phone']) ?></p>
                            <?php endif; ?>
                            <p class="card-text small"><?= nl2br(htmlspecialchars($member['Bio'] ?? 'No bio available.')) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>