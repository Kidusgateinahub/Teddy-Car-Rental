<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require_once 'functions.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables
    $phone = $email = $message = "";
    $errors = [];

    // Validate and sanitize inputs
    if (empty($_POST["phone"])) {
        $errors[] = "Phone number is required";
    } else {
        $phone = sanitize_input($_POST["phone"]);
        if (!validate_phone($phone)) {
            $errors[] = "Invalid phone number format";
        }
    }

    if (empty($_POST["email"])) {
        $errors[] = "Email is required";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!validate_email($email)) {
            $errors[] = "Invalid email format";
        }
    }

    if (empty($_POST["message"])) {
        $errors[] = "Message is required";
    } else {
        $message = sanitize_input($_POST["message"]);
    }

    // If there are no errors, proceed with email sending
    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // Enable debugging
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kidusgate@gmail.com';  // Your Gmail address
            $mail->Password   = 'zfgn vnzr vrpw udxc';    // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('kidusgate@gmail.com', 'Teddy Car Rental');
            $mail->addAddress('kidusgate@gmail.com');   // Your email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission - Teddy Car Rental';
            $mail->Body    = "
                <h2>New Contact Form Submission</h2>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Message:</strong> {$message}</p>
            ";

            $mail->send();
            return true; // Return true if email is sent successfully
        } catch (Exception $e) {
            $errors[] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            error_log("Email Error: " . $mail->ErrorInfo);
            return false; // Return false if email sending fails
        }
    }
    return false; // Return false if there are validation errors
} else {
    header("Location: contact.html");
    exit();
}
?>
