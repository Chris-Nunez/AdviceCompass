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
        INNER JOIN Users ON UserFollowers.Following_ID = Users.User_ID
        WHERE UserFollowers.Follower_ID = ? 
        AND Users.Username LIKE ?
        ORDER BY Users.Username ASC";

$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("is", $user_id, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Start the main grid container
echo '<div class="row" id="following-container">';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <div class="following-container">
                    <div class="following-image">';
                        if (!empty($row["Profile_Image"])) {
                            echo '<img src="' . htmlspecialchars($row["Profile_Image"]) . '" alt="Profile Picture" class="profile-pic">';
                        }
        echo       '</div>
                    <div class="following-username">' . htmlspecialchars($row["Username"]) . '</div>
                    <a href="view-profile.php?user_id=' . urlencode($row["User_ID"]) . '">
                        <button class="following-button">Go <i class="bi bi-arrow-right"></i></button>
                    </a>
                </div>
              </div>';
    }

    echo '</div>'; // Close the grid row

} else {
    echo '</div>'; // Close the grid row early for layout consistency

    // Output centered message
    echo '<div class="no-following-container">
                <div class="no-following-text">
                    <p>No users found.</p>
                </div> 
            </div>';
}

$stmt->close();
$conn->close();
?>
