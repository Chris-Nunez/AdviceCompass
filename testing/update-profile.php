<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php?error=1");
    exit();
}

$user_id = $_SESSION['User_ID'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['action']) && $_POST['action'] === 'remove_profile_image') {
        // Get the current profile image path from DB
        $query = $conn->prepare("SELECT Profile_Image FROM Users WHERE User_ID = ?");
        $query->bind_param("i", $user_id);
        $query->execute();
        $query->bind_result($currentImage);
        $query->fetch();
        $query->close();
    
        // Remove image file if it exists
        if (!empty($currentImage) && file_exists($currentImage)) {
            unlink($currentImage);
        }
    
        // Update DB: set Profile_Image to NULL
        $query = $conn->prepare("UPDATE Users SET Profile_Image = NULL WHERE User_ID = ?");
        $query->bind_param("i", $user_id);
        if ($query->execute()) {
            echo "success: image removed";
        } else {
            echo "error: could not remove image";
        }
        $query->close();
    }

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
                    echo "error updating profile image: " . $query->error;
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
