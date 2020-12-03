<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../libs/mail/src/Exception.php';
require __DIR__ . '/../libs/mail/src/PHPMailer.php';
require __DIR__ . '/../libs/mail/src/SMTP.php';

class Mail
{
  private $_mail;

  public function __construct()
  {
    // get the env variables
    require __DIR__ . '/../config/config.php';

    $this->_mail = new PHPMailer(); // create a new object
    $this->_mail->IsSMTP(); // enable SMTP
    // $this->_mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $this->_mail->Host = $stmpHost;
    $this->_mail->Port = 587; // or 587
    $this->_mail->IsHTML(true);
    $this->_mail->SMTPAuth = true; // Enable SMTP authentication
    $this->_mail->SMTPAutoTLS = false;
    $this->_mail->SMTPSecure = false; // Enable TLS encryption, `ssl` also accepted
    $this->_mail->Username = $username_email;
    $this->_mail->Password = $password_email;
    $this->_mail->SetFrom($emailFrom);
  }

  public function sendEmail($to, $subject, $body)
  {
    $this->_mail->Subject = $subject;
    $this->_mail->Body = $body;
    $this->_mail->AddAddress($to);

    if (!$this->_mail->Send()) {
      echo "Mailer Error: " . $this->_mail->ErrorInfo;
    } else {
      echo "Message has been sent";
    }
  }
}
