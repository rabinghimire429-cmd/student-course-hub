<?php
// =============================================
// Bluebird College - Contact Page
// =============================================

// Include database connection
require_once 'db.php';

// Start session for messages
session_start();

// Initialize variables
$success = '';
$error = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        
        // Insert into database
        try {
            $sql = "INSERT INTO contactmessages (Name, Email, Message, IsRead) VALUES (?, ?, ?, 0)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $message]);
            
            $success = 'Your message has been sent successfully! We will get back to you soon.';
            
        } catch (PDOException $e) {
            $error = 'Sorry, there was an error sending your message. Please try again.';
            // For debugging: echo $e->getMessage();
        }
    }
}

// Include the header
include 'header.php';
?>

<style>
    .page-header {
        background: linear-gradient(135deg, #6c5ce7, #a29bfe);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-top: 0;
        margin-bottom: 40px;
    }
    
    .contact-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 50px;
    }
    
    .contact-form {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .contact-form h3 {
        color: #333;
        margin-bottom: 30px;
        font-size: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #6c5ce7;
        outline: none;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #6c5ce7, #a29bfe);
        color: white;
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        width: 100%;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
    }
    
    .contact-info {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .contact-info h3 {
        color: #333;
        margin-bottom: 30px;
        font-size: 24px;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .info-item i {
        font-size: 24px;
        color: #6c5ce7;
        width: 30px;
    }
    
    .info-item h4 {
        color: #555;
        margin-bottom: 5px;
        font-size: 18px;
    }
    
    .info-item p {
        color: #666;
        line-height: 1.6;
    }
    
    .map-container {
        grid-column: span 2;
        height: 400px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .back-btn {
        text-align: center;
        margin: 40px 0;
    }
    
    .back-btn a {
        display: inline-block;
        padding: 12px 30px;
        background: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s;
    }
    
    .back-btn a:hover {
        background: #5a6268;
    }
    
    @media (max-width: 768px) {
        .contact-container {
            grid-template-columns: 1fr;
        }
        
        .map-container {
            grid-column: span 1;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1><i class="bi bi-envelope"></i> Contact Us</h1>
        <p>Get in touch with us for any inquiries</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    
    <!-- Success/Error Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Contact Grid -->
    <div class="contact-container">
        
        <!-- Contact Form -->
        <div class="contact-form">
            <h3><i class="bi bi-chat-dots"></i> Send us a Message</h3>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="bi bi-send"></i> Send Message
                </button>
            </form>
        </div>
        
        <!-- Contact Information -->
        <div class="contact-info">
            <h3><i class="bi bi-info-circle"></i> Contact Information</h3>
            
            <div class="info-item">
                <i class="bi bi-geo-alt-fill"></i>
                <div>
                    <h4>Address</h4>
                    <p>123 Education Lane<br>London, EC1A 1BB<br>United Kingdom</p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <h4>Phone</h4>
                    <p>+44 55447800</p>