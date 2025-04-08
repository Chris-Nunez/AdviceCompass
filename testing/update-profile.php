<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['User_ID'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['User_ID'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Handle Profile Image Upload
    if (isset($_FILES["profile-image"]) && !empty($_FILES["profile-image"]["name"])) {
        $fileName = time() . "_" . basename($_FILES["profile-image"]["name"]);
        $targetFilePath = "uploads/" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile-image"]["tmp_name"], $targetFilePath)) {
                $profileImagePath = htmlspecialchars($targetFilePath, ENT_QUOTES, 'UTF-8');

                $query = $conn->prepare("UPDATE Users SET Profile_Image = ? WHERE User_ID = ?");
                $query->bind_param("si", $profileImagePath, $user_id);
                if ($query->execute()) {
                    echo "success:" . $profileImagePath;
                } else {
                    echo "error updating profile image.";
                }
                $query->close();
            } else {
                echo "error uploading file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.";
        }
    }

    // Handle text updates (bio, location, occupation)
    if (isset($_POST['field']) && isset($_POST['value'])) {
        $field = $_POST['field'];
        $value = $_POST['value'];

        $allowed_fields = ['bio_text', 'location_state', 'occupation_title'];
        if (!in_array($field, $allowed_fields)) {
            die("Invalid field.");
        }

        $query = $conn->prepare("UPDATE Users SET $field = ? WHERE User_ID = ?");
        $query->bind_param("si", $value, $user_id);

        if ($query->execute()) {
            echo "success";
        } else {
            echo "error updating profile.";
        }

        $query->close();
    }

    $conn->close(); 
}
?>
