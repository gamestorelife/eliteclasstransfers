

<?php
   
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\PHPMailerAutoload;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/PHPMailerAutoload.php';

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$comments = $_POST['message'];
$phone = $_POST['subject'];


   $mail = new PHPMailer();
   $mail->isSMTP();
   $mail->Host = 'smtp.hostinger.com';
   $mail->Port = 465;
   $mail->SMTPAuth = true;
   $mail->Username = 'no-reply@eliteclasstransfers.com';
   $mail->Password = 'Noeliteclasstransfers23**';
   $mail->SMTPSecure = 'ssl';
   $mail->isHTML(true);
   $mail->setFrom('no-reply@eliteclasstransfers.com', 'Elite Class Transfers');
   $mail->addAddress('info@eliteclasstransfers.com', $name);
   $mail->Subject = 'Client Has been submit on Elite Class Transfers';

   $mail->Body = "Name: $name <br>Email: $email<br>Phone: $phone<br>Comment: $comments";



   
  if(!$mail->send()) {
    echo 'Email could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo '<center><h1>Email has been sent.</h1></center>';
}






?>