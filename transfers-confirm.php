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

    // Admin email content
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
        <h1 class="header">New Transfer Booking</h1>
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
                    <td>Pick-Up Location</td>
                    <td>' . htmlspecialchars($pickupLocation) . '</td>
                </tr>
                <tr>
                    <td>Pick-Up Date/Time</td>
                    <td>' . htmlspecialchars("$pickupDate at $pickupTime") . '</td>
                </tr>
                <tr>
                    <td>Flight Number</td>
                    <td>' . htmlspecialchars($flightNumber) . '</td>
                </tr>
                <tr>
                    <td>Drop-Off Location</td>
                    <td>' . htmlspecialchars($dropOffLocation) . '</td>
                </tr>';

    if (!empty($returnPickupDate)) {
        $adminContent .= '<tr>
                    <td>Return Pick-Up</td>
                    <td>' . htmlspecialchars("$returnPickupDate at $returnPickupTime") . '</td>
                </tr>
                <tr>
                    <td>Return Flight</td>
                    <td>' . htmlspecialchars($returnFlightNumber) . '</td>
                </tr>';
    }

    $adminContent .= '<tr>
                    <td>Passengers</td>
                    <td>' . htmlspecialchars("$adults adults, $children children") . '</td>
                </tr>
                <tr>
                    <td>Luggage</td>
                    <td>' . htmlspecialchars($luggage) . '</td>
                </tr>
            </table>
        </div>
        <p class="highlight">This booking requires confirmation within 24 hours.</p>
    </body>
    </html>';

    // Admin recipients
    $adminMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $adminMail->addAddress('rsveliteclasstransfers@gmail.com');
    $adminMail->addAddress('reservations@eliteclasstransfers.com');
    $adminMail->addReplyTo($email, $name);
    $adminMail->Subject = 'NEW TRANSFER: ' . htmlspecialchars($name) . ' - ' . htmlspecialchars($pickupDate);
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
    $confirmationNumber = 'TRF-' . strtoupper(substr(md5(time()), 0, 8));
    $imageUrl = 'https://iili.io/3u6xz7a.md.png';
    
    $clientContent = '<!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @media only screen and (max-width: 600px) {
                .container { width: 100% !important; }
                table { width: 100% !important; }
            }
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; padding-bottom: 20px; }
            .logo { max-width: 200px; height: auto; }
            .confirmation-number { 
                background-color: #f5f5f5; 
                padding: 10px; 
                text-align: center; 
                margin: 20px 0;
                font-weight: bold;
                border-radius: 4px;
            }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th { background-color: #2c3e50; color: white; text-align: left; padding: 12px; }
            td { padding: 12px; border-bottom: 1px solid #ddd; }
            .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #777; }
            .highlight { color: #e74c3c; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $imageUrl . '" alt="Elite Class Transfers" class="logo">
                <h1 style="color: #2c3e50;">Transfer Booking Confirmation</h1>
                <div class="confirmation-number">
                    Confirmation #: ' . $confirmationNumber . '
                </div>
            </div>
            
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>Thank you for booking your transfer with Elite Class Transfers. Here are your booking details:</p>
            
            <h3 style="color: #2c3e50;">Transfer Details</h3>
            <table>
                <tr>
                    <th>Pick-Up</th>
                    <td>' . htmlspecialchars("$pickupLocation on $pickupDate at $pickupTime") . '</td>
                </tr>
                <tr>
                    <th>Drop-Off</th>
                    <td>' . htmlspecialchars($dropOffLocation) . '</td>
                </tr>
                <tr>
                    <th>Flight Number</th>
                    <td>' . htmlspecialchars($flightNumber) . '</td>
                </tr>';

    if (!empty($returnPickupDate)) {
        $clientContent .= '<tr>
                    <th>Return Pick-Up</th>
                    <td>' . htmlspecialchars("$returnPickupDate at $returnPickupTime") . '</td>
                </tr>
                <tr>
                    <th>Return Flight</th>
                    <td>' . htmlspecialchars($returnFlightNumber) . '</td>
                </tr>';
    }

    $clientContent .= '<tr>
                    <th>Passengers</th>
                    <td>' . htmlspecialchars("$adults adults, $children children") . '</td>
                </tr>
                <tr>
                    <th>Luggage</th>
                    <td>' . htmlspecialchars($luggage) . '</td>
                </tr>
            </table>
            
            <p>Our team is processing your request and will contact you shortly to confirm your transfer details.</p>
            
            <p style="text-align: center; margin: 25px 0;">
                <span class="highlight">For immediate assistance: +30 693 661 4936</span><br>
                <a href="mailto:info@eliteclasstransfers.com">info@eliteclasstransfers.com</a>
            </p>
            
            <div class="footer">
                <p>Thank you for choosing Elite Class Transfers!</p>
                <p>&copy; ' . date('Y') . ' Elite Class Transfers. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';

    // Plain text version
    $plainTextContent = "TRANSFER CONFIRMATION #$confirmationNumber\n\n";
    $plainTextContent .= "Dear $name,\n\n";
    $plainTextContent .= "Thank you for booking your transfer with Elite Class Transfers.\n\n";
    $plainTextContent .= "Transfer Details:\n";
    $plainTextContent .= "Pick-Up: $pickupLocation on $pickupDate at $pickupTime\n";
    $plainTextContent .= "Flight: $flightNumber\n";
    $plainTextContent .= "Drop-Off: $dropOffLocation\n";
    if (!empty($returnPickupDate)) {
        $plainTextContent .= "Return Pick-Up: $returnPickupDate at $returnPickupTime\n";
        $plainTextContent .= "Return Flight: $returnFlightNumber\n";
    }
    $plainTextContent .= "Passengers: $adults adults, $children children\n";
    $plainTextContent .= "Luggage: $luggage\n\n";
    $plainTextContent .= "Our team will contact you shortly to confirm your transfer.\n\n";
    $plainTextContent .= "For immediate assistance: +30 693 661 4936\n";
    $plainTextContent .= "Email: reservations@eliteclasstransfers.com\n\n";
    $plainTextContent .= "Thank you,\nElite Class Transfers Team";

    // Client recipients
    $clientMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $clientMail->addAddress($email);
    $clientMail->Subject = 'Transfer Confirmation #' . $confirmationNumber;
    $clientMail->Body = $clientContent;
    $clientMail->AltBody = $plainTextContent;
    $clientMail->isHTML(true);
    $clientMail->CharSet = 'UTF-8';

    // Send both emails with error tracking
    $adminSent = $adminMail->send();
    $clientSent = $clientMail->send();

    // Prepare response
    $response = [
        'status' => 'success',
        'message' => 'Thank you! Your transfer request has been submitted.'
    ];

    if (!$adminSent || !$clientSent) {
        $response['status'] = 'warning';
        $response['message'] = 'Request submitted, but confirmation email may not have been sent.';
        
        // Log which email failed
        if (!$adminSent) error_log("Failed to send admin notification for transfer: $email");
        if (!$clientSent) error_log("Failed to send client confirmation: $email");
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Log the full error
    error_log("Transfer Confirmation Error: " . $e->getMessage());
    
    // Return JSON error
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your request. Please try again later.'
    ]);
}
?>