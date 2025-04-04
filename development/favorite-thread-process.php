<?php
session_start();
include 'config.php';

if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to favorite threads."]);
    exit;
}

if (!isset($_POST['thread_id'])) {
    echo json_encode(["success" => false, "message" => "Thread ID is missing."]);
    exit;
}

$user_id = intval($_SESSION['User_ID']);
$thread_id = intval($_POST['thread_id']);

// Check if the thread is already favorited
$checkQuery = $conn->prepare("SELECT * FROM FavoriteThreads WHERE User_ID = ? AND Thread_ID = ?");
$checkQuery->bind_param("ii", $user_id, $thread_id);
$checkQuery->execute();
$result = $checkQuery->get_result();

if ($result->num_rows > 0) {
    // If already favorited, remove it from favorites
    $deleteQuery = $conn->prepare("DELETE FROM FavoriteThreads WHERE User_ID = ? AND Thread_ID = ?");
    $deleteQuery->bind_param("ii", $user_id, $thread_id);
    if ($deleteQuery->execute()) {
        echo json_encode(["success" => true, "message" => "Thread removed from favorites."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to remove favorite."]);
    }
} else {
    // If not favorited, add to favorites
    $insertQuery = $conn->prepare("INSERT INTO FavoriteThreads (User_ID, Thread_ID) VALUES (?, ?)");
    $insertQuery->bind_param("ii", $user_id, $thread_id);
    if ($insertQuery->execute()) {
        echo json_encode(["success" => true, "message" => "Thread added to favorites."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add favorite."]);
    }
}
?>
