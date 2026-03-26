<?php
// =============================================
// Bluebird College - Homepage
// Refactored Version (Better Structure + Reusable Functions)
// =============================================

// Include database connection
require_once 'db.php';

/**
 * Escape output (prevents XSS attacks)
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Fetch programme counts grouped by LevelID
 */
function getProgrammeCounts($pdo)
{
    $sql = "
        SELECT LevelID, COUNT(*) as count 
        FROM Programmes 
        WHERE is_published = 1 
        GROUP BY LevelID
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $counts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $levelId = (int) $row['LevelID'];
        $counts[$levelId] = (int) $row['count'];
    }

    return $counts;
}

/**
 * Get count safely by level
 */
function getCountByLevel($counts, $levelId)
{
    return $counts[$levelId] ?? 0;
}

// Fetch counts using helper functions
$counts   = getProgrammeCounts($pdo);
$ug_count = getCountByLevel($counts, 1);
$pg_count = getCountByLevel($counts, 2);

/**
 * Alumni Data (Static for now)
 * TODO: Move to database in future
 */
function getAlumniData()
{
    return [
        [
            'name' => 'Ayush Adhikari',
            'programme' => 'BSc Computer Science (2024)',
            'position' => 'Software Engineer at Google',
            'quote' => 'Bluebird College gave me the perfect foundation for my career. The hands-on projects and industry connections helped me land my dream job at Google.',
            'image' => 'images/alumni1.jpg',
            'grad_image' => 'images/Graduated.jpg'
        ],
        [
            'name' => 'Manisha Karki',
            'programme' => 'MBA (2023)',
            'position' => 'Business Analyst at Deloitte',
            'quote' => 'The MBA programme at Bluebird transformed my career. The faculty expertise and networking opportunities were invaluable for my professional growth.',
            'image' => 'images/alumni2.jpg',
            'grad_image' => 'images/Graduated.jpg'
        ],
        [
            'name' => 'Kartik Shah',
            'programme' => 'MSc Data Science (2024)',
            'position' => 'Data Scientist at Amazon',
            'quote' => 'The data science curriculum is cutting-edge and industry-relevant. I was job-ready from day one after graduation.',
            'image' => 'images/alumni3.jpg',
            'grad_image' => 'images/Graduated.jpg'
        ]
    ];
}

$alumni = getAlumniData();

// Include header
include 'header.php';

/**
 * Display logout message
 */
function showLogoutMessage()
{
    if (!empty($_GET['logout']) && $_GET['logout'] === 'success') {
        echo '<div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    You have been successfully logged out. Thank you for using Bluebird College Admin Panel.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              </div>';
    }
}

// Call function
showLogoutMessage();
?>