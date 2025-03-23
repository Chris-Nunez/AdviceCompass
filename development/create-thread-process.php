<?php
session_start();
include 'config.php'; // Connect to DB

if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);  // Ensure it's an integer
} 
else {
    die("No category selected.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $thread_title = $_POST['thread-title'];
    $thread_text = $_POST['thread-text'];
    $thread_imagePath = null; // Default if no image is uploaded

    // Check if an image was uploaded
    if (!empty($_FILES["thread-image"]["name"])) {
        $targetDir = "uploads/"; // Folder to store images
        $fileName = basename($_FILES["thread-image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName; // Add timestamp to avoid duplicates
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        // Validate file type
        if (in_array($fileType, $allowedTypes)) {
            // Move file to server folder
            if (move_uploaded_file($_FILES["thread-image"]["tmp_name"], $targetFilePath)) {
                $thread_imagePath = $targetFilePath; // Store file path in DB
            } else {
                die("Error uploading the file.");
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.");
        }
    }

    // Insert thread into the database
    $query = $conn->prepare("INSERT INTO Threads (Thread_Title, Thread_Text, Thread_Image, User_ID, Industry_Thread_Category_ID) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssii", $thread_title, $thread_text, $thread_imagePath, $_SESSION['User_ID'], $category_id);
    $query->execute();
    $query->close(); // Close after execution

    // Get the updated thread count
    $query = $conn->prepare("SELECT COUNT(*) FROM Threads WHERE Industry_Thread_Category_ID = ?");
    $query->bind_param("i", $category_id);
    $query->execute();
    $query->bind_result($thread_count);
    $query->fetch();
    $query->close(); // Close after fetching

    $query = $conn->prepare("UPDATE IndustryThreadCategories SET Industry_Thread_Category_Thread_Count = ? WHERE Industry_Thread_Category_ID = ?");
    $query->bind_param("ii", $thread_count, $category_id);
    $query->execute();

    if ($query->execute()) {
        echo "Thread created successfully!";
        header("Location: thread-category.php?category_id=" . urlencode($category_id));
        exit();
    } 
    else {
        echo "Error: " . $conn->error;
    }

    // Close connection
    $query->close();
    $conn->close();
}
?>
