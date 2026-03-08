<?php
// email-functions.php - Email handling functions

function sendEmail($to, $subject, $message, $from = "noreply@bluebird-college.ac.uk") {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Bluebird College <" . $from . ">\r\n";
    $headers .= "Reply-To: admissions@bluebird-college.ac.uk\r\n";
    
    // In production, use mail() or PHPMailer
    // return mail($to, $subject, $message, $headers);
    
    // For development, log to file
    $log = "To: $to\nSubject: $subject\nMessage: $message\n---\n";
    file_put_contents(__DIR__ . '/../logs/emails.log', $log, FILE_APPEND);
    
    return true;
}

function sendNewInquiryNotification($inquiry) {
    $admin_email = "admin@bluebird-college.ac.uk";
    $subject = "New Contact Inquiry from " . $inquiry['Name'];
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .inquiry { background: #f4f4f4; padding: 20px; border-radius: 5px; }
        </style>
    </head>
    <body>
        <h2>New Contact Inquiry</h2>
        <div class='inquiry'>
            <p><strong>Name:</strong> {$inquiry['Name']}</p>
            <p><strong>Email:</strong> {$inquiry['Email']}</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($inquiry['Message']) . "</p>
            <p><small>Received: " . date('Y-m-d H:i:s') . "</small></p>
        </div>
        <p>
            <a href='http://localhost/student-course-hub/admin/view-inquiries.php'>View in Admin Panel</a>
        </p>
    </body>
    </html>
    ";
    
    return sendEmail($admin_email, $subject, $message);
}

function sendAutoReply($inquiry) {
    $subject = "Thank you for contacting Bluebird College";
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; }
            .header { background: #004aad; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Bluebird College</h2>
            </div>
            <div class='content'>
                <h3>Dear {$inquiry['Name']},</h3>
                
                <p>Thank you for contacting Bluebird College. We have received your inquiry and will respond as soon as possible.</p>
                
                <p><strong>Your message:</strong></p>
                <p>" . nl2br($inquiry['Message']) . "</p>
                
                <p>In the meantime, you may find the following resources helpful:</p>
                <ul>
                    <li><a href='http://localhost/student-course-hub/our-college.php'>About Our College</a></li>
                    <li><a href='http://localhost/student-course-hub/programmes.php'>Our Programmes</a></li>
                    <li><a href='http://localhost/student-course-hub/admissions.php'>Admissions Information</a></li>
                </ul>
                
                <p>Best regards,<br>
                <strong>Admissions Office</strong><br>
                Bluebird College</p>
            </div>
            <div class='footer'>
                &copy; " . date('Y') . " Bluebird College. All rights reserved.
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($inquiry['Email'], $subject, $message);
}
?>