<?php
session_start();
include 'config.php';

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php?error=1");
    exit();
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo json_encode(["status" => "error", "message" => "Invalid CSRF token."]);
    exit();
}

if (!isset($_POST['category_id'])) {
    echo json_encode(["success" => false, "message" => "Category ID is missing."]);
    exit();
}

$user_id = $_SESSION['User_ID'];
$category_id = intval($_POST['category_id']);

$category_check = $conn->prepare("SELECT 1 FROM IndustryThreadCategories WHERE Industry_Thread_Category_ID = ?");
$category_check->bind_param("i", $category_id);
$category_check->execute();
$category_check->store_result();

if ($category_check->num_rows === 0) {
    $category_check->close();
    die("Invalid category.");
}
$categoryCheck->close();


// Check if the category is already favorited
$query = $conn->prepare("SELECT * FROM UserPreferredCategories WHERE User_ID = ? AND Industry_Thread_Category_ID = ?");
$query->bind_param("ii", $user_id, $category_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // Remove from favorites
    $delete_query = $conn->prepare("DELETE FROM UserPreferredCategories WHERE User_ID = ? AND Industry_Thread_Category_ID = ?");
    $delete_query->bind_param("ii", $user_id, $category_id);
    $delete_query->execute();
    echo json_encode(["success" => true, "message" => "Category removed from favorites."]);
} else {
    // Add to favorites
    $insert_query = $conn->prepare("INSERT INTO UserPreferredCategories (User_ID, Industry_Thread_Category_ID) VALUES (?, ?)");
    $insert_query->bind_param("ii", $user_id, $category_id);
    $insert_query->execute();
    echo json_encode(["success" => true, "message" => "Category added to favorites."]);
}

exit;
?>
