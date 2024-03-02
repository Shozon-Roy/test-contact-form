<?php
header('Content-Type: application/json');

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to sanitize and validate input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if the form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $message = sanitizeInput($_POST['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit();
    }

    // Create a PHPMailer object
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Your SMTP server address
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_username';   // Your SMTP username
        $mail->Password   = 'your_password';   // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress('shozonroy2@gmail.com', 'Recipient Name');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Message from Contact Form';
        $mail->Body    = "Name: $name<br>Email: $email<br>Message: $message";

        // Send email
        $mail->send();
        
        echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
