<?php
session_start();
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);
    $user_id = $_SESSION['User_ID']; 

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    // Check if the category is already favorited
    $query = $conn->prepare("SELECT * FROM UserPreferredCategories WHERE User_ID = ? AND Industry_Thread_Category_ID = ?");
    $query->bind_param("ii", $user_id, $category_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // If it exists, remove it (Unfavorite)
        $query = $conn->prepare("DELETE FROM UserPreferredCategories WHERE User_ID = ? AND Industry_Thread_Category_ID = ?");
        $query->bind_param("ii", $user_id, $category_id);
        $query->execute();
        echo json_encode(['success' => true, 'action' => 'unfavorited']);
    } else {
        // If it doesn't exist, insert it (Favorite)
        $query = $conn->prepare("INSERT INTO UserPreferredCategories (User_ID, Industry_Thread_Category_ID) VALUES (?, ?)");
        $query->bind_param("ii", $user_id, $category_id);
        $query->execute();
        echo json_encode(['success' => true, 'action' => 'favorited']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$query->close();
$conn->close();
?>
