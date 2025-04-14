<?php
session_start();
include 'config.php';

$search = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!isset($_SESSION['User_ID'])) {
    die("Unauthorized access.");
}

$user_id = intval($_SESSION['User_ID']);

$sql = "SELECT 
            Users.User_ID, 
            Users.Username, 
            Users.Profile_Image
        FROM UserFollowers
        INNER JOIN Users ON UserFollowers.Follower_ID = Users.User_ID
        WHERE UserFollowers.Following_ID = ? 
        AND Users.Username LIKE ?
        ORDER BY Users.Username ASC";

$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("is", $user_id, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Start container
echo '<div class="row" id="followers-container">';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <div class="follower-container">
                    <div class="follower-image">';
                        if (!empty($row["Profile_Image"])) {
                            echo '<img src="' . htmlspecialchars($row["Profile_Image"]) . '" alt="Profile Picture" class="profile-pic">';
                        }
        echo       '</div>
                    <div class="follower-username">' . htmlspecialchars($row["Username"]) . '</div>
                    <a href="view-profile.php?user_id=' . urlencode($row["User_ID"]) . '">
                        <button class="follower-button">Go <i class="bi bi-arrow-right"></i></button>
                    </a>
                </div>
              </div>';
    }

    // Close row container
    echo '</div>';

} else {
    // Close row first
    echo '</div>';

    // Show centered message without grid
    echo '<div class="no-followers-container">
                <div class="no-followers-text">
                    <p>No followers found.</p>
                </div> 
            </div>';
}

$stmt->close();
$conn->close();
?>
