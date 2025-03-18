<?php
    include 'config.php';
    include 'home-data.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <nav class="navbar navbar-expand-md fixed-top" style="background-color: #303030;">
            <div class="container">
                <a href="#home" class="navbar-brand text-white">
                    <h1 class="text-white mb-0">AdviceCompass</h1>
                </a>
        
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav-collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="nav-collapse">
                    <div class="navbar-nav ms-auto">
                        <?php if (!isset($_SESSION['User_ID']) || !isset($_SESSION['Username'])): ?>
                            <a href="index.html">
                                <button class="navbar-login-button me-4">Login</button>
                            </a>
                            <a href="register.html">
                                <button class="navbar-signup-button">Sign Up</button>
                            </a>
                        <?php else: ?>
                            <i class="bi bi-person-fill me-2" id="user-icon"></i>
                            <span class="text-white me-4" id="navbar-username"><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
                            <a href="settings.html">
                                <i class="bi bi-gear me-4" id="gear-icon"></i>
                            </a>
                            <a href="logout.php">   
                                <button class="navbar-logout-button">Logout</button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <section id="main">
            <div class="main-container">
                <div id="home-title">
                    <h1>Home</h1>
                </div>
                <div class="row mt-5">
                    <div class="home-preferred-categories-container col col-7 col-lg-3">
                        
                    </div>

                    <div class="home-explore-categories-container col col-7 col-lg-3">
                        <div class="explore-categories-container-title">
                            <h2>Explore Thread Categories</h2>
                        </div>
                        <?php if (isset($_SESSION['User_ID']) || isset($_SESSION['Username'])): ?>

                            <?php for ($i = 0; $i < count($explore_categories); $i++): ?>
                                <div class="home-category-box">
                                    <div class="category-name">
                                        <h4><?php echo htmlspecialchars($explore_categories[$i]); ?></h4>
                                    </div>
                                    <div class="category-username">
                                        Made by <?php echo htmlspecialchars($explore_usernames[$i]); ?>
                                    </div>
                                </div>
                            <?php endfor; ?>

                            <a href="explore-thread-categories.php">
                                <button class="home-explore-thread-categories-button">Go -></button>
                            </a>

                        <?php else: ?>

                        <?php endif; ?>
                    </div>

                    <div class="home-following-threads-container col col-7 col-lg-3">

                    </div>
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