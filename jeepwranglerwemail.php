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
    $mail->DKIM_domain = 'eliteclasstransfers.com'; // Uncomment if DKIM is configured
    // $mail->DKIM_private = '/path/to/private.key';
    // $mail->DKIM_selector = 'phpmailer';

    // Recipients with enhanced headers
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('bookings@eliteclasstransfers.com', 'Jeep Wrangler Reservations');
    $mail->addCustomHeader('X-Mailer: PHP/' . phpversion());
    $mail->addCustomHeader('X-Priority: 1 (Highest)');
    
    // Email content with template handling
    $mail->Subject = 'Your Jeep Wrangler Adventure - Booking Confirmation';
    $mail->isHTML(true);
    
    // Template handling with fallback
    $templateFile = 'jeepwranglermessage.html';
    if (file_exists($templateFile)) {
        $htmlContent = file_get_contents($templateFile);
        
        // Personalize content
        $htmlContent = str_replace(
            ['{booking_date}', '{contact_number}'],
            [date('F j, Y'), '+30 693 661 4936'],
            $htmlContent
        );
        
        $mail->msgHTML($htmlContent, __DIR__);
        
        // Plain text fallback
        $mail->AltBody = "THANK YOU FOR YOUR JEEP WRANGLER BOOKING\n\n" .
                         "We're excited to help you explore in style!\n\n" .
                         "Our team will contact you shortly to confirm your adventure details.\n\n" .
                         "For immediate assistance:\n" .
                         "Phone: +30 693 661 4936\n" .
                         "Email: info@eliteclasstransfers.com\n\n" .
                         "Adventure awaits!\n" .
                         "The Elite Class Transfers Team";
    } else {
        // Fallback content
        $mail->Body = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">' .
                     '<h1 style="color: #2c3e50;">Thank You for Your Jeep Wrangler Booking</h1>' .
                     '<p>We have received your request and will contact you shortly to confirm your adventure details.</p>' .
                     '<p style="font-weight: bold;">For immediate assistance: +30 693 661 4936</p>' .
                     '</div>';
        
        error_log("Jeep Wrangler template missing: " . $templateFile);
    }

    // Send email and handle response
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
        error_log('Jeep Wrangler Email Error: ' . $mail->ErrorInfo);
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    error_log('Jeep Wrangler Email Exception: ' . $e->getMessage());
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your request. Please try again later.'
    ]);
}
?>