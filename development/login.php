<?php
    include 'config.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM Users WHERE Email='$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['User_Password'])) {
                echo "Login successful!";
                session_start();
                $_SESSION['User_ID'] = $row['User_ID'];
                $_SESSION['Username'] = $row['Username'];
            } 
            else {
                echo "Invalid password.";
            }
        } 
        else {
            echo "No user found.";
        }

        $conn->close();
    }
?>