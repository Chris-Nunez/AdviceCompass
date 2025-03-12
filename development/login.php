<?php
session_start();
include 'config.php';

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query securely
    $query = $conn->prepare("SELECT User_ID, Username, User_Password FROM Users WHERE Email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row['User_Password'])) {
            // Start session and store user details
            $_SESSION['User_ID'] = $row['User_ID'];
            $_SESSION['Username'] = $row['Username'];

            // Send success response
            echo json_encode(["status" => "success", "message" => "Login successful! Redirecting..."]);
            exit();
        } else {
            // Incorrect password
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
            exit();
        }
    } else {
        // No user found
        echo json_encode(["status" => "error", "message" => "No user found with this email."]);
        exit();
    }

    // Close the connection
    $query->close();
    $conn->close();
}
?>
