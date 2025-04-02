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

// Sanitize and validate form data
$startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING);
$pickUpTime = filter_var($_POST['hpickUpTime'], FILTER_SANITIZE_STRING);
$adults = filter_var($_POST['hAdults'], FILTER_SANITIZE_NUMBER_INT);
$children = filter_var($_POST['hChildren'], FILTER_SANITIZE_NUMBER_INT);
$driverDays = filter_var($_POST['driverDays'], FILTER_SANITIZE_NUMBER_INT);
$driverHours = filter_var($_POST['driverHours'], FILTER_SANITIZE_STRING);
$specialRequest = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
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
    // Create PHPMailer instance
    $mail = new PHPMailer(true);
    
    // Server settings with timeout configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eliteclasstransfers.com';
    $mail->Password = 'Noeliteclasstransfers23**';
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = 0;
    $mail->Timeout = 30; // 30 seconds timeout
    
    // Email content with responsive design
    $imageUrl = 'https://iili.io/3u6xz7a.md.png';
    $confirmationNumber = 'ECT-' . strtoupper(substr(md5(time()), 0, 8));
    
    $emailContent = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking Confirmation</title>
        <style>
            @media only screen and (max-width: 600px) {
                .container { width: 100% !important; }
                table { width: 100% !important; }
            }
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                text-align: center;
                padding: 20px 0;
            }
            .logo {
                max-width: 200px;
                height: auto;
            }
            .confirmation-number {
                background-color: #f5f5f5;
                padding: 10px;
                text-align: center;
                margin: 20px 0;
                font-weight: bold;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            th, td {
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 14px;
                color: #777;
            }
            .highlight {
                color: #e74c3c;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $imageUrl . '" alt="Elite Class Transfers" class="logo">
                <h1 style="color: #2c3e50;">Booking Confirmation</h1>
                <div class="confirmation-number">
                    Confirmation #: ' . $confirmationNumber . '
                </div>
            </div>
            
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>Thank you for choosing Elite Class Transfers. Your booking details are confirmed below:</p>
            
            <h3 style="color: #2c3e50;">Booking Summary</h3>
            <table>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td>' . htmlspecialchars($startDate) . '</td>
                </tr>
                <tr>
                    <td>Pick Up Time</td>
                    <td>' . htmlspecialchars($pickUpTime) . '</td>
                </tr>
                <tr>
                    <td>Passengers</td>
                    <td>' . htmlspecialchars("$adults adults, $children children") . '</td>
                </tr>
                <tr>
                    <td>Duration</td>
                    <td>' . htmlspecialchars("$driverDays days, $driverHours hours/day") . '</td>
                </tr>';

    if (!empty($specialRequest)) {
        $emailContent .= '<tr>
                    <td>Special Requests</td>
                    <td>' . nl2br(htmlspecialchars($specialRequest)) . '</td>
                </tr>';
    }

    $emailContent .= '<tr>
                    <td>Contact Phone</td>
                    <td>' . htmlspecialchars($phone) . '</td>
                </tr>
            </table>
            
            <p>Our team will contact you if we need any additional information. You can also reach us at:</p>
            <p style="text-align: center; margin: 25px 0;">
                <span class="highlight">+30 693 661 4936</span><br>
                <a href="mailto:info@eliteclasstransfers.com">info@eliteclasstransfers.com</a>
            </p>
            
            <div class="footer">
                <p>Thank you for your booking!</p>
                <p>Elite Class Transfers Team</p>
                <p>&copy; ' . date('Y') . ' Elite Class Transfers. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';

    // Plain text version for email clients that don't support HTML
    $plainTextContent = "BOOKING CONFIRMATION #$confirmationNumber\n\n";
    $plainTextContent .= "Dear $name,\n\n";
    $plainTextContent .= "Thank you for choosing Elite Class Transfers. Your booking details:\n\n";
    $plainTextContent .= "Start Date: $startDate\n";
    $plainTextContent .= "Pick Up Time: $pickUpTime\n";
    $plainTextContent .= "Passengers: $adults adults, $children children\n";
    $plainTextContent .= "Duration: $driverDays days, $driverHours hours/day\n";
    if (!empty($specialRequest)) {
        $plainTextContent .= "Special Requests: $specialRequest\n";
    }
    $plainTextContent .= "\nContact Phone: $phone\n\n";
    $plainTextContent .= "For immediate assistance: +30 693 661 4936\n";
    $plainTextContent .= "Email: info@eliteclasstransfers.com\n\n";
    $plainTextContent .= "Thank you for your booking!\nElite Class Transfers Team";

    // Email configuration
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('info@eliteclasstransfers.com', 'Customer Support');
    $mail->Subject = 'Booking Confirmation #' . $confirmationNumber . ' - Elite Class Transfers';
    $mail->Body = $emailContent;
    $mail->AltBody = $plainTextContent;
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    // Send email
    if ($mail->send()) {
        $response = [
            'status' => 'success',
            'message' => 'Your booking confirmation has been sent to your email.'
        ];
    } else {
        $response = [
            'status' => 'warning',
            'message' => 'Booking received but confirmation email could not be sent.'
        ];
        error_log("Failed to send confirmation email to: $email");
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Log the error
    error_log("Booking Confirmation Error: " . $e->getMessage());
    
    // Return JSON error
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your booking. Please try again.'
    ]);
}
?>