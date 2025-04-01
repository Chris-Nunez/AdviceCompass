<?php
session_start();
include 'config.php';

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $occupation_title = $_POST['occupation_title'];
    $bio_text = $_POST['bio_text'];
    $location_state = $_POST['location_state'];

    //Check if email or username already exists
    $query = $conn->prepare("SELECT Email, Username FROM Users WHERE Email = ? OR Username = ?");
    $query->bind_param("ss", $email, $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['Email'] == $email) {
            echo json_encode(["status" => "error", "message" => "Email already exists"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Username already exists"]);
        }
        exit();
    } 
    else {
        //Insert new user with the new fields
        $query = $conn->prepare("INSERT INTO Users (First_Name, Last_Name, Username, Email, User_Password, Occupation_Title, Bio_Text, Location_State) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssssss", $first_name, $last_name, $username, $email, $password, $occupation_title, $bio_text, $location_state);

        if ($query->execute()) {
            echo json_encode(["status" => "success", "message" => "Registration successful! Redirecting..."]);
            exit();  
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
            exit();
        }
        
    }

    $query->close();
    $conn->close();
}
?>

