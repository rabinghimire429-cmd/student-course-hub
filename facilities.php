<?php

// Include database connection if needed
require_once 'db.php';

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
    
    .facility-section {
        background: white;
        border-radius: 10px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        scroll-margin-top: 100px;
    }
    
    .section-title {
        color: #2c3e50;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 15px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .facility-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf2 100%);
        border-radius: 10px;
        padding: 25px;
        height: 100%;
        transition: transform 0.3s;
    }
    
    .facility-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .facility-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .facility-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .sport-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    .library-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .back-btn {
        margin: 40px 0;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-building"></i> Our Facilities</h1>
        <p>State-of-the-art facilities for holistic development</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <!-- ECA Programmes Section -->
    <div id="eca" class="facility-section">
        <h2 class="section-title"><i class="bi bi-trophy"></i> Extra-Curricular Activities (ECA)</h2>
        <p class="lead mb-4">Beyond academics, we believe in holistic development through various activities.</p>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="bi bi-mic"></i>
                    </div>
                    <h4>Public Speaking Club</h4>
                    <p>Develop confidence and communication skills through debates, presentations, and public speaking events.</p>
                    <img src="images/Public Speaking.jpg" alt="Public Speaking" class="facility-image">
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="bi bi-music-note"></i>
                    </div>
                    <h4>Music & Arts</h4>
                    <p>Join our band, choir, or art club. Annual cultural festival showcases student talents.</p>
                    <!-- Added music.jpg -->
                    <img src="images/music.jpg" alt="Music & Arts" class="facility-image">
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h4>Robotics Club</h4>
                    <p>Build and program robots. Participate in national and international competitions.</p>
                    <!-- Added robotics.jpg -->
                    <img src="images/robotics.jpg" alt="Robotics Club" class="facility-image">
                </div>
            </div>
        </div>
    </div>
    
    <!-- CCA Programmes Section -->
    <div id="cca" class="facility-section">
        <h2 class="section-title"><i class="bi bi-book"></i> Co-Curricular Activities (CCA)</h2>
        <p class="lead mb-4">Activities that complement your academic learning.</p>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h4>Tech Hackathons</h4>
                    <p>24-hour coding competitions where students build innovative solutions to real-world problems.</p>
                    <!-- Added Hacking.jpg -->
                    <img src="images/Hacking.jpg" alt="Tech Hackathons" class="facility-image">
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="facility-card">
                    <div class="facility-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h4>Business Case Competitions</h4>
                    <p>Solve real business challenges and present solutions to industry experts.</p>
                    <!-- Added business.jpg -->
                    <img src="images/business.jpg" alt="Business Case Competition" class="facility-image">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sports Facilities Section -->
    <div id="sports" class="facility-section">
        <h2 class="section-title"><i class="bi bi-basketball"></i> Sports Facilities</h2>
        <p class="lead mb-4">World-class sports infrastructure for physical fitness and team building.</p>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="facility-card">
                    <img src="images/Football.jpg" alt="Football Ground" class="sport-image">
                    <h4>Football Ground</h4>
                    <p>Full-size FIFA-standard football ground with floodlights for evening practice.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="facility-card">
                    <img src="images/Basketball.jpg" alt="Basketball Court" class="sport-image">
                    <h4>Basketball Court</h4>
                    <p>Indoor and outdoor basketball courts with professional flooring.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="facility-card">
                    <img src="images/classroom.jpg" alt="Indoor Sports" class="sport-image">
                    <h4>Indoor Sports Complex</h4>
                    <p>Badminton, table tennis, chess, and carrom facilities.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Library Section -->
    <div id="library" class="facility-section">
        <h2 class="section-title"><i class="bi bi-journal"></i> Library</h2>
        
        <div class="row">
            <div class="col-md-8">
                <div class="facility-card">
                    <h4>Knowledge Resource Center</h4>
                    <p>Our library houses over 50,000 books, 200+ journals, and access to online databases including IEEE, Springer, and JSTOR.</p>
                    <ul>
                        <li>24/7 study spaces</li>
                        <li>Digital library with e-books</li>
                        <li>Quiet zones for focused study</li>
                        <li>Group discussion rooms</li>
                        <li>Computer lab with research software</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-4">
                <img src="images/hero-classroom.png" alt="Library" class="library-image">
            </div>
        </div>
    </div>
    
    <!-- Back to Home Button -->
    <div class="text-center back-btn">
        <a href="index.php" class="btn btn-primary btn-lg">
            <i class="bi bi-house-door"></i> Back to Home
        </a>
    </div>
</div>

<?php include 'footer.php'; ?>