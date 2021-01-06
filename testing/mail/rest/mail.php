<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->SMTPDebug  = 1;
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "smart.iot.singh@gmail.com";
$mail->Password   = "FA4UpyL3y9naQwH";
$mail->IsHTML(true);
$mail->AddAddress("bram.droogmans@student.uhasselt.be", "recipient-name");
$mail->SetFrom("from-email@gmail.com", "from-name");
$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
$mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
$mail->MsgHTML($content);
if(!$mail->Send()) {
  echo "Error while sending Email.";
  var_dump($mail);
} else {
  echo "Email sent successfully";
}
