<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sanitize and validate form data
$pickupLocation = filter_var($_POST['pickupLocation'], FILTER_SANITIZE_STRING);
$dropLocation = filter_var($_POST['dropLocation'], FILTER_SANITIZE_STRING);
$pickDate = filter_var($_POST['pickDate'], FILTER_SANITIZE_STRING);
$dropDate = filter_var($_POST['dropDate'], FILTER_SANITIZE_STRING);
$pickTime = filter_var($_POST['pickTime'], FILTER_SANITIZE_STRING);
$dropTime = filter_var($_POST['dropTime'], FILTER_SANITIZE_STRING);
$carType = filter_var($_POST['car-type'], FILTER_SANITIZE_STRING);
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);

// Validate required fields
if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Please provide valid name and email address'
    ]));
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
    $adminMail->Timeout = 30; // 30 seconds timeout
    $adminMail->Priority = 1; // High priority

    // Admin email content with professional formatting
    $adminContent = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .header { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
            .details { margin: 20px 0; }
            table { width: 100%; border-collapse: collapse; }
            th { background-color: #f2f2f2; text-align: left; padding: 8px; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            .highlight { color: #e74c3c; font-weight: bold; }
        </style>
    </head>
    <body>
        <h1 class="header">New Rental Car Booking</h1>
        <div class="details">
            <table>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td>' . htmlspecialchars($name) . '</td>
                </tr>
                <tr>
                    <td>Contact Email</td>
                    <td>' . htmlspecialchars($email) . '</td>
                </tr>
                <tr>
                    <td>Contact Phone</td>
                    <td>' . htmlspecialchars($phone) . '</td>
                </tr>
                <tr>
                    <td>Vehicle Type</td>
                    <td>' . htmlspecialchars($carType) . '</td>
                </tr>
                <tr>
                    <td>Pick-Up Location</td>
                    <td>' . htmlspecialchars($pickupLocation) . '</td>
                </tr>
                <tr>
                    <td>Pick-Up Date/Time</td>
                    <td>' . htmlspecialchars("$pickDate at $pickTime") . '</td>
                </tr>
                <tr>
                    <td>Drop-Off Location</td>
                    <td>' . htmlspecialchars($dropLocation) . '</td>
                </tr>
                <tr>
                    <td>Drop-Off Date/Time</td>
                    <td>' . htmlspecialchars("$dropDate at $dropTime") . '</td>
                </tr>
            </table>
        </div>
        <p>This booking requires immediate attention. Please contact the customer to confirm availability.</p>
        <p class="highlight">Customer expects confirmation within 24 hours.</p>
    </body>
    </html>';

    // Admin recipients
    $adminMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $adminMail->addAddress('rsveliteclasstransfers@gmail.com');
    $adminMail->addAddress('reservations@eliteclasstransfers.com');
    $adminMail->addReplyTo($email, $name);
    $adminMail->Subject = 'NEW RENTAL: ' . htmlspecialchars($carType) . ' - ' . htmlspecialchars($name);
    $adminMail->Body = $adminContent;
    $adminMail->isHTML(true);
    $adminMail->CharSet = 'UTF-8';

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
    $confirmationNumber = 'RENT-' . strtoupper(substr(md5(time()), 0, 8));
    $clientContent = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            @media only screen and (max-width: 600px) {
                .container { width: 100% !important; }
            }
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .confirmation-number { background-color: #f5f5f5; padding: 10px; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Thank You for Your Rental Request</h1>
            <div class="confirmation-number">
                Confirmation #: ' . $confirmationNumber . '
            </div>
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>We have received your request for a ' . htmlspecialchars($carType) . ' rental:</p>
            <table>
                <tr>
                    <th>Pick-Up</th>
                    <td>' . htmlspecialchars("$pickupLocation on $pickDate at $pickTime") . '</td>
                </tr>
                <tr>
                    <th>Drop-Off</th>
                    <td>' . htmlspecialchars("$dropLocation on $dropDate at $dropTime") . '</td>
                </tr>
            </table>
            <p>Our reservations team is reviewing your request and will contact you within 24 hours to confirm availability.</p>
            <p>For immediate assistance, please call: <strong>+30 693 661 4936</strong></p>
            <p>Thank you for choosing Elite Class Transfers!</p>
        </div>
    </body>
    </html>';

    // Client recipients
    $clientMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $clientMail->addAddress($email);
    $clientMail->Subject = 'Rental Request Received #' . $confirmationNumber;
    $clientMail->Body = $clientContent;
    $clientMail->isHTML(true);
    $clientMail->AltBody = "Thank you for your rental request. We'll contact you shortly to confirm.";
    $clientMail->CharSet = 'UTF-8';

    // Send both emails with error tracking
    $adminSent = $adminMail->send();
    $clientSent = $clientMail->send();

    // Prepare response
    $response = [
        'status' => 'success',
        'message' => 'Thank you! Your rental request has been submitted.'
    ];

    if (!$adminSent || !$clientSent) {
        $response['status'] = 'warning';
        $response['message'] = 'Request submitted, but email confirmation may not have been sent.';
        
        // Log which email failed
        if (!$adminSent) error_log("Failed to send admin notification for rental: $email");
        if (!$clientSent) error_log("Failed to send client confirmation: $email");
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Log the full error
    error_log("Rental Booking Error: " . $e->getMessage());
    
    // Return JSON error
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your request. Please try again later.'
    ]);
}
?>