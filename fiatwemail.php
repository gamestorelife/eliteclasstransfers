<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load PHPMailer classes (removed deprecated Autoload)
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Validate and sanitize email input
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('<div class="alert alert-danger">Please provide a valid email address</div>');
}

try {
    // Create a single PHPMailer instance
    $mail = new PHPMailer(true);
    
    // Server configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eliteclasstransfers.com';
    $mail->Password = 'Noeliteclasstransfers23**';
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = 0; // Set to 2 for debugging
    
    // Set email priority (optional)
    $mail->Priority = 1; // Highest priority

    // Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('support@eliteclasstransfers.com', 'Customer Support');
    
    // Email content
    $mail->Subject = 'Welcome to Elite Class Transfers - Your Fiat Service';
    $mail->isHTML(true);
    
    // Load HTML template with error handling
    $templateFile = 'fiatmessage.html';
    if (file_exists($templateFile)) {
        $htmlContent = file_get_contents($templateFile);
        
        // Personalize content (example)
        $htmlContent = str_replace(
            ['{customer_email}', '{date}'],
            [$email, date('F j, Y')],
            $htmlContent
        );
        
        $mail->msgHTML($htmlContent, __DIR__);
        $mail->AltBody = "Thank you for choosing Elite Class Transfers for your Fiat service.\n\nWe appreciate your business!";
    } else {
        // Fallback content
        $mail->Body = "Thank you for choosing Elite Class Transfers for your Fiat service.\n\nWe appreciate your business!";
        error_log("FIAT email template missing: " . $templateFile);
    }

    // Add DKIM signature if available (recommended)
    // $mail->DKIM_domain = 'eliteclasstransfers.com';
    // $mail->DKIM_private = '/path/to/private.key';
    // $mail->DKIM_selector = 'phpmailer';

    // Send email with error handling
    if ($mail->send()) {
        echo '<div class="alert alert-success">Thank you! Your confirmation email has been sent.</div>';
    } else {
        echo '<div class="alert alert-warning">Your request was received, but we couldn\'t send the confirmation email.</div>';
        error_log('FIAT Email Error: ' . $mail->ErrorInfo);
        
        // Optional: Notify admin of failure
        // notifyAdminOfEmailFailure($email, $mail->ErrorInfo);
    }

} catch (Exception $e) {
    echo '<div class="alert alert-danger">There was an error processing your request. Our team has been notified.</div>';
    error_log('FIAT Email Exception: ' . $e->getMessage());
    
    // Consider implementing a retry mechanism or queue system here
}
?>