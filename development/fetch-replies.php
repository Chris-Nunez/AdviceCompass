<?php
include 'config.php';

if (isset($_GET['comment_id'])) {
    $comment_id = (int) $_GET['comment_id'];

    $query = $conn->prepare("SELECT * FROM ThreadCommentReplies
                             INNER JOIN Users ON ThreadCommentReplies.User_ID = Users.User_ID
                             WHERE Thread_Comment_ID = ? 
                             ORDER BY Thread_Comment_Reply_Date_Time DESC");
    $query->bind_param("i", $comment_id);
    $query->execute();
    $result = $query->get_result();

    $replies = [];
    while ($row = $result->fetch_assoc()) {
        $replies[] = [
            'reply_id' => $row['Thread_Comment_Reply_ID'],
            'username' => $row['Username'],
            'text' => $row['Thread_Comment_Reply_Text'],
            'date_time' => $row['Thread_Comment_Reply_Date_Time'],
            'like_count' => $row['Thread_Comment_Reply_Like_Count'],
            'dislike_count' => $row['Thread_Comment_Reply_Dislike_Count'],
        ];
    }

    echo json_encode($replies);
} else {
    echo json_encode([]);
}

$query->close();
$conn->close();
?>
