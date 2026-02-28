<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all interests
$stmt = $pdo->query("
    SELECT p.ProgrammeName, i.StudentName, i.Email, i.RegisteredAt
    FROM InterestedStudents i
    JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
    ORDER BY p.ProgrammeName, i.RegisteredAt DESC
");
$interests = $stmt->fetchAll();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="interested_students_' . date('Y-m-d') . '.csv"');

// Output CSV
$output = fopen('php://output', 'w');
fputcsv($output, ['Programme', 'Student Name', 'Email', 'Registered At']);

foreach ($interests as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>