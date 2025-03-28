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
    // Create PHPMailer instance with error handling enabled
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eliteclasstransfers.com';
    $mail->Password = 'Noeliteclasstransfers23**';
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = 0; // Set to 2 for debugging if needed

    // Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('info@eliteclasstransfers.com', 'Customer Support');
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Elite Class Transfers';
    
    // Load HTML template with fallback
    $templateFile = 'message.html';
    if (file_exists($templateFile)) {
        $htmlContent = file_get_contents($templateFile);
        // Personalize the content if needed
        // $htmlContent = str_replace('{customer_email}', $email, $htmlContent);
        $mail->msgHTML($htmlContent, __DIR__);
        $mail->AltBody = 'Thank you for choosing Elite Class Transfers. We appreciate your business.';
    } else {
        // Fallback simple message
        $mail->Body = 'Thank you for choosing Elite Class Transfers. We appreciate your business.';
        error_log("Email template not found: " . $templateFile);
    }

    // Send email
    if ($mail->send()) {
        echo '<div class="success-message">Thank you! Your confirmation email has been sent.</div>';
    } else {
        echo '<div class="error-message">Email could not be sent. Please try again later.</div>';
        error_log('Mailer Error: ' . $mail->ErrorInfo);
    }
    
} catch (Exception $e) {
    echo '<div class="error-message">There was a problem sending your email. Our team has been notified.</div>';
    error_log('Mailer Exception: ' . $e->getMessage());
    
    // Consider sending an alert to admin about the failure
    // notifyAdminOfEmailFailure($email, $e->getMessage());
}
?>