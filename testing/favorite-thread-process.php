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

if (!isset($_POST['thread_id'])) {
    echo json_encode(["success" => false, "message" => "Thread ID is missing."]);
    exit;
}

$user_id = intval($_SESSION['User_ID']);
$thread_id = intval($_POST['thread_id']);

// Check if the thread is already favorited
$check_query = $conn->prepare("SELECT * FROM FavoriteThreads WHERE User_ID = ? AND Thread_ID = ?");
$check_query->bind_param("ii", $user_id, $thread_id);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows > 0) {
    // If already favorited, remove it from favorites
    $delete_query = $conn->prepare("DELETE FROM FavoriteThreads WHERE User_ID = ? AND Thread_ID = ?");
    $delete_query->bind_param("ii", $user_id, $thread_id);
    if ($delete_query->execute()) {
        echo json_encode(["success" => true, "message" => "Thread removed from favorites."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to remove favorite."]);
    }
} else {
    // If not favorited, add to favorites
    $insert_query = $conn->prepare("INSERT INTO FavoriteThreads (User_ID, Thread_ID) VALUES (?, ?)");
    $insert_query->bind_param("ii", $user_id, $thread_id);
    if ($insert_query->execute()) {
        echo json_encode(["success" => true, "message" => "Thread added to favorites."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add favorite."]);
    }
}
?>
