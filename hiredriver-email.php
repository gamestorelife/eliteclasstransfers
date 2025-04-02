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
    // Create PHPMailer instance for admin notification
    $adminMail = new PHPMailer(true);
    
    // Server settings with timeout configuration
    $adminMail->isSMTP();
    $adminMail->Host = 'smtp.hostinger.com';
    $adminMail->Port = 465;
    $adminMail->SMTPAuth = true;
    $adminMail->Username = 'no-reply@eliteclasstransfers.com';
    $adminMail->Password = 'Noeliteclasstransfers23**';
    $adminMail->SMTPSecure = 'ssl';
    $adminMail->SMTPDebug = 0;
    $adminMail->Timeout = 30; // 30 seconds timeout
    
    // Admin email content with improved formatting
    $adminContent = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .header { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
            .details { margin: 20px 0; }
            .detail-row { margin-bottom: 10px; }
            .label { font-weight: bold; color: #2c3e50; display: inline-block; width: 150px; }
        </style>
    </head>
    <body>
        <h1 class="header">New Driver Hire Request</h1>
        <div class="details">';
    
    $adminContent .= '<div class="detail-row"><span class="label">Name:</span> ' . htmlspecialchars($name) . '</div>';
    $adminContent .= '<div class="detail-row"><span class="label">Email:</span> ' . htmlspecialchars($email) . '</div>';
    $adminContent .= '<div class="detail-row"><span class="label">Phone:</span> ' . htmlspecialchars($phone) . '</div>';
    $adminContent .= '<div class="detail-row"><span class="label">Start Date:</span> ' . htmlspecialchars($startDate) . '</div>';
    $adminContent .= '<div class="detail-row"><span class="label">Pickup Time:</span> ' . htmlspecialchars($pickUpTime) . '</div>';
    $adminContent .= '<div class="detail-row"><span class="label">Passengers:</span> ' . htmlspecialchars("$adults adults, $children children") . '</div>';
    $adminContent .= '<div class="detail-row"><span class="label">Duration:</span> ' . htmlspecialchars("$driverDays days, $driverHours hours/day") . '</div>';
    
    if (!empty($specialRequest)) {
        $adminContent .= '<div class="detail-row"><span class="label">Special Request:</span> ' . nl2br(htmlspecialchars($specialRequest)) . '</div>';
    }
    
    $adminContent .= '</div></body></html>';

    // Admin recipients with CC option (uncomment if needed)
    $adminMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $adminMail->addAddress('rsveliteclasstransfers@gmail.com');
    // $adminMail->addCC('another@email.com'); // Uncomment to CC another address
    $adminMail->addReplyTo($email, $name);
    $adminMail->Subject = 'New Driver Hire Request: ' . $name;
    $adminMail->Body = $adminContent;
    $adminMail->isHTML(true);
    $adminMail->CharSet = 'UTF-8';

    // Create PHPMailer instance for client confirmation
    $clientMail = new PHPMailer(true);
    $clientMail->isSMTP();
    $clientMail->Host = 'smtp.hostinger.com';
    $adminMail->Timeout = 30; // 30 seconds timeout
    $clientMail->Port = 465;
    $clientMail->SMTPAuth = true;
    $clientMail->Username = 'no-reply@eliteclasstransfers.com';
    $clientMail->Password = 'Noeliteclasstransfers23**';
    $clientMail->SMTPSecure = 'ssl';

    // Client email content with responsive design
    $clientContent = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            @media only screen and (max-width: 600px) {
                .container { width: 100% !important; }
            }
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { color: #2c3e50; text-align: center; }
            .logo { max-width: 200px; margin: 0 auto 20px; display: block; }
            .content { margin: 20px 0; }
            .footer { margin-top: 30px; font-size: 0.9em; color: #7f8c8d; text-align: center; }
            .button { background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="https://iili.io/3u6xz7a.md.png" alt="Elite Class Transfers" class="logo"> 
            <h1 class="header">Thank You for Your Driver Hire Request</h1>
            <div class="content">
                <p>Dear ' . htmlspecialchars($name) . ',</p>
                <p>We have received your request for a private driver starting on ' . htmlspecialchars($startDate) . '.</p>
                
                <h3>Your Request Details:</h3>
                <ul>
                    <li><strong>Pickup Time:</strong> ' . htmlspecialchars($pickUpTime) . '</li>
                    <li><strong>Passengers:</strong> ' . htmlspecialchars("$adults adults, $children children") . '</li>
                    <li><strong>Duration:</strong> ' . htmlspecialchars("$driverDays days, $driverHours hours/day") . '</li>
                </ul>';
    
    if (!empty($specialRequest)) {
        $clientContent .= '<p><strong>Special Requests:</strong><br>' . nl2br(htmlspecialchars($specialRequest)) . '</p>';
    }
    
    $clientContent .= '<p>Our team is reviewing your request and will contact you within 24 hours to confirm availability and finalize details.</p>
                <p>If you have any questions or need immediate assistance, please contact us at:</p>
                <p>Phone: +30 693 661 4936<br>
                Email: info@eliteclasstransfers.com</p>
                <!-- <p style="text-align: center;"><a href="https://eliteclasstransfers.com/" class="button">Contact Us</a></p> -->
            </div>
            <div class="footer">
                <p>Best regards,<br>The Elite Class Transfers Team</p>
                <p>&copy; ' . date('Y') . ' Elite Class Transfers. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';

    // Client recipients
    $clientMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $clientMail->addAddress($email);
    $clientMail->Subject = 'Your Driver Hire Request Confirmation #' . substr(md5(time()), 0, 8);
    $clientMail->Body = $clientContent;
    $clientMail->isHTML(true);
    $clientMail->AltBody = "Thank you for your driver hire request, $name.\n\n" .
        "We have received your request for a private driver starting on $startDate.\n\n" .
        "Our team will contact you within 24 hours to confirm availability.\n\n" .
        "If you have any questions, please contact support@eliteclasstransfers.com\n\n" .
        "Best regards,\nElite Class Transfers Team";
    $clientMail->CharSet = 'UTF-8';

    // Send both emails with error tracking
    $adminSent = $adminMail->send();
    $clientSent = $clientMail->send();

    // Prepare response
    $response = [
        'status' => 'success',
        'message' => 'Thank you! Your request has been submitted.'
    ];

    if (!$adminSent || !$clientSent) {
        $response['status'] = 'warning';
        $response['message'] = 'Request submitted, but email confirmation may not have been sent.';
        
        // Log which email failed
        if (!$adminSent) error_log("Failed to send admin email for driver hire: $email");
        if (!$clientSent) error_log("Failed to send client confirmation: $email");
    }

    // Return JSON response (better for AJAX handling)
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    // Log the full error
    error_log("Driver Hire Email Error: " . $e->getMessage());
    
    // Return JSON error
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'There was an error processing your request. Please try again later.'
    ]);
}
?>  