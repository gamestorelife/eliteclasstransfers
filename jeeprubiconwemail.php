<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Enable error reporting for debugging (remove in production)
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
    $mail->Timeout = 30; // 30 seconds timeout
    
    // Set email priority
    $mail->Priority = 1; // Highest priority

    // Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('reservations@eliteclasstransfers.com', 'Reservations Team');
    
    // Email content
    $mail->Subject = 'Your Jeep Rubicon Adventure - Booking Confirmation';
    $mail->isHTML(true);
    
    // Load HTML template with error handling
    $templateFile = 'jeeprubiconmessage.html';
    if (file_exists($templateFile)) {
        $htmlContent = file_get_contents($templateFile);
        
        // Personalize content with customer data
        $htmlContent = str_replace(
            ['{current_date}'],
            [date('F j, Y')],
            $htmlContent
        );
        
        $mail->msgHTML($htmlContent, __DIR__);
        
        // Plain text fallback
        $mail->AltBody = "Thank you for booking your Jeep Rubicon adventure with Elite Class Transfers!\n\n" .
                         "We're excited to help you explore in style. Our team will contact you shortly to confirm your booking details.\n\n" .
                         "For immediate assistance, contact our Reservations Team at +30 693 661 4936\n\n" .
                         "Adventure awaits!\nThe Elite Class Transfers Team";
    } else {
        // Fallback content if template is missing
        $mail->Body = '<h1>Thank You for Your Jeep Rubicon Booking</h1>' .
                     '<p>We have received your request and will contact you shortly to confirm your adventure details.</p>' .
                     '<p>For immediate assistance, please call +30 693 661 4936</p>';
        
        $mail->AltBody = "Thank you for your Jeep Rubicon booking. We'll contact you shortly.";
        
        error_log("Jeep Rubicon email template missing: " . $templateFile);
    }

    // Add headers for better email deliverability
    $mail->addCustomHeader('X-Mailer: PHP/' . phpversion());
    $mail->addCustomHeader('Precedence: bulk');
    $mail->addCustomHeader('X-Priority: 1 (Highest)');

    // Send email with error handling
    if ($mail->send()) {
        $response = [
            'status' => 'success',
            'message' => 'Thank you! Your booking confirmation has been sent.'
        ];
    } else {
        $response = [
            'status' => 'warning',
            'message' => 'Booking received but confirmation email could not be sent.'
        ];
        error_log('Jeep Rubicon Email Error: ' . $mail->ErrorInfo);
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    error_log('Jeep Rubicon Email Exception: ' . $e->getMessage());
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your request. Please try again later.'
    ]);
}
?>