<?php
// Include common functions
require_once 'functions.php';

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teddy_car_rental";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

    // If there are no errors, proceed with database insertion
    if (empty($errors)) {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO contact_messages (phone, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $phone, $email, $message);

        // Execute the statement
        if ($stmt->execute()) {
            // Re-populate $_POST for email script
            $_POST['phone'] = $phone;
            $_POST['email'] = $email;
            $_POST['message'] = $message;

            // Send the email
            include 'send-email.php';

            // Redirect to contact page with success message
            header("Location: contact.html?status=success");
            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }

    // If there are errors, store in session and redirect
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: contact.html?status=error");
    exit();
} else {
    // Prevent direct access
    header("Location: contact.html");
    exit();
}

// Close DB connection
$conn->close();
?>
