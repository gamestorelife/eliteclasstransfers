<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailerAutoload;



require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/PHPMailerAutoload.php';

// Get the form data
$pickupLocation = $_POST['gt-pickUplocation'];
$dropOffLocation = $_POST['gt-dropOfflocation'];
$pickupDate = $_POST['gt-pickUpDate'];
$pickupTime = $_POST['gt-pickUpTime'];
$flightNumber = $_POST['flight-number'];
$returnPickupDate = $_POST['pickUpDate-return'];
$returnPickupTime = $_POST['pickUpTime-return'];
$returnFlightNumber = $_POST['flight-number-return'];
$adults = $_POST['gt-adults'];
$children = $_POST['gt-children'];
$luggage = $_POST['gt-luggage'];

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['subject'];

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
   $mail->Host = 'smtp.hostinger.com';
   $mail->Port = 465;
   $mail->SMTPAuth = true;
   $mail->Username = 'no-reply@eliteclasstransfers.com';
   $mail->Password = 'Noeliteclasstransfers23**';
   $mail->SMTPSecure = 'ssl';   

    //Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email); // Replace with your recipient email address

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Booking Confirmation';
   

     $imageUrl = 'https://i.ibb.co/98QjqxL/email-tranfers-logo.png';
    
    $emailContent = "<div style='text-align: center; display: inline-table;'>";
    $emailContent .= "<h1 style='color: #333; font-size: 24px;'>Thanks You For Contacting Elite Class Transfers</h1>";
    $emailContent .= "<div>";
    $emailContent .= "<img src='$imageUrl' alt='Your Image Alt Text' style='max-width: 100%;'>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>Booking Details</p>";
    $emailContent .= "<table style='width: 100%; text-align: left; border: 2px solid black;'>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Pick-Up Location:</td><td style='font-size: 16px;'>$pickupLocation</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Drop-Off Location:</td><td style='font-size: 16px;'>$dropOffLocation</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Pick-Up Date:</td><td style='font-size: 16px;'>$pickupDate</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Pick-Up Time:</td><td style='font-size: 16px;'>$pickupTime</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Flight Number:</td><td style='font-size: 16px;'>$flightNumber</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Return Date :</td><td style='font-size: 16px;'>$returnPickupDate</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Return Time:</td><td style='font-size: 16px;'>$returnPickupTime</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Return Flight Number:</td><td style='font-size: 16px;'>$returnFlightNumber</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Number Of Adults:</td><td style='font-size: 16px;'>$adults</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Number Of Children:</td><td style='font-size: 16px;'>$children</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Number Of Luggage:</td><td style='font-size: 16px;'>$luggage</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Name:</td><td style='font-size: 16px;'>$name</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Email:</td><td style='font-size: 16px;'>$email</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Phone:</td><td style='font-size: 16px;'>$phone</td></tr>";
    $emailContent .= "</table>";
    $emailContent .= "</div>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>We Got Your Message.  And you will be contacted shortly</p>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>If you require immediate assistance, please call us at +30 693 661 4936</p>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>Thank you and enjoy!</p>";
    $emailContent .= "</div>";

    $mail->Body = $emailContent;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
