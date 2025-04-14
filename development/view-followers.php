<?php
session_start();
include 'config.php';

if (!isset($_GET['user_id'])) {
    die("User ID not provided.");
}

$user_id = intval($_GET['user_id']);

// Query to fetch all followers of the logged-in user
$query = $conn->prepare("SELECT Users.User_ID, Users.Username, Users.Profile_Image 
                        FROM UserFollowers 
                        INNER JOIN Users ON UserFollowers.Follower_ID = Users.User_ID 
                        WHERE UserFollowers.Following_ID = ?
                        ORDER BY Users.First_Name ASC");

$query->bind_param("i", $user_id); 
$query->execute();
$result = $query->get_result();

$followers_user_id = [];
$followers_usernames = [];
$followers_profile_image = [];

while ($row = $result->fetch_assoc()) {
    $followers_user_id[] = $row['User_ID'];
    $followers_usernames[] = $row['Username'];
    $followers_profile_image[] = $row['Profile_Image'];
}

$query->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Followers</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        <section id="followers">
            <div class="main-container px-5">
                <div class="top-container d-flex align-items-center justify-content-between">
                
                    <!-- Left Section: Back & Create Category Buttons -->
                    <div class="d-flex align-items-center flex-1">
                        <button class="explore-categories-back-button mx-2" onclick="history.back();">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                    </div>

                    <!-- Right Section: Search Bar -->
                    <div class="search-container flex-1 d-flex justify-content-end">
                        <input type="text" class="form-control mx-2" placeholder="Search followers..." id="followers-search" style="width: 250px;">
                    </div>

                </div>
                <h2 id="followers-title">Followers</h2>
                <div class="row mt-5" id="followers-container">
                    <?php if (count($followers_user_id) > 0) { ?>
                        <?php for ($i = 0; $i < count($followers_user_id); $i++) { ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                <div class="follower-container">
                                    <div class="follower-image">
                                        <?php if (!empty($followers_profile_image[$i])) : ?>
                                            <img src="<?php echo htmlspecialchars($followers_profile_image[$i]); ?>" alt="Profile Picture" class="profile-pic">
                                        <?php endif; ?>
                                    </div>
                                    <div class="follower-username">
                                        <?php echo htmlspecialchars($followers_usernames[$i]); ?>
                                    </div>
                                    <a href="view-profile.php?user_id=<?php echo urlencode($followers_user_id[$i]); ?>">
                                        <button class="follower-button">Go <i class="bi bi-arrow-right"></i></button>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                </div>
                        
                        <div class="no-followers-container">
                            <div class="no-followers-text">
                                <p>No followers found.</p>
                            </div> 
                        </div>
                        
                    <?php } ?>
            </div>
        </section>


        <section id="footer-section">
            <div class="footer-container">
              <div class="footer-text">
                <p>&copy; AdviceCompass 2025</p>
              </div>
            </div>
        </section>

        <script>
            document.getElementById('followers-search').addEventListener('input', function () {
                let searchQuery = this.value.trim();

                let xhr = new XMLHttpRequest();
                xhr.open('GET', 'search-followers.php?query=' + encodeURIComponent(searchQuery), true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('followers-container').innerHTML = xhr.responseText;
                    }
                };

                xhr.send();
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
