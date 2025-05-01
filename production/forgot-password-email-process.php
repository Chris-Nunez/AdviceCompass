<?php
include 'config.php';
require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use SendGrid\Mail\Mail;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Lookup user
    $query = $conn->prepare("SELECT User_ID FROM Users WHERE Email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();

    // If user found, generate token & send email
    if ($user_id) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insert token into password_resets
        $query = $conn->prepare("INSERT INTO PasswordResets (User_ID, Token, Expiration) VALUES (?, ?, ?)");
        $query->bind_param("iss", $user_id, $token, $expires);
        $query->execute();
        $query->close();

        $resetLink = $_ENV['APP_URL'] . "/reset-password.php?token=$token";

        // Send email with SendGrid
        $emailSender = new Mail();
        $emailSender->setFrom("advicecompass.test@gmail.com", "AdviceCompass");
        $emailSender->setSubject("Password Reset Request");
        $emailSender->addTo($email);
        $emailSender->addContent("text/plain", "Click the link to reset your password: $resetLink");

        $sendgrid = new \SendGrid($_ENV['SENDGRID_API_KEY']);
        try {
            $response = $sendgrid->send($emailSender);
        } catch (Exception $e) {
            // Optionally log exception
        }
    }

    // Redirect with a success indicator, regardless of whether the email exists
    header("Location: forgot-password-email.php?sent=1");
    exit;
}
?>
