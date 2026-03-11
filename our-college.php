<?php

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
    
    .college-section {
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
    
    .vision-mission-box {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf2 100%);
        border-radius: 10px;
        padding: 30px;
        margin: 20px 0;
        height: 100%;
    }
    
    .vision-icon, .mission-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 20px;
    }
    
    .stat-box {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 36px;
        font-weight: bold;
        color: #667eea;
    }
    
    .stat-label {
        color: #7f8c8d;
        font-size: 14px;
    }
    
    .about-image {
        width: 100%;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .contact-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-building"></i> Our College</h1>
        <p>Excellence in Education Since 2000</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <!-- Vision & Mission Section -->
    <div id="vision-mission" class="college-section">
        <h2 class="section-title"><i class="bi bi-eye"></i> Vision & Mission</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="vision-mission-box text-center">
                    <div class="vision-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>To be a leading institution in innovative education, fostering global leaders and thinkers who shape the future of business and technology.</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="vision-mission-box text-center">
                    <div class="mission-icon">
                        <i class="bi bi-flag"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide quality education that combines academic excellence with practical skills, promoting ethical values, innovation, and community service.</p>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">2000</div>
                    <div class="stat-label">Founded</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">Alumni</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Expert Faculty</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Employment Rate</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- About Us Section -->
    <div id="about-us" class="college-section">
        <h2 class="section-title"><i class="bi bi-info-circle"></i> About Us</h2>
        
        <div class="row">
            <div class="col-md-8">
                <p class="lead">Bluebird College is a premier UK university offering a range of undergraduate and postgraduate degrees in computer science and business. Founded in 2000, we have a commitment to excellence in teaching and research.</p>
                
                <h4 class="mt-4">Our History</h4>
                <p>Established in the year 2000, Bluebird College started with just 50 students and 5 faculty members. Today, we have grown into a renowned institution with over 2000 students and 50 expert faculty members from around the world.</p>
                
                <h4 class="mt-4">Our Values</h4>
                <ul>
                    <li><strong>Excellence:</strong> Striving for the highest standards in education</li>
                    <li><strong>Innovation:</strong> Embracing new technologies and teaching methods</li>
                    <li><strong>Integrity:</strong> Maintaining ethical practices in all we do</li>
                    <li><strong>Inclusivity:</strong> Welcoming students from all backgrounds</li>
                </ul>
                
                <h4 class="mt-4">Accreditations</h4>
                <p>Bluebird College is accredited by the British Accreditation Council and all our degrees are recognized by the UK government and international employers.</p>
            </div>
            
            <div class="col-md-4">
                <img src="images/classroom.jpg" alt="Bluebird College Campus" class="about-image">
                <p class="text-center text-muted">Our Main Campus</p>
                
                <div class="contact-box">
                    <h5><i class="bi bi-geo-alt"></i> Location</h5>
                    <p>123 Education Lane<br>London, EC1A 1BB<br>United Kingdom</p>
                    
                    <h5><i class="bi bi-telephone"></i> Contact</h5>
                    <p>+44 (0)20 1234 5678<br>info@bluebird-college.ac.uk</p>
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