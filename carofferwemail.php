<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load PHPMailer classes
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Validate and sanitize email input
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Please provide a valid email address');
}

try {
    // Create a single PHPMailer instance
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eliteclasstransfers.com';
    $mail->Password = 'Noeliteclasstransfers23**';
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = 0; // Set to 2 for debugging

    // Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Elite Class Transfers';
    
    // Load HTML content from file if available
    $htmlFile = 'messagecaroffer.html';
    if (file_exists($htmlFile)) {
        $htmlContent = file_get_contents($htmlFile);
        // You can personalize the content here if needed
        // $htmlContent = str_replace('{email}', $email, $htmlContent);
        $mail->msgHTML($htmlContent, __DIR__);
        
        // Plain text fallback
        $mail->AltBody = 'Thank you for choosing Elite Class Transfers.';
    } else {
        // Fallback if HTML file is missing
        $mail->Body = 'Thank you for choosing Elite Class Transfers.';
        error_log("HTML template not found: " . $htmlFile);
    }

    if ($mail->send()) {
        echo '<div class="alert alert-success">Thank you! Your email has been sent successfully.</div>';
    } else {
        echo '<div class="alert alert-danger">Email could not be sent. Please try again later.</div>';
        error_log('Mailer Error: ' . $mail->ErrorInfo);
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Message could not be sent. Mailer Error: ' . $e->getMessage() . '</div>';
    error_log('Mailer Exception: ' . $e->getMessage());
}
?>