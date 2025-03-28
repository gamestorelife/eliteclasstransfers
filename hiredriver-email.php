<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Get and sanitize form data
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
    die('Please provide valid name and email address');
}

try {
    // Create PHPMailer instance for admin notification
    $adminMail = new PHPMailer(true);
    
    // Server settings
    $adminMail->isSMTP();
    $adminMail->Host = 'smtp.hostinger.com';
    $adminMail->Port = 465;
    $adminMail->SMTPAuth = true;
    $adminMail->Username = 'no-reply@eliteclasstransfers.com';
    $adminMail->Password = 'Noeliteclasstransfers23**';
    $adminMail->SMTPSecure = 'ssl';
    $adminMail->SMTPDebug = 0;

    // Admin email content
    $adminContent = "<h1>New Hire a Driver Submission</h1>";
    $adminContent .= "<p><strong>Start Date:</strong> $startDate</p>";
    $adminContent .= "<p><strong>Pick Up Time:</strong> $pickUpTime</p>";
    $adminContent .= "<p><strong>Adults:</strong> $adults</p>";
    $adminContent .= "<p><strong>Children:</strong> $children</p>";
    $adminContent .= "<p><strong>Driver Days:</strong> $driverDays</p>";
    $adminContent .= "<p><strong>Driver Hours:</strong> $driverHours</p>";
    $adminContent .= "<p><strong>Special Request:</strong> $specialRequest</p>";
    $adminContent .= "<p><strong>Name:</strong> $name</p>";
    $adminContent .= "<p><strong>Email:</strong> $email</p>";
    $adminContent .= "<p><strong>Phone:</strong> $phone</p>";

    // Admin recipients
    $adminMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $adminMail->addAddress('rsveliteclasstransfers@gmail.com');
    $adminMail->Subject = 'New Hire a Driver Submission';
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
    $clientContent = "<h1>Thank You for Your Driver Hire Request</h1>";
    $clientContent .= "<p>Dear $name,</p>";
    $clientContent .= "<p>We have received your request to hire a driver starting on $startDate.</p>";
    $clientContent .= "<p>Our team will review your request and contact you shortly to confirm the details.</p>";
    $clientContent .= "<p>If you have any questions, please don't hesitate to contact us.</p>";
    $clientContent .= "<p>Best regards,<br>Elite Class Transfers Team</p>";

    // Client recipients
    $clientMail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $clientMail->addAddress($email);
    $clientMail->Subject = 'Your Driver Hire Request Confirmation';
    $clientMail->Body = $clientContent;
    $clientMail->isHTML(true);

    // Send both emails
    $adminSent = $adminMail->send();
    $clientSent = $clientMail->send();

    if ($adminSent && $clientSent) {
        echo 'Thank you! Your request has been submitted. A confirmation has been sent to your email.';
    } else {
        echo 'Your request has been submitted, but there was an issue sending the confirmation email.';
    }

} catch (Exception $e) {
    echo "Message could not be sent. Error: {$e->getMessage()}";
}
?>