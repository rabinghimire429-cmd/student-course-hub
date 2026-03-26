<?php
// =============================================
// Bluebird College - Gallery Page
// =============================================

// Include the header
include 'header.php';
?>



<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-images"></i> Gallery</h1>
        <p>Moments captured at Bluebird College</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
  
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