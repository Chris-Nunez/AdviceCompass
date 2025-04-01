<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['User_ID'])) {
        echo json_encode(["success" => false, "message" => "You must be logged in to comment."]);
        exit;
    }

    $thread_id = intval($_POST['thread_id']);
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['User_ID'];
    $username = $_SESSION['Username'];

    if (empty($comment_text)) {
        echo json_encode(["success" => false, "message" => "Comment cannot be empty."]);
        exit;
    }

    // Insert the comment into the ThreadComments table
    $query = $conn->prepare("INSERT INTO ThreadComments (Thread_Comment_Text, User_ID, Thread_ID) VALUES (?, ?, ?)");
    $query->bind_param("sii", $comment_text, $user_id, $thread_id);

    if ($query->execute()) {
        $comment_id = $query->insert_id;
        $comment_date_time = date("Y-m-d H:i:s"); // Current timestamp

        // Update the Thread_Comment_Count field in the Threads table
        $query = $conn->prepare("UPDATE Threads SET Thread_Comment_Count = Thread_Comment_Count + 1 WHERE Thread_ID = ?");
        $query->bind_param("i", $thread_id);
        $query->execute();
        $query->close();

        echo json_encode([
            "success" => true,
            "message" => "Comment added successfully!",
            "comment_id" => $comment_id,
            "username" => htmlspecialchars($username),
            "comment_text" => htmlspecialchars($comment_text),
            "comment_date_time" => htmlspecialchars($comment_date_time),
            "comment_like_count" => 0,
            "comment_dislike_count" => 0,
            "comment_reply_count" => 0
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to post comment."]);
    }

    $query->close();
    $conn->close();
}

?>
