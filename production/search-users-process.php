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
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
            <div class="follower-container">
                <div class="follower-image">
                    <?php if (!empty($row['Profile_Image'])) : ?>
                        <img src="<?php echo htmlspecialchars($row['Profile_Image']); ?>" class="profile-pic">
                    <?php endif; ?>
                </div>
                <div class="follower-username">
                    <?php echo htmlspecialchars($row['Username']); ?>
                </div>
                <a href="view-profile.php?user_id=<?php echo urlencode($row['User_ID']); ?>">
                    <button class="follower-button">Go <i class="bi bi-arrow-right"></i></button>
                </a>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="col-12">
        <p class="text-muted">No users found.</p>
    </div>
    <?php
}

$query->close();
$conn->close();
?>
