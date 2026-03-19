<?php

require_once 'db.php';

// Fetch all staff members from database
$sql = "SELECT * FROM staff ORDER BY Name";
$stmt = $pdo->query($sql);
$staff = $stmt->fetchAll();

// Include the header
include 'header.php';
?>

<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .page-header h1 {
        font-size: 42px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .page-header p {
        font-size: 18px;
        opacity: 0.9;
    }
    
    /* Grid layout for staff cards */
    .staff-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        padding: 20px 0;
    }
    
    /* Individual staff card */
    .staff-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .staff-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    
    /* Staff image styles */
    .staff-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #667eea;
        margin: 0 auto 20px;
    }
    
    .staff-image-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        margin: 0 auto 20px;
    }
    
    /* Staff info styles */
    .staff-name {
        font-size: 20px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .staff-title {
        color: #667eea;
        font-size: 15px;
        font-weight: 500;
        margin-bottom: 10px;
    }
    
    .staff-department {
        background: #f0f0f0;
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 15px;
    }
    
    .staff-bio {
        color: #7f8c8d;
        font-size: 14px;
        line-height: 1.6;
        margin: 15px 0;
        flex-grow: 1;
    }
    
    /* Contact information styles */
    .staff-contact {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .staff-contact p {
        margin: 8px 0;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .staff-contact i {
        color: #667eea;
        font-size: 16px;
        width: 20px;
    }
    
    .contact-link {
        color: #667eea;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .contact-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .staff-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-people-fill"></i> Staff Directory</h1>
        <p>Meet our expert faculty and staff members</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <?php if (empty($staff)): ?>
        <div class="alert alert-info text-center">
            No staff members found.
        </div>
    <?php else: ?>
        <div class="staff-grid">
            <?php foreach ($staff as $member): ?>
            <div class="staff-card">
                <!-- Staff Image -->
                <?php if (!empty($member['photo'])): ?>
                    <img src="images/<?php echo htmlspecialchars(basename($member['photo'])); ?>"
                         alt="<?php echo htmlspecialchars($member['Name']); ?>" 
                         class="staff-image">
                <?php else: ?>git
                    <div class="staff-image-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Staff Info -->
                <h3 class="staff-name"><?php echo htmlspecialchars($member['Name']); ?></h3>
                <p class="staff-title"><?php echo htmlspecialchars($member['job_title'] ?? 'Faculty Member'); ?></p>
                
                <?php if (!empty($member['department'])): ?>
                    <span class="staff-department"><?php echo htmlspecialchars($member['department']); ?></span>
                <?php endif; ?>
                
                <!-- Bio -->
                <?php if (!empty($member['Bio'])): ?>
                    <p class="staff-bio"><?php echo htmlspecialchars(substr($member['Bio'], 0, 100)); ?>...</p>
                <?php endif; ?>
                
                <!-- Contact Information -->
                <div class="staff-contact">
                    <?php if (!empty($member['Email'])): ?>
                        <p>
                            <i class="bi bi-envelope-fill"></i>
                            <a href="mailto:<?php echo htmlspecialchars($member['Email']); ?>" class="contact-link">
                                <?php echo htmlspecialchars($member['Email']); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($member['Phone'])): ?>
                        <p>
                            <i class="bi bi-telephone-fill"></i>
                            <a href="tel:<?php echo htmlspecialchars($member['Phone']); ?>" class="contact-link">
                                <?php echo htmlspecialchars($member['Phone']); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Back to Home Button -->
    <div class="text-center mt-4 mb-5">
        <a href="index.php" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Back to Home
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>