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
    // Create PHPMailer instance
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
    $mail->Priority = 1; // High priority

    // Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    $mail->addReplyTo('rentals@eliteclasstransfers.com', 'Rental Department');
    $mail->addCustomHeader('X-Mailer: PHP/' . phpversion());
    $mail->addCustomHeader('X-Priority: 1 (Highest)');

    // Generate confirmation number
    $confirmationNumber = 'RENT-' . strtoupper(substr(md5(time()), 0, 8));

    // Email content with responsive design
    $imageUrl = 'https://iili.io/3u6xz7a.md.png';
    
    $emailContent = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rental Confirmation</title>
        <style>
            @media only screen and (max-width: 600px) {
                .container { width: 100% !important; }
                table { width: 100% !important; }
                td, th { display: block; width: 100%; }
                td:before { content: attr(data-label); float: left; font-weight: bold; }
            }
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #ffffff;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .header {
                text-align: center;
                padding-bottom: 20px;
                border-bottom: 1px solid #eee;
            }
            .logo {
                max-width: 200px;
                height: auto;
                margin-bottom: 20px;
            }
            .confirmation-number {
                background-color: #f5f5f5;
                padding: 10px;
                text-align: center;
                margin: 20px 0;
                font-weight: bold;
                border-radius: 4px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th {
                background-color: #2c3e50;
                color: white;
                text-align: left;
                padding: 12px;
            }
            td {
                padding: 12px;
                border-bottom: 1px solid #ddd;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 14px;
                color: #777;
                border-top: 1px solid #eee;
                padding-top: 20px;
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
                <h1 style="color: #2c3e50;">Rental Confirmation</h1>
                <div class="confirmation-number">
                    Confirmation #: ' . $confirmationNumber . '
                </div>
            </div>
            
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>Thank you for choosing Elite Class Transfers for your ' . htmlspecialchars($carType) . ' rental. Below are your booking details:</p>
            
            <h3 style="color: #2c3e50;">Rental Summary</h3>
            <table>
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
                <tr>
                    <td>Pick-Up Location</td>
                    <td>' . htmlspecialchars($pickupLocation) . '</td>
                </tr>
                <tr>
                    <td>Drop-Off Location</td>
                    <td>' . htmlspecialchars($dropLocation) . '</td>
                </tr>
                <tr>
                    <td>Pick-Up Date/Time</td>
                    <td>' . htmlspecialchars("$pickDate at $pickTime") . '</td>
                </tr>
                <tr>
                    <td>Drop-Off Date/Time</td>
                    <td>' . htmlspecialchars("$dropDate at $dropTime") . '</td>
                </tr>
                <tr>
                    <td>Vehicle Type</td>
                    <td>' . htmlspecialchars($carType) . '</td>
                </tr>
                <tr>
                    <td>Contact Phone</td>
                    <td>' . htmlspecialchars($phone) . '</td>
                </tr>
            </table>
            
            <p>Our rental team is processing your request and will contact you shortly to confirm availability and finalize your reservation.</p>
            
            <p style="text-align: center; margin: 25px 0;">
                <span class="highlight">For immediate assistance: +30 693 661 4936</span><br>
                <a href="mailto:info@eliteclasstransfers.com">info@eliteclasstransfers.com</a>
            </p>
            
            <div class="footer">
                <p>Thank you for choosing Elite Class Transfers!</p>
                <p>Rental Department<br>Elite Class Transfers</p>
                <p>&copy; ' . date('Y') . ' Elite Class Transfers. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';

    // Plain text version
    $plainTextContent = "RENTAL CONFIRMATION #$confirmationNumber\n\n";
    $plainTextContent .= "Dear $name,\n\n";
    $plainTextContent .= "Thank you for your $carType rental request with Elite Class Transfers.\n\n";
    $plainTextContent .= "Rental Details:\n";
    $plainTextContent .= "Pick-Up: $pickupLocation on $pickDate at $pickTime\n";
    $plainTextContent .= "Drop-Off: $dropLocation on $dropDate at $dropTime\n";
    $plainTextContent .= "Vehicle Type: $carType\n";
    $plainTextContent .= "Contact Phone: $phone\n\n";
    $plainTextContent .= "Our team will contact you shortly to confirm your reservation.\n\n";
    $plainTextContent .= "For immediate assistance: +30 693 661 4936\n";
    $plainTextContent .= "Email: rentals@eliteclasstransfers.com\n\n";
    $plainTextContent .= "Thank you,\nElite Class Transfers Rental Team";

    // Email configuration
    $mail->Subject = 'Rental Confirmation #' . $confirmationNumber . ' - Elite Class Transfers';
    $mail->Body = $emailContent;
    $mail->AltBody = $plainTextContent;
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    // Send email
    if ($mail->send()) {
        $response = [
            'status' => 'success',
            'message' => 'Thank you! Your rental confirmation has been sent to your email.'
        ];
    } else {
        $response = [
            'status' => 'warning',
            'message' => 'Request received but confirmation email could not be sent.'
        ];
        error_log("Failed to send rental confirmation to: $email");
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    error_log("Rental Confirmation Error: " . $e->getMessage());
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your request. Please try again.'
    ]);
}
?>