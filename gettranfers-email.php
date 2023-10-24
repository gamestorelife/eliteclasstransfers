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

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['subject'];
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
    $mail->addAddress('reservations@eliteclasstransfers.com'); // Replace with your recipient email address

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'New Get Tranfers booking';

    $mail->Body = "Name: $name<br>Email: $email<br>Phone: $phone<br>Pick Up Location: $pickupLocation<br>Drop Off Location: $dropOffLocation<br>Pick Up Date: $pickupDate<br>Pick Up Time: $pickupTime<br>Flight Number: $flightNumber<br>Return Pick Up Date: $returnPickupDate<br>Return Pick Up Time: $returnPickupTime<br>Return Flight Number: $returnFlightNumber<br>Adults: $adults<br>Children: $children<br>Luggage: $luggage";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
