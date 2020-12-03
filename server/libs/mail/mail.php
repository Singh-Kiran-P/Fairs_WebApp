<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->Host = "mail.singh-kiran.educationhost.cloud";
$mail->Port = 587; // or 587
$mail->IsHTML(true);
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->SMTPAutoTLS = false;
$mail->SMTPSecure = false; // Enable TLS encryption, `ssl` also accepted
$mail->Username = "info@singh-kiran.educationhost.cloud";
$mail->Password = "ohh0Choo";
$mail->SetFrom("info@singh-kiran.educationhost.cloud");
$mail->Subject = "Test";
$mail->Body = "yo joppe";
$mail->AddAddress("joppe.wouters@student.uhasselt.be");

 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }
