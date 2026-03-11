<?php
// =============================================
// Bluebird College - Gallery Page
// =============================================

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
    
    .gallery-category {
        margin-bottom: 40px;
    }
    
    .gallery-category h3 {
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }
    
    .gallery-item {
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .gallery-item:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .gallery-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
    }
    
    .gallery-caption {
        background: white;
        padding: 15px;
        text-align: center;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .gallery-tag {
        font-size: 12px;
        color: #667eea;
        margin-top: 5px;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-images"></i> Gallery</h1>
        <p>Moments captured at Bluebird College</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <!-- Graduation Section -->
    <div class="gallery-category">
        <h3><i class="bi bi-mortarboard"></i> Graduation Day</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="gallery-item">
                    <img src="images/Graduated.jpg" alt="Graduation Ceremony" class="gallery-image">
                    <div class="gallery-caption">
                        Class of 2024 Graduation
                        <div class="gallery-tag">Graduation</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Campus Life Section -->
    <div class="gallery-category">
        <h3><i class="bi bi-building"></i> Campus Life</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="gallery-item">
                    <img src="images/hero-classroom.png" alt="Classroom" class="gallery-image">
                    <div class="gallery-caption">
                        Modern Classrooms
                        <div class="gallery-tag">Campus</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="gallery-item">
                    <img src="images/classroom.jpg" alt="Lecture Hall" class="gallery-image">
                    <div class="gallery-caption">
                        Interactive Lectures
                        <div class="gallery-tag">Campus</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sports Section -->
    <div class="gallery-category">
        <h3><i class="bi bi-trophy"></i> Sports & Activities</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="gallery-item">
                    <img src="images/Football.jpg" alt="Football Match" class="gallery-image">
                    <div class="gallery-caption">
                        Inter-College Football Tournament
                        <div class="gallery-tag">Sports</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="gallery-item">
                    <img src="images/Basketball.jpg" alt="Basketball" class="gallery-image">
                    <div class="gallery-caption">
                        Basketball Championship
                        <div class="gallery-tag">Sports</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="gallery-item">
                    <img src="images/Public Speaking.jpg" alt="Public Speaking" class="gallery-image">
                    <div class="gallery-caption">
                        Public Speaking Competition
                        <div class="gallery-tag">Events</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to Home Button -->
    <div class="text-center mb-5">
        <a href="index.php" class="btn btn-primary">
            <i class="bi bi-house-door"></i> Back to Home
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>