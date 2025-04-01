<?php
include 'config.php'; 

session_start();
if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['User_ID'];
$reply_id = $_POST['reply_id'];
$is_like = $_POST['is_like']; // 1 = like, 0 = dislike

// Check if the user already liked/disliked the reply
$query = $conn->prepare("SELECT * FROM ThreadCommentReplyLikes WHERE User_ID = ? AND Thread_Comment_Reply_ID = ?");
$query->bind_param("ii", $user_id, $reply_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['Is_Like'] == $is_like) {
        // If the same action, remove it (toggle)
        $query = $conn->prepare("DELETE FROM ThreadCommentReplyLikes WHERE User_ID = ? AND Thread_Comment_Reply_ID = ?");
        $query->bind_param("ii", $user_id, $reply_id);
        $query->execute();
    } else {
        // Otherwise, update the like/dislike
        $query = $conn->prepare("UPDATE ThreadCommentReplyLikes SET Is_Like = ? WHERE User_ID = ? AND Thread_Comment_Reply_ID = ?");
        $query->bind_param("iii", $is_like, $user_id, $reply_id);
        $query->execute();
    }
} else {
    // Insert new like/dislike
    $query = $conn->prepare("INSERT INTO ThreadCommentReplyLikes (User_ID, Thread_Comment_Reply_ID, Is_Like) VALUES (?, ?, ?)");
    $query->bind_param("iii", $user_id, $reply_id, $is_like);
    $query->execute();
}

// Get updated like/dislike counts
$query = $conn->prepare("SELECT 
                        SUM(CASE WHEN Is_Like = 1 THEN 1 ELSE 0 END) AS likes,
                        SUM(CASE WHEN Is_Like = 0 THEN 1 ELSE 0 END) AS dislikes
                        FROM ThreadCommentReplyLikes
                        WHERE Thread_Comment_Reply_ID = ?");
$query->bind_param("i", $reply_id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

$likes = $row['likes'] ?? 0;
$dislikes = $row['dislikes'] ?? 0;

// Return the updated like/dislike counts for the reply
echo json_encode([
    "success" => true,
    "likes" => $likes,
    "dislikes" => $dislikes
]);
$query->close();
$conn->close();
exit();

?>
