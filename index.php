<?php
// =============================================
// Bluebird College - Homepage
// =============================================

// Include database connection
require_once 'db.php';

// Get counts for each level
$sql = "SELECT LevelID, COUNT(*) as count FROM Programmes WHERE is_published = 1 GROUP BY LevelID";
$stmt = $pdo->query($sql);
$counts = [];
while ($row = $stmt->fetch()) {
    $counts[$row['LevelID']] = $row['count'];
}

$ug_count = isset($counts[1]) ? $counts[1] : 0;
$pg_count = isset($counts[2]) ? $counts[2] : 0;


$alumni = [
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

// Include the header
include 'header.php';

// Check for logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo '<div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                You have been successfully logged out. Thank you for using Bluebird College Admin Panel.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>';
}
?>

<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .hero-title {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    
    .hero-subtitle {
        font-size: 20px;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .category-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 50px 40px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 30px;
        transition: transform 0.3s;
        height: 350px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .category-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    .category-box.pg {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    
    .category-box i {
        font-size: 48px;
        margin-bottom: 20px;
    }
    
    .category-box h2 {
        font-size: 32px;
        margin-bottom: 15px;
        font-weight: bold;
    }
    
    .category-box p {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 25px;
    }
    
    .category-btn {
        background-color: white;
        color: #667eea;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .category-btn:hover {
        background-color: transparent;
        color: white;
        border-color: white;
    }
    
    .info-section {
        background: white;
        padding: 60px 0;
        margin: 40px 0;
        border-radius: 10px;
        text-align: center;
    }
    
    .info-item {
        padding: 20px;
    }
    
    .info-item i {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .info-item h5 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    .info-item p {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .alumni-section {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf2 100%);
        padding: 60px 0;
        margin: 40px 0;
        border-radius: 10px;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .section-title h2 {
        font-size: 36px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .section-title p {
        font-size: 18px;
        color: #7f8c8d;
    }
    
    .alumni-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .alumni-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .alumni-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #667eea;
        margin: 0 auto 20px;
        display: block;
    }
    
    .alumni-quote {
        font-size: 15px;
        color: #555;
        font-style: italic;
        line-height: 1.6;
        margin-bottom: 20px;
        padding: 0 15px;
    }
    
    .alumni-name {
        font-size: 20px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .alumni-programme {
        color: #667eea;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 5px;
    }
    
    .alumni-position {
        color: #7f8c8d;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .alumni-grad {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        position: absolute;
        bottom: 20px;
        right: 20px;
        opacity: 0.2;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Welcome to Bluebird College</h1>
        <p class="hero-subtitle">
            Choose your path to success with our undergraduate and postgraduate 
            programmes designed for your future.
        </p>
    </div>
</section>

<!-- Main Content -->
<div class="container">
    <!-- Programme Boxes -->
    <div class="row">
        <!-- Undergraduate Box -->
        <div class="col-md-6">
            <div class="category-box">
                <i class="bi bi-mortarboard"></i>
                <h2>Undergraduate</h2>
                <p><?php echo $ug_count; ?> Programmes • 3 Years • Foundation Year Available</p>
                <p class="mb-4">Bachelor degrees in Computer Science, Business, Marketing, Finance and more.</p>
                <a href="undergraduate.php" class="category-btn">
                    View All Undergraduate <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Postgraduate Box -->
        <div class="col-md-6">
            <div class="category-box pg">
                <i class="bi bi-award"></i>
                <h2>Postgraduate</h2>
                <p><?php echo $pg_count; ?> Programmes • 1 Year • Part-time Options</p>
                <p class="mb-4">Master degrees in Advanced Computer Science, Data Science, MBA, and more.</p>
                <a href="postgraduate.php" class="category-btn">
                    View All Postgraduate <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Why Choose Us Section -->
    <div class="info-section">
        <div class="container">
            <h3 class="mb-5">Why Choose Bluebird College?</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-item">
                        <i class="bi bi-star-fill text-warning"></i>
                        <h5>Quality Education</h5>
                        <p>Industry-focused curriculum designed with employers</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <i class="bi bi-people-fill text-primary"></i>
                        <h5>Expert Faculty</h5>
                        <p>Learn from experienced industry professionals</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <i class="bi bi-briefcase-fill text-success"></i>
                        <h5>Career Support</h5>
                        <p>Internships and placement opportunities</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alumni Testimonials Section -->
    <div class="alumni-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="bi bi-chat-quote"></i> What Our Alumni Say</h2>
                <p>Success stories from our graduates</p>
            </div>
            
            <div class="row">
                <?php foreach ($alumni as $person): ?>
                <div class="col-md-4">
                    <div class="alumni-card">
                        <img src="<?php echo $person['image']; ?>" 
                             alt="<?php echo $person['name']; ?>" 
                             class="alumni-image">
                        
                        <div class="alumni-quote">
                            <i class="bi bi-quote"></i>
                            <?php echo $person['quote']; ?>
                            <i class="bi bi-quote"></i>
                        </div>
                        
                        <h4 class="alumni-name"><?php echo $person['name']; ?></h4>
                        <p class="alumni-programme"><?php echo $person['programme']; ?></p>
                        <p class="alumni-position"><?php echo $person['position']; ?></p>
                        
                        <img src="<?php echo $person['grad_image']; ?>" 
                             alt="Graduation" 
                             class="alumni-grad">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>