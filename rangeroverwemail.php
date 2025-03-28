<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Retrieve form data
$email = $_POST['email'];

try {
    // Create a single PHPMailer instance
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@eliteclasstransfers.com';
    $mail->Password = 'Noeliteclasstransfers23**';
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output

    // Recipients
    $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
    $mail->addAddress($email);
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Elite Class Transfers';
    
    // Load HTML content from file
    $htmlFile = 'rangerovermessage.html';
    if (file_exists($htmlFile)) {
        $mail->msgHTML(file_get_contents($htmlFile), __DIR__);
    } else {
        $mail->Body = 'Thank you For Elite Class Transfers';
        error_log("HTML file not found: " . $htmlFile);
    }

    if(!$mail->send()) {
        echo 'Email could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo '<center><h1>Email has been sent.</h1></center>';
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>