<?php
require_once '../db.php';
session_start();

// Protect admin page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Mark as read (optional)
if (isset($_GET['mark_read'])) {
    $id = (int)$_GET['mark_read'];
    $pdo->prepare("UPDATE ContactMessages SET IsRead = 1 WHERE MessageID = ?")->execute([$id]);
    header('Location: view-inquiries.php');
    exit;
}

// Fetch all messages
$stmt = $pdo->query("SELECT * FROM ContactMessages ORDER BY SubmittedAt DESC");
$messages = $stmt->fetchAll();
?>

<?php include '../header.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Contact Inquiries</h1>

    <?php if (empty($messages)): ?>
        <div class="alert alert-info text-center">No messages yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?= date('d M Y H:i', strtotime($msg['SubmittedAt'])) ?></td>
                            <td><?= htmlspecialchars($msg['Name']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($msg['Email']) ?>"><?= htmlspecialchars($msg['Email']) ?></a></td>
                            <td><?= nl2br(htmlspecialchars(substr($msg['Message'], 0, 150))) ?>...</td>
                            <td>
                                <?php if ($msg['IsRead']): ?>
                                    <span class="badge bg-success">Read</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Unread</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$msg['IsRead']): ?>
                                    <a href="?mark_read=<?= $msg['MessageID'] ?>" class="btn btn-sm btn-success">Mark as Read</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<?php include '../footer.php'; ?>