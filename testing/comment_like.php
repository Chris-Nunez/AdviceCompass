<?php
include 'config.php';

session_start();
if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['User_ID'];
$comment_id = $_GET['comment_id'];
$is_like = $_GET['is_like']; // 1 = like, 0 = dislike

// Check if the user already liked/disliked the comment
$query = $conn->prepare("SELECT * FROM ThreadCommentLikes WHERE User_ID = ? AND Thread_Comment_ID = ?");
$query->bind_param("ii", $user_id, $comment_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // If the same action (like/dislike), remove it (toggle)
    $row = $result->fetch_assoc();
    if ($row['Is_Like'] == $is_like) {
        // Remove the like/dislike
        $query = $conn->prepare("DELETE FROM ThreadCommentLikes WHERE User_ID = ? AND Thread_Comment_ID = ?");
        $query->bind_param("ii", $user_id, $comment_id);
        $query->execute();
    } else {
        // Update the like/dislike
        $query = $conn->prepare("UPDATE ThreadCommentLikes SET Is_Like = ? WHERE User_ID = ? AND Thread_Comment_ID = ?");
        $query->bind_param("iii", $is_like, $user_id, $comment_id);
        $query->execute();
    }
} else {
    // Insert new like/dislike
    $query = $conn->prepare("INSERT INTO ThreadCommentLikes (User_ID, Thread_Comment_ID, Is_Like) VALUES (?, ?, ?)");
    $query->bind_param("iii", $user_id, $comment_id, $is_like);
    $query->execute();
}

// Get updated like/dislike counts
$query = $conn->prepare("SELECT SUM(CASE WHEN Is_Like = 1 THEN 1 ELSE 0 END) AS likes,
                        SUM(CASE WHEN Is_Like = 0 THEN 1 ELSE 0 END) AS dislikes
                        FROM ThreadCommentLikes
                        WHERE Thread_Comment_ID = ?");
$query->bind_param("i", $comment_id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

echo json_encode([
    "success" => true,
    "likes" => $row['likes'] ?? 0,
    "dislikes" => $row['dislikes'] ?? 0
]);

$query->close();
$conn->close();
exit();
?>
