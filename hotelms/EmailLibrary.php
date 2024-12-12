<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer classes
require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

function sendMail($to, $subject, $message, $from = 'sender@gmail.com') {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debug output in production
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = 'your-email@gmail.com'; // Replace with your Gmail email
        $mail->Password = 'your-app-password'; // Replace with your Gmail App Password

        // Sender and recipient settings
        $mail->setFrom($from, 'Sender Name');
        $mail->addAddress($to); // Add recipient email
        $mail->addReplyTo($from, 'Sender Name'); // Reply-to email

        // Setting the email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send the email
        $mail->send();
        echo "Email message sent successfully.";
    } catch (Exception $e) {
        // Handle error gracefully
        echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
