<?php
session_start();
include 'config.php';

// Check if logged in
if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to reply."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    if (!isset($_POST['comment_id'], $_POST['reply_text'])) {
        echo json_encode(["success" => false, "message" => "Invalid input."]);
        exit;
    }

    $comment_id = intval($_POST['comment_id']);
    $reply_text = trim($_POST['reply_text']);
    $user_id = $_SESSION['User_ID'];
    
    if (empty($reply_text)) {
        echo json_encode(["success" => false, "message" => "Reply cannot be empty."]);
        exit;
    }

    // Insert reply into the database
    $stmt = $conn->prepare("INSERT INTO ThreadCommentReplies (Thread_Comment_Reply_Text, User_ID, Thread_Comment_ID) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $reply_text, $user_id, $comment_id);

    if ($stmt->execute()) {
        $reply_id = $stmt->insert_id;
        $reply_date_time = date("Y-m-d H:i:s");

        // Update Thread_Comment_Reply_Count in ThreadComments table
        $update_stmt = $conn->prepare("UPDATE ThreadComments SET Thread_Comment_Reply_Count = Thread_Comment_Reply_Count + 1 WHERE Thread_Comment_ID = ?");
        $update_stmt->bind_param("i", $comment_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo json_encode([
            "success" => true,
            "message" => "Reply added successfully!",
            "reply_id" => $reply_id,
            "username" => htmlspecialchars($_SESSION['Username']),
            "reply_text" => htmlspecialchars($reply_text),
            "reply_date_time" => $reply_date_time
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to post reply."]);
    }

    $stmt->close();
    $conn->close();
}
?>
