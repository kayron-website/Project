<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $Name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);
    $userType = htmlspecialchars($_POST['user_type']);
    $recipientEmail = htmlspecialchars($_POST['recipient_email']);

    // Instantiate PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'scheduler.website@gmail.com'; // Your SMTP username
        $mail->Password = 'ctmrabmbuxjnmsso'; // Your SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Sender and recipient
        $mail->setFrom('scheduler.website@gmail.com'); // Your email address and name
        $mail->addAddress($recipientEmail); // Recipient's email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Created!'; // Subject line
        $mail->Body    = 'Hello, ' . $Name . '<br> Password: ' .$password . '<br> Type: ' .$userType; // Email body

        // Send email
        $mail->send();
        header("Location: register-success.php");
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
?>
