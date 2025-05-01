<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $token = $_SESSION['reset_token'] ?? '';
    $user_id = $_SESSION['reset_user_id'] ?? null;

    if (!$token || !$user_id || !$password) {
        die("Invalid session or missing data.");
    }

    // (Optional) Re-validate token from DB
    $query = $conn->prepare("SELECT Expiration FROM PasswordResets WHERE Token = ? AND User_ID = ?");
    $query->bind_param("si", $token, $user_id);
    $query->execute();
    $query->bind_result($expiration);
    $query->fetch();
    $query->close();

    if (!$expiration || strtotime($expiration) < time()) {
        die("Token expired or invalid.");
    }

    // Hash new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $query = $conn->prepare("UPDATE Users SET User_Password = ? WHERE User_ID = ?");
    $query->bind_param("si", $hashedPassword, $user_id);
    $query->execute();
    $query->close();

    // Delete used token
    $query = $conn->prepare("DELETE FROM PasswordResets WHERE Token = ?");
    $query->bind_param("s", $token);
    $query->execute();
    $query->close();

    // Clear session data
    unset($_SESSION['reset_token'], $_SESSION['reset_user_id']);

    // Redirect to success page or login
    header("Location: login.php");
    exit;
}
?>
