<?php
include 'config.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Invalid token.");
}

$stmt = $conn->prepare("SELECT User_ID, Expiration FROM PasswordResets WHERE Token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($user_id, $expiration);
$stmt->fetch();
$stmt->close();

if (!$user_id || strtotime($expiration) < time()) {
    die("Token is invalid or expired.");
}
?>

<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
    <h2>Reset Your Password</h2>
    <form action="reset-password-process.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="password">New Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
