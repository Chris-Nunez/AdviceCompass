<?php
include 'config.php'; // Adjust based on your setup

$thread_id = $_GET['thread_id'] ?? '';

if (!$thread_id) {
    echo json_encode(["error" => "Thread ID is missing"]);
    exit;
}

$query = $conn->prepare("SELECT * FROM ThreadComments 
                        INNER JOIN Users ON ThreadComments.User_ID = Users.User_ID
                        WHERE Thread_ID = ? 
                        ORDER BY Thread_Comment_Date_Time DESC");
$query->bind_param("i", $thread_id);
$query->execute();
$result = $query->get_result();

$comment_id = [];
$comment_text = [];
$comment_date_time = [];
$comment_like_count = [];
$comment_dislike_count = [];
$comment_reply_count = [];

while ($row = $result->fetch_assoc()) {
    $comment_id[] = $row['Thread_Comment_ID'];
    $comment_username[] = $row['Username'];
    $comment_text[] = $row['Thread_Comment_Text'];
    $comment_date_time[] = $row['Thread_Comment_Date_Time'];
    $comment_like_count[] = $row['Thread_Comment_Like_Count'];
    $comment_dislike_count[] = $row['Thread_Comment_Dislike_Count'];
    $comment_reply_count[] = $row['Thread_Comment_Reply_Count'];
}


$query = $conn->prepare("SELECT * FROM ThreadCommentReplies
                             INNER JOIN Users ON ThreadCommentReplies.User_ID = Users.User_ID
                             WHERE Thread_Comment_ID = ? 
                             ORDER BY Thread_Comment_Reply_Date_Time DESC");
$query->bind_param("i", $comment_id); // Use $comment_id here to match the comment
$query->execute();
$result = $query->get_result();

$reply_id = [];
$reply_username = [];
$reply_text = [];
$reply_date_time = [];
$reply_like_count = [];
$reply_dislike_count = [];

while ($row = $result->fetch_assoc()) {
    $reply_id[] = $row['Thread_Comment_Reply_ID'];
    $reply_username[] = $row['Username'];
    $reply_text[] = $row['Thread_Comment_Reply_Text'];
    $reply_date_time[] = $row['Thread_Comment_Reply_Date_Time'];
    $reply_like_count[] = $row['Thread_Comment_Reply_Like_Count'];
    $reply_dislike_count[] = $row['Thread_Comment_Reply_Dislike_Count'];
}

// Output the replies as HTML inside the replies-container
foreach ($reply_id as $index => $id) {
    echo '<div class="reply">';
    echo '<div class="reply-username"><p>' . htmlspecialchars($reply_username[$index]) . '</p></div>';
    echo '<div class="reply-text"><p>' . htmlspecialchars($reply_text[$index]) . '</p></div>';
    echo '<div class="reply-date-time"><p>' . htmlspecialchars($reply_date_time[$index]) . '</p></div>';
    echo '<div class="reply-actions">';
    echo '<a href="like.php?reply_id=' . $id . '" class="action-button like"><i class="bi bi-hand-thumbs-up"></i>' . $reply_like_count[$index] . '</a>';
    echo '<a href="dislike.php?reply_id=' . $id . '" class="action-button dislike"><i class="bi bi-hand-thumbs-down"></i>' . $reply_dislike_count[$index] . '</a>';
    echo '</div>';
    echo '</div>';
}

$query->close();
$conn->close();

?>
