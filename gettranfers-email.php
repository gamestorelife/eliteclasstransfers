<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Load PHPMailer classes (removed deprecated Autoload)
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Sanitize and validate form data
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
$pickupLocation = filter_var($_POST['gt-pickUplocation'], FILTER_SANITIZE_STRING);
$dropOffLocation = filter_var($_POST['gt-dropOfflocation'], FILTER_SANITIZE_STRING);
$pickupDate = filter_var($_POST['gt-pickUpDate'], FILTER_SANITIZE_STRING);
$pickupTime = filter_var($_POST['gt-pickUpTime'], FILTER_SANITIZE_STRING);
$flightNumber = filter_var($_POST['flight-number'], FILTER_SANITIZE_STRING);
$returnPickupDate = filter_var($_POST['pickUpDate-return'], FILTER_SANITIZE_STRING);
$returnPickupTime = filter_var($_POST['pickUpTime-return'], FILTER_SANITIZE_STRING);
$returnFlightNumber = filter_var($_POST['flight-number-return'], FILTER_SANITIZE_STRING);
$adults = filter_var($_POST['gt-adults'], FILTER_SANITIZE_NUMBER_INT);
$children = filter_var($_POST['gt-children'], FILTER_SANITIZE_NUMBER_INT);
$luggage = filter_var($_POST['gt-luggage'], FILTER_SANITIZE_STRING);

// Validate required fields
if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('<div class="alert alert-danger">Please provide valid name and email address</div>');
}

try {
    // Create PHPMailer instance for admin notification
    $adminMail = new PHPMailer(true);
    
    // Server configuration
    $adminMail->isSMTP();
    $adminMail->Host = 'smtp.hostinger.com';
    $adminMail->Port = 465;
    $adminMail->SMTPAuth = true;
    $adminMail->Username = 'no-reply@eliteclasstransfers.com';
    $adminMail->Password = 'Noeliteclasstransfers23**';
    $adminMail->SMTPSecure = 'ssl';
    $adminMail->SMTPDebug = 0; // Set to 2 for debugging
    
    // Admin email content (formatted as HTML table)
    $adminContent = '<h2 style="color:#003366;">New Transfer Booking</h2>';
    $adminContent .= '<table style="width:100%; border-collapse:collapse;">';
    $adminContent .= '<tr style="background-color:#f2f2f2;"><th style="padding:8px; text-align:left; border:1px solid #ddd;">Field</th><th style="padding:8px; text-align:left; border:1px solid #ddd;">Details</th></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Name</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($name).'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Email</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($email).'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Phone</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($phone).'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Pickup Location</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($pickupLocation).'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Dropoff Location</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($dropOffLocation).'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Pickup Date/Time</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars("$pickupDate at $pickupTime").'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Flight Number</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($flightNumber).'</td></tr>';
    
    if (!empty($returnPickupDate)) {
        $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Return Pickup</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars("$returnPickupDate at $returnPickupTime").'</td></tr>';
        $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Return Flight</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($returnFlightNumber).'</td></tr>';
    }
    
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Passengers</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars("$adults adults, $children children").'</td></tr>';
    $adminContent .= '<tr><td style="padding:8px; border:1px solid #ddd;">Luggage</td><td style="padding:8px; border:1px solid #ddd;">'.htmlspecialchars($luggage).'</td></tr>';
    $adminContent .= '</table>';

    // Admin recipients
    $adminMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $adminMail->addAddress('rsveliteclasstransfers@gmail.com');
    $adminMail->addReplyTo($email, $name);
    $adminMail->Subject = 'New Transfer Booking: ' . $name;
    $adminMail->Body = $adminContent;
    $adminMail->isHTML(true);

    // Create PHPMailer instance for client confirmation
    $clientMail = new PHPMailer(true);
    $clientMail->isSMTP();
    $clientMail->Host = 'smtp.hostinger.com';
    $clientMail->Port = 465;
    $clientMail->SMTPAuth = true;
    $clientMail->Username = 'no-reply@eliteclasstransfers.com';
    $clientMail->Password = 'Noeliteclasstransfers23**';
    $clientMail->SMTPSecure = 'ssl';

    // Client email content
    $clientContent = '<h2 style="color:#003366;">Thank You for Your Booking, '.htmlspecialchars($name).'!</h2>';
    $clientContent .= '<p>We have received your transfer request with the following details:</p>';
    $clientContent .= '<ul>';
    $clientContent .= '<li><strong>Pickup:</strong> '.htmlspecialchars($pickupLocation).' on '.htmlspecialchars("$pickupDate at $pickupTime").'</li>';
    $clientContent .= '<li><strong>Dropoff:</strong> '.htmlspecialchars($dropOffLocation).'</li>';
    if (!empty($flightNumber)) {
        $clientContent .= '<li><strong>Flight Number:</strong> '.htmlspecialchars($flightNumber).'</li>';
    }
    if (!empty($returnPickupDate)) {
        $clientContent .= '<li><strong>Return Pickup:</strong> '.htmlspecialchars("$returnPickupDate at $returnPickupTime").'</li>';
        if (!empty($returnFlightNumber)) {
            $clientContent .= '<li><strong>Return Flight:</strong> '.htmlspecialchars($returnFlightNumber).'</li>';
        }
    }
    $clientContent .= '<li><strong>Passengers:</strong> '.htmlspecialchars("$adults adults, $children children").'</li>';
    $clientContent .= '<li><strong>Luggage:</strong> '.htmlspecialchars($luggage).'</li>';
    $clientContent .= '</ul>';
    $clientContent .= '<p>Our team will review your request and contact you shortly to confirm the details.</p>';
    $clientContent .= '<p>If you have any questions, please reply to this email or contact us at support@eliteclasstransfers.com</p>';
    $clientContent .= '<p>Best regards,<br>The Elite Class Transfers Team</p>';

    // Client recipients
    $clientMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $clientMail->addAddress($email);
    $clientMail->Subject = 'Your Transfer Booking Confirmation';
    $clientMail->Body = $clientContent;
    $clientMail->isHTML(true);
    $clientMail->AltBody = "Thank you for your booking, $name!\n\nWe have received your transfer request and will contact you shortly to confirm the details.\n\nBest regards,\nThe Elite Class Transfers Team";

    // Send both emails
    $adminSent = $adminMail->send();
    $clientSent = $clientMail->send();

    if ($adminSent && $clientSent) {
        echo '<div class="alert alert-success">Thank you! Your booking has been submitted. A confirmation has been sent to your email.</div>';
    } elseif ($adminSent) {
        echo '<div class="alert alert-warning">Your booking has been submitted, but there was an issue sending your confirmation email. Our team will contact you shortly.</div>';
    } else {
        echo '<div class="alert alert-danger">There was an error processing your booking. Please try again or contact us directly.</div>';
    }

} catch (Exception $e) {
    echo '<div class="alert alert-danger">There was an error processing your request. Our team has been notified. Please try again later.</div>';
    error_log('Transfer Booking Error: ' . $e->getMessage());
    
    // Consider implementing an admin notification here
    // notifyAdminOfBookingFailure($name, $email, $e->getMessage());
}
?>