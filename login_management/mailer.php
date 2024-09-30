<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";

$mail = new PHPMailer(true);

$mail->SMTPDebug = 0;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port = 465;
$mail->Username = "YOUR_EMAIL_ADDRESS";
$mail->Password = "YOUR_PASSWWORD";

$mail->isHTML(true);

return $mail;
