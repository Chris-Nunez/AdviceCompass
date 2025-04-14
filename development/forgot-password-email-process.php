<?php
include 'config.php';
require 'vendor/autoload.php'; // Composer autoload

use SendGrid\Mail\Mail;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Lookup user
    $stmt = $conn->prepare("SELECT User_ID FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        echo "No user found with that email.";
        exit;
    }

    // Generate token and expiration
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Store in password_resets
    $stmt = $conn->prepare("INSERT INTO PasswordResets (User_ID, Token, Expiration) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $token, $expires);
    $stmt->execute();
    $stmt->close();

    // Create reset link
    $resetLink = "https://development.local/reset-password.php?token=$token";

    // Send email using SendGrid
    $emailSender = new Mail();
    $emailSender->setFrom("advicecompass.test@gmail.com", "AdviceCompass");
    $emailSender->setSubject("Password Reset Request");
    $emailSender->addTo($email);
    $emailSender->addContent("text/plain", "Click the link to reset your password: $resetLink");

    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    
    try {
        $response = $sendgrid->send($emailSender);
        echo "Reset link sent! Check your email.";
    } catch (Exception $e) {
        echo 'Caught exception: '. $e->getMessage();
    }
}
?>
