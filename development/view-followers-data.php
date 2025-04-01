<?php
include 'config.php';

$user_id = $_SESSION['User_ID'];

// Query to fetch all followers of the logged-in user
$query = $conn->prepare("SELECT Users.User_ID, Users.First_Name, Users.Last_Name, Users.Username, Users.Profile_Image 
                        FROM UserFollowers 
                        INNER JOIN Users ON UserFollowers.Follower_ID = Users.User_ID 
                        WHERE UserFollowers.Following_ID = ?
                        ORDER BY Users.First_Name ASC");

$query->bind_param("i", $user_id); 
$query->execute();
$result = $query->get_result();

$followers_user_id = [];
$followers_first_name = [];
$followers_last_name = [];
$followers_usernames = [];
$followers_profile_image = [];

while ($row = $result->fetch_assoc()) {
    $followers_user_id[] = $row['User_ID'];
    $followers_first_name[] = $row['First_Name'];
    $followers_last_name[] = $row['Last_Name'];
    $followers_usernames[] = $row['Username'];
    $followers_profile_image[] = $row['Profile_Image'];
}

$query->close();
$conn->close();
?>

