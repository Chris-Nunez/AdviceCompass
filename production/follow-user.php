<?php
session_start();
include 'config.php';

if (!isset($_SESSION['User_ID']) || !isset($_POST['following_id'])) {
    echo "error";
    exit();
}

$follower_id = $_SESSION['User_ID'];
$following_id = intval($_POST['following_id']);

if ($follower_id == $following_id) {
    echo "error";
    exit(); 
}

// Check if already following
$check_follow = $conn->prepare("SELECT * FROM UserFollowers WHERE Follower_ID = ? AND Following_ID = ?");
$check_follow->bind_param("ii", $follower_id, $following_id);
$check_follow->execute();
$result = $check_follow->get_result();

if ($result->num_rows > 0) {
    // Unfollow the user
    $unfollow = $conn->prepare("DELETE FROM UserFollowers WHERE Follower_ID = ? AND Following_ID = ?");
    $unfollow->bind_param("ii", $follower_id, $following_id);
    $unfollow->execute();
    echo "unfollowed";
} else {
    // Follow the user
    $follow = $conn->prepare("INSERT INTO UserFollowers (Follower_ID, Following_ID) VALUES (?, ?)");
    $follow->bind_param("ii", $follower_id, $following_id);
    $follow->execute();
    echo "followed";
}

$query->close();
$conn->close();
?>
