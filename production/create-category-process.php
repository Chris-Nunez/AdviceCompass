<?php
session_start();
include 'config.php';

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category-name'];
    $category_description = $_POST['category-description'];
    $user_id = $_SESSION['User_ID'];

    // Validate input
    if (strlen($category_name) < 3 || strlen($category_name) > 50) {
        echo json_encode(["status" => "error", "message" => "Category Name must be between 3 and 50 characters."]);
        exit();
    }
    if (strlen($category_description) < 10) {
        echo json_encode(["status" => "error", "message" => "Category Description must be at least 10 characters long."]);
        exit();
    }

    // Check if category already exists
    $query = $conn->prepare("SELECT Industry_Thread_Category_Name FROM IndustryThreadCategories WHERE Industry_Thread_Category_Name = ?");
    $query->bind_param("s", $category_name);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Category already exists."]);
        exit();
    }
    
    // Insert new category
    $query = $conn->prepare("INSERT INTO IndustryThreadCategories (Industry_Thread_Category_Name, Industry_Thread_Category_Description, User_ID) VALUES (?, ?, ?)");
    $query->bind_param("ssi", $category_name, $category_description, $user_id);
    
    if ($query->execute()) {
        echo json_encode(["status" => "success", "message" => "Category created successfully!"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit();
    }
    
    $query->close();
    $conn->close();
}
?>
