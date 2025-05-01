<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['thread_id']) && isset($_SESSION['User_ID'])) {

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo json_encode(["status" => "error", "message" => "Invalid CSRF token."]);
        exit();
    }

    if (!isset($_SESSION['User_ID'])) {
        header("Location: login.php?error=1");
        exit();
    }
    
    $thread_id = intval($_POST['thread_id']);
    $user_id = $_SESSION['User_ID'];

    // Get the thread and category info first
    $query = $conn->prepare("SELECT Industry_Thread_Category_ID FROM Threads WHERE Thread_ID = ? AND User_ID = ?");
    $query->bind_param("ii", $thread_id, $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $category_id = $row['Industry_Thread_Category_ID'];

        // Delete the thread
        $query = $conn->prepare("DELETE FROM Threads WHERE Thread_ID = ?");
        $query->bind_param("i", $thread_id);
        if ($query->execute()) {
            echo "success:" . $category_id;
        } else {
            echo "Failed to delete thread.";
        }
    } else {
        echo "Unauthorized.";
    }
} else {
    echo "Invalid request.";
}
?>
