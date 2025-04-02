<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load PHPMailer classes (removed deprecated Autoload)
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate and sanitize email input
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Please provide a valid email address'
    ]));
}

try {
    // Create PHPMailer instance
    $mail = new PHPMailer(true);
    
    // Server configuration with enhanced settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eliteclasstransfers.com';
    $mail->Password = 'Noeliteclasstransfers23**';
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = 0; // Set to 2 for debugging
    $mail->Timeout = 30; // 30 seconds timeout
    $mail->Priority = 1; // High priority

    // Recipients with enhanced headers
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('mercedes@eliteclasstransfers.com', 'Mercedes Reservations');
    $mail->addCustomHeader('X-Mailer: PHP/' . phpversion());
    $mail->addCustomHeader('X-Priority: 1 (Highest)');
    
    // Email content with template handling
    $mail->Subject = 'Your Mercedes Luxury Transfer - Booking Confirmation';
    $mail->isHTML(true);
    
    // Template handling with fallback (corrected filename spelling)
    $templateFile = 'mercedesmessage.html';
    if (file_exists($templateFile)) {
        $htmlContent = file_get_contents($templateFile);
        
        // Personalize content
        $htmlContent = str_replace(
            ['{confirmation_date}', '{contact_phone}'],
            [date('F j, Y'), '+30 693 661 4936'],
            $htmlContent
        );
        
        $mail->msgHTML($htmlContent, __DIR__);
        
        // Plain text fallback
        $mail->AltBody = "MERCEDES BOOKING CONFIRMATION\n\n" .
                         "Thank you for choosing Elite Class Transfers for your luxury Mercedes experience.\n\n" .
                         "We have received your booking request and will contact you shortly to confirm all details.\n\n" .
                         "For immediate assistance:\n" .
                         "Phone: +30 693 661 4936\n" .
                         "Email: info@eliteclasstransfers.com\n\n" .
                         "Best regards,\n" .
                         "The Elite Class Transfers Team";
    } else {
        // Fallback content
        $mail->Body = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">' .
                     '<h1 style="color: #000000;">Mercedes Booking Confirmation</h1>' .
                     '<p>Thank you for your Mercedes transfer booking with Elite Class Transfers.</p>' .
                     '<p>Our luxury vehicle team will contact you shortly to confirm your reservation details.</p>' .
                     '<p style="font-weight: bold; margin-top: 20px;">For immediate assistance: +30 693 661 4936</p>' .
                     '</div>';
        
        error_log("Mercedes email template missing: " . $templateFile);
    }

    // Send email and handle response
    if ($mail->send()) {
        $response = [
            'status' => 'success',
            'message' => 'Thank you! Your Mercedes booking confirmation has been sent.'
        ];
    } else {
        $response = [
            'status' => 'warning',
            'message' => 'Booking received but confirmation email could not be sent.'
        ];
        error_log('Mercedes Email Error: ' . $mail->ErrorInfo);
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    error_log('Mercedes Email Exception: ' . $e->getMessage());
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your booking. Please try again later.'
    ]);
}
?>