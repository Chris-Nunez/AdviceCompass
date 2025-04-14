<?php
session_start();
include 'config.php';

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

$query = $conn->prepare("SELECT User_ID, Username, Profile_Image 
                         FROM Users 
                         WHERE Username LIKE CONCAT('%', ?, '%') 
                         ORDER BY Username ASC");
$query->bind_param("s", $searchQuery);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2">
                <div class="follower-container">
                    <div class="follower-image">';
                        if (!empty($row["Profile_Image"])) {
                            echo '<img src="' . htmlspecialchars($row["Profile_Image"]) . '" alt="Profile Picture" class="profile-pic">';
                        }
        echo        '</div>
                    <div class="follower-username">' . htmlspecialchars($row["Username"]) . '</div>
                    <a href="view-profile.php?user_id=' . urlencode($row["User_ID"]) . '">
                        <button class="follower-button">Go <i class="bi bi-arrow-right"></i></button>
                    </a>
                </div>
              </div>';
    }
} else {

    echo '<div class="no-followers-container text-center mt-5">
            <div class="no-followers-text">
                <p>No users found.</p>
            </div>
          </div>';
}

$query->close();
$conn->close();
?>
