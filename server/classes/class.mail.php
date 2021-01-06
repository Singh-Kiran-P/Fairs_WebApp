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

    $this->_mail = new PHPMailer();
    $this->_mail->IsSMTP();
    $this->_mail->Mailer = "smtp";
    $this->_mail->SMTPDebug  = 1;
    $this->_mail->SMTPAuth   = TRUE;
    $this->_mail->SMTPSecure = "tls";
    $this->_mail->Port       = 587;
    $this->_mail->Host       = "smtp.gmail.com";
    $this->_mail->Username   = "smart.iot.singh@gmail.com";
    $this->_mail->Password   = "FA4UpyL3y9naQwH";
    $this->_mail->IsHTML(true);
  }

  public function sendEmail($to, $subject, $body)
  {

    $this->_mail->AddAddress($to, "");
    $this->_mail->SetFrom("smart.iot.singh@gmail.com", "Singh Kiran");
    $this->_mail->Subject = $subject;
    $this->_mail->MsgHTML($body);
    if (!$this->_mail->Send()) {
      return "Error sending email";
    } else {
      return "Email sent successfully";
    }
  }
}
