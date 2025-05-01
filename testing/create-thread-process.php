<?php
session_start();
include 'config.php'; 

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo json_encode(["status" => "error", "message" => "Invalid CSRF token."]);
    exit();
}

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php?error=1");
    exit();
}

if (!isset($_POST['category_id'])) {
    die("No category selected.");
}

$category_id = intval($_POST['category_id']);
$thread_title = $_POST['thread-title'];
$thread_text = $_POST['thread-text'];
$thread_imagePath = null; 

$check_category = $conn->prepare("SELECT 1 FROM IndustryThreadCategories WHERE Industry_Thread_Category_ID = ?");
$check_category->bind_param("i", $category_id);
$check_category->execute();
$check_category->store_result();

if ($check_category->num_rows === 0) {
    $check_category->close();
    die("Invalid category.");
}
$check_category->close();


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
