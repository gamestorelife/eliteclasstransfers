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
$pickupLocation = $_POST['pickupLocation'];
$dropLocation = $_POST['dropLocation'];
$pickDate = $_POST['pickDate'];
$dropDate = $_POST['dropDate'];
$pickTime = $_POST['pickTime'];
$dropTime = $_POST['dropTime'];
$carType = $_POST['car-type'];

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['subject'];

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
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
   

     $imageUrl = 'https://i.ibb.co/gPdtqNw/image.png';
     
    
    $emailContent = "<div style='text-align: center; display: inline-table;'>";
    $emailContent .= "<h1 style='color: #333; font-size: 24px;'>Thanks You For Contacting Elite Class Transfers</h1>";
    $emailContent .= "<div>";
    $emailContent .= "<img src='$imageUrl' alt='mainlogo' style='max-width: 100%;'>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>Booking Details</p>";
    $emailContent .= "<table style='width: 100%; text-align: left; border: 2px solid black;'>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Pick-Up Location:</td><td style='font-size: 16px;'>$pickupLocation</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Drop-Off Location:</td><td style='font-size: 16px;'>$dropLocation</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Pick-Up Date:</td><td style='font-size: 16px;'>$pickDate</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Drop-Off Date:</td><td style='font-size: 16px;'>$dropDate</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Pick-Up Time:</td><td style='font-size: 16px;'>$pickTime</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Drop-Off Time:</td><td style='font-size: 16px;'>$dropTime</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Car Type:</td><td style='font-size: 16px;'>$carType</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Name:</td><td style='font-size: 16px;'>$name</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Email:</td><td style='font-size: 16px;'>$email</td></tr>";
    $emailContent .= "<tr><td style='font-size: 16px;'>Phone:</td><td style='font-size: 16px;'>$phone</td></tr>";
    $emailContent .= "</table>";
    $emailContent .= "</div>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>We Got Your Message.  And you will be contacted shortly</p>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>Thank you & have fun!</p>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'>Or you call us Now on</p>";
    $emailContent .= "<p style='font-size: 19px; font-weight: bold;'> +30 6977 020 552</p>";
    

    $emailContent .= "</div>";

     $mail->Body = $emailContent;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
