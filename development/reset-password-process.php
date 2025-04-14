<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'];

    if (!$token || !$password) {
        die("Missing data.");
    }

    // Find user by token
    $stmt = $conn->prepare("SELECT User_ID FROM PasswordResets WHERE Token = ? AND Expiration > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$user_id) {
        die("Token expired or invalid.");
    }

    // Hash new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $stmt = $conn->prepare("UPDATE Users SET User_Password = ? WHERE User_ID = ?");
    $stmt->bind_param("si", $hashedPassword, $user_id);
    $stmt->execute();
    $stmt->close();

    // Delete used token
    $stmt = $conn->prepare("DELETE FROM PasswordResets WHERE Token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();

    // Redirect to the login page or any success page
    header("Location: index.html");  // Change this to your desired page
    exit;  // Make sure to call exit after header to stop further script execution
}
?>
