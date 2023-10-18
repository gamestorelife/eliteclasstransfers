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

$startDate = $_POST['startDate'];
$pickUpTime = $_POST['hpickUpTime'];
$adults = $_POST['hAdults'];
$children = $_POST['hChildren'];
$driverDays = $_POST['driverDays'];
$driverHours = $_POST['driverHours'];
$specialRequest = $_POST['message'];
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
    $mail->addAddress('info@eliteclasstransfers.com'); // Replace with your recipient email address

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'New Hire a driver Submission';

    $emailContent = "<h1>New Hire a driver Submission</h1>";
    $emailContent .= "<p>Start Date: $startDate</p>";
    $emailContent .= "<p>Pick Up Time: $pickUpTime</p>";
    $emailContent .= "<p>Adults: $adults</p>";
    $emailContent .= "<p>Children: $children</p>";
    $emailContent .= "<p>Driver Days: $driverDays</p>";
    $emailContent .= "<p>Driver Hours: $driverHours</p>";
    $emailContent .= "<p>Special Request: $specialRequest</p>";
    $emailContent .= "<p>Name: $name</p>";
    $emailContent .= "<p>Email: $email</p>";
    $emailContent .= "<p>Phone: $phone</p>";

    $mail->Body = $emailContent;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
