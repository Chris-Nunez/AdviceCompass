<?php
    session_start();
    include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Settings</title>
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

        <section id="settings">
            <div class="settings-container px-5">
                <div class="top-container d-flex align-items-center justify-content-between">
            
                    <!-- Left Section: Back & Create Category Buttons -->
                    <div class="d-flex align-items-center flex-1">
                        <button class="explore-categories-back-button mx-2" onclick="history.back();">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                    </div>
                </div>
                <h2 id="settings-title">Settings</h2>
                <div class="settings-box">
                    <a href="view-profile.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                        <button class="settings-button" id="view-profile">
                            View Profile <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
                    <a href="view-followers.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                        <button class="settings-button" id="view-followers">
                            View Followers <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
                    <a href="view-following.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                        <button class="settings-button" id="view-following">
                            View Following <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
                    <a href="search-users.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                        <button class="settings-button" id="search-users">
                            Search Users <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
                    <a href="favorite-thread-categories.php">
                        <button class="settings-button" id="view-favorite-categories">
                            View Favorite Categories <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
                    <a href="favorite-threads.php">
                        <button class="settings-button" id="view-favorite-threads">
                            View Favorite Threads <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
                    <a href="logout.php">
                        <button class="settings-button" id="logout">
                            Logout <i class="bi bi-arrow-right"></i>
                        </button>
                    </a>
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


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>