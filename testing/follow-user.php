<?php
session_start();
include 'config.php';

if (!isset($_SESSION['User_ID'])) {
    echo "error: session missing";
    exit();
}

if (!isset($_POST['following_id'])) {
    echo "error: following_id missing";
    exit();
}

$follower_id = intval($_SESSION['User_ID']);
$following_id = intval($_POST['following_id']);

if ($follower_id === $following_id) {
    echo "error: cannot follow yourself";
    exit(); 
}

// Check if already following
$check_follow = $conn->prepare("SELECT 1 FROM UserFollowers WHERE Follower_ID = ? AND Following_ID = ?");
$check_follow->bind_param("ii", $follower_id, $following_id);
$check_follow->execute();
$result = $check_follow->get_result();

if ($result->num_rows > 0) {
    // Unfollow the user
    $unfollow = $conn->prepare("DELETE FROM UserFollowers WHERE Follower_ID = ? AND Following_ID = ?");
    $unfollow->bind_param("ii", $follower_id, $following_id);
    if ($unfollow->execute()) {
        echo "unfollowed";
    } else {
        echo "error: unfollow failed";
    }
} else {
    // Follow the user
    $follow = $conn->prepare("INSERT INTO UserFollowers (Follower_ID, Following_ID) VALUES (?, ?)");
    $follow->bind_param("ii", $follower_id, $following_id);
    if ($follow->execute()) {
        echo "followed";
    } else {
        echo "error: follow failed";
    }
}

$conn->close();
?>
