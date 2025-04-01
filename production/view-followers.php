<?php
    session_start();
    include 'config.php';
    include 'view-followers-data';
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
                        <i class="bi bi-person-fill me-2" id="user-icon"></i>
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
            <div class="followers-container">
                <h2 id="followers-title">Followers</h2>

                <!-- Check if there are followers -->
                <?php if (!empty($followers_user_id)): ?>
                    <div class="followers-list">
                        <?php $i = 0; // Initialize the counter variable i ?>
                        <?php foreach ($followers_user_id as $follower_id): ?>
                            <div class="follower-box">
                                <div class="follower-info">
                                    <img src="<?php echo htmlspecialchars($followers_profile_image[$i]); ?>" alt="Profile Image" class="follower-image">
                                    <div class="follower-details">
                                        <p><strong><?php echo htmlspecialchars($followers_first_name[$i]) . " " . htmlspecialchars($followers_last_name[$i]); ?></strong></p>
                                        <p>@<?php echo htmlspecialchars($followers_usernames[$i]); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; // Increment the counter after each iteration ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No followers found.</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="footer-section">
            <div class="footer-container">
              <div class="footer-text">
                <p>&copy; AdviceCompass 2025</p>
              </div>
            </div>
        </section>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>