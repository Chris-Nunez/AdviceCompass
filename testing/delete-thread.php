<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['thread_id']) && isset($_SESSION['User_ID'])) {
    $thread_id = intval($_POST['thread_id']);
    $user_id = $_SESSION['User_ID'];

    // Get the thread and category info first
    $stmt = $conn->prepare("SELECT Industry_Thread_Category_ID FROM Threads WHERE Thread_ID = ? AND User_ID = ?");
    $stmt->bind_param("ii", $thread_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $category_id = $row['Industry_Thread_Category_ID'];

        // Delete the thread
        $delete_stmt = $conn->prepare("DELETE FROM Threads WHERE Thread_ID = ?");
        $delete_stmt->bind_param("i", $thread_id);
        if ($delete_stmt->execute()) {
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
