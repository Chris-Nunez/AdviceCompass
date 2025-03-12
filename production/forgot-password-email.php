<?php
include 'config.php';

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Prepare and execute query securely
    $query = $conn->prepare("SELECT User_ID FROM Users WHERE Email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {


    } 
    else {
        // No user found
        echo json_encode(["status" => "error", "message" => "No user found with this email."]);
        exit();
    }

    // Close the connection
    $query->close();
    $conn->close();
}
?>