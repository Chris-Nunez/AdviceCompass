<?php
session_start();
include 'config.php'; 

if (!isset($_POST['category_id'])) {
    die("No category selected.");
}

$category_id = intval($_POST['category_id']);
$thread_title = $_POST['thread-title'];
$thread_text = $_POST['thread-text'];
$thread_imagePath = null; 

// Check if an image was uploaded
if (isset($_FILES["thread-image"]) && $_FILES["thread-image"]["error"] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/"; 
    $fileName = time() . "_" . basename($_FILES["thread-image"]["name"]); 
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["thread-image"]["tmp_name"], $targetFilePath)) {
            $thread_imagePath = htmlspecialchars($targetFilePath, ENT_QUOTES, 'UTF-8');
        } else {
            die("File upload failed.");
        }
    } else {
        die("Invalid file type.");
    }
}

// Insert into database
$query = $conn->prepare("INSERT INTO Threads (Thread_Title, Thread_Text, Thread_Image, User_ID, Industry_Thread_Category_ID) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("sssii", $thread_title, $thread_text, $thread_imagePath, $_SESSION['User_ID'], $category_id);

if (!$query->execute()) {
    die("Database Error: " . $query->error);
}

$query->close();
$conn->close();

header("Location: thread-category.php?category_id=" . urlencode($category_id));
exit();
?>
