<?php
session_start();
include 'config.php';

if (!isset($_GET['user_id'])) {
    die("User ID not provided.");
}

$user_id = intval($_GET['user_id']);

// Query to fetch all users that the logged-in user is following
$query = $conn->prepare("SELECT Users.User_ID, Users.Username, Users.Profile_Image 
                        FROM UserFollowers 
                        INNER JOIN Users ON UserFollowers.Following_ID = Users.User_ID 
                        WHERE UserFollowers.Follower_ID = ?
                        ORDER BY Users.First_Name ASC");

$query->bind_param("i", $user_id); 
$query->execute();
$result = $query->get_result();

$following_user_id = [];
$following_usernames = [];
$following_profile_image = [];

while ($row = $result->fetch_assoc()) {
    $following_user_id[] = $row['User_ID'];
    $following_usernames[] = $row['Username'];
    $following_profile_image[] = $row['Profile_Image'];
}

$query->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Following</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <nav class="navbar navbar-expand-md fixed-top" style="background-color: #303030;">
            <div class="container">
                <a href="home.php" class="navbar-brand text-white">
                    <h1 class="text-white mb-0">AdviceCompass</h1>
                </a>
        
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav-collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="nav-collapse">
                    <div class="navbar-nav ms-auto">
                        <a href="view-profile.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                            <i class="bi bi-person-fill me-2" id="user-icon"></i>
                        </a>
                        <span class="text-white me-4" id="navbar-username"><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
                        <a href="settings.php">
                            <i class="bi bi-gear me-4" id="gear-icon"></i>
                        </a>
                        <a href="logout.php">   
                            <button class="navbar-logout-button">Logout</button>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <section id="following">
            <div class="main-container">
                <h2 id="following-title">Following</h2>
                <div class="row mt-5">
                    <?php if (count($following_user_id) > 0) { ?>
                        <?php for ($i = 0; $i < count($following_user_id); $i++) { ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="following-container">
                                    <div class="following-image">
                                        <?php if (!empty($following_profile_image[$i])) : ?>
                                            <img src="<?php echo htmlspecialchars($following_profile_image[$i]); ?>" alt="Profile Picture" class="profile-pic">
                                        <?php endif; ?>
                                    </div>
                                    <div class="following-username">
                                        <?php echo htmlspecialchars($following_usernames[$i]); ?>
                                    </div>
                                    <a href="view-profile.php?user_id=<?php echo urlencode($following_user_id[$i]); ?>">
                                        <button class="following-button">Go <i class="bi bi-arrow-right"></i></button>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="no-following-container">
                                <div class="no-following-text">
                                    <p>You are not following anyone.</p>
                                </div> 
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>


        <section id="footer-section">
            <div class="footer-container">
              <div class="footer-text">
                <p>&copy; AdviceCompass 2025</p>
              </div>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>