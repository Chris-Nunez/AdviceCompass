<?php
include 'config.php'; 

session_start();
if (!isset($_SESSION['User_ID'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['User_ID'];
$thread_id = $_POST['thread_id'];
$is_like = $_POST['is_like']; // 1 = like, 0 = dislike

// Check if the user already liked/disliked the thread
$query = $conn->prepare("SELECT * FROM ThreadLikes WHERE User_ID = ? AND Thread_ID = ?");
$query->bind_param("ii", $user_id, $thread_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['Is_Like'] == $is_like) {
        // If the same action, remove it (toggle)
        $query = $conn->prepare("DELETE FROM ThreadLikes WHERE User_ID = ? AND Thread_ID = ?");
        $query->bind_param("ii", $user_id, $thread_id);
        $query->execute();
    } else {
        // Otherwise, update the like/dislike
        $query = $conn->prepare("UPDATE ThreadLikes SET Is_Like = ? WHERE User_ID = ? AND Thread_ID = ?");
        $query->bind_param("iii", $is_like, $user_id, $thread_id);
        $query->execute();
    }
} else {
    // Insert new like/dislike
    $query = $conn->prepare("INSERT INTO ThreadLikes (User_ID, Thread_ID, Is_Like) VALUES (?, ?, ?)");
    $query->bind_param("iii", $user_id, $thread_id, $is_like);
    $query->execute();
}

// Get updated like/dislike counts
$stmt = $conn->prepare("SELECT SUM(CASE WHEN Is_Like = 1 THEN 1 ELSE 0 END) AS likes,
                        SUM(CASE WHEN Is_Like = 0 THEN 1 ELSE 0 END) AS dislikes
                        FROM ThreadLikes
                        WHERE Thread_ID = ?");
$query->bind_param("i", $thread_id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

// Get the updated like and dislike counts
$likes = $row['likes'] ?? 0;
$dislikes = $row['dislikes'] ?? 0;

// Update the Threads table with the new like and dislike counts
$query = $conn->prepare("UPDATE Threads 
                        SET Thread_Like_Count = ?, Thread_Dislike_Count = ? 
                        WHERE Thread_ID = ?");
$query->bind_param("iii", $likes, $dislikes, $thread_id);
$query->execute();

// Return the updated like/dislike counts in the response
echo json_encode([
    "success" => true,
    "likes" => $likes,
    "dislikes" => $dislikes
]);
$query->close();
$conn->close();
exit();
?>

