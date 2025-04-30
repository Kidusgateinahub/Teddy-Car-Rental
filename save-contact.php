<?php
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

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
        if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
            $errors[] = "Invalid phone number format";
        }
    }

    if (empty($_POST["email"])) {
        $errors[] = "Email is required";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
            // Redirect to contact page with success message
            header("Location: contact.html?status=success");
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // If there are errors, redirect back to contact page with error messages
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: contact.html?status=error");
        exit();
    }
} else {
    // If someone tries to access this page directly, redirect to contact page
    header("Location: contact.html");
    exit();
}

// Close the database connection
$conn->close();
?>
