<?php

// Path of autoload.php of PHPMailer
require_once 'path/of/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_email($urls) {
    // Create a new PHPMailer object
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.domain.tld';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'username or email'; // Replace with your Zoho EU email address
        $mail->Password   = 'password'; // Replace with your Zoho EU email password
        $mail->SMTPSecure = 'ssl or tls';
        $mail->Port       = 465 or 587;

        // Email content
        $mail->setFrom('mail@domain.tld', 'Your Name');
        $mail->addAddress('to@domain.tld'); // Replace with the email address you want to send the email to
        $mail->isHTML(true);
        $mail->Subject = 'Missing translations on domain.tld';
        $mail->Body    = 'The following pages are missing translations:<br>' . implode('<br>', $urls);

        // Attempt to send the email
        $mail->send();
        echo "Email sent successfully!\n";
    } catch (Exception $e) {
        echo "Email failed to send. Error message: {$mail->ErrorInfo}\n";
    }
}
