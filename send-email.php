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
    $mail->SMTPDebug = 0;
   $mail->Host = 'smtp.hostinger.com';
   $mail->Port = 465;
   $mail->SMTPAuth = true;
   $mail->Username = 'no-reply@eliteclasstransfers.com';
   $mail->Password = 'Noeliteclasstransfers23**';
   $mail->SMTPSecure = 'ssl';   

    //Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress('reservations@eliteclasstransfers.com'); // Replace with your recipient email address

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'New rental car booking';
    $mail->Body = "Pick-Up Location: $pickupLocation<br>Drop-Off Location: $dropLocation<br>Pick-Up Date: $pickDate<br>Drop-Off Date: $dropDate<br>Pick-Up Time: $pickTime<br>Drop-Off Time: $dropTime<br>Car Type: $carType<br>Name: $name<br>Email: $email<br>Phone: $phone";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
