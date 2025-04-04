<?php
session_start();
include 'config.php';

if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

if (!isset($_POST['category_id'])) {
    echo json_encode(["success" => false, "message" => "Category ID is missing."]);
    exit;
}

$user_id = $_SESSION['User_ID'];
$category_id = intval($_POST['category_id']);

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
