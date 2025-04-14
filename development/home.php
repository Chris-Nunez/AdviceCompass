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
                <a href="home.php" class="navbar-brand text-white">
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
                            <a href="register.php">
                                <button class="navbar-signup-button">Sign Up</button>
                            </a>
                        <?php else: ?>
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
                    <!-- LEFT: Explore Categories -->
                    <div class="col-12 col-lg-6">
                        <div class="home-container">
                            
                            <div class="home-explore-categories-title">
                                <h2>Explore Page</h2>
                                <h5>View the latest thread categories!</h5>
                            </div>
                            <div class="row mt-5 categories-container">
                                <?php if (count($explore_categories) > 0) { ?>
                                    <?php for ($i = 0; $i < count($explore_categories); $i++) { ?>
                                        <div class="col-12 col-sm-6 col-md-6 category-item">
                                            <div class="explore-categories-container">
                                                <div class="category-name">
                                                    <h5><?php echo htmlspecialchars($explore_categories[$i]); ?></h5>
                                                </div>
                                                <div class="category-username">
                                                    <p>Made by: 
                                                        <a href="view-profile.php?user_id=<?php echo urlencode($explore_user_id[$i]); ?>">
                                                            <?php echo htmlspecialchars($explore_usernames[$i]); ?>
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="category-thread-count">
                                                    <p><?php echo htmlspecialchars($explore_thread_count[$i]); ?> Total Threads</p>
                                                </div>
                                                <div class="category-year-created">
                                                    <p>Created <?php echo htmlspecialchars($explore_category_year[$i]); ?></p>
                                                </div>
                                                <a href="thread-category.php?category_id=<?php echo urlencode($explore_category_id[$i]); ?>">
                                                    <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                            </div>
                                <?php } else { ?>
                                    <div class="no-threads-container text-center mt-5">
                                        <div class="no-threads-text">
                                            <p>No categories in .</p>
                                        </div>
                                    </div>
                                <?php } ?>

                            <div class="view-explore-page-button">
                                <a href="explore-thread-categories.php" id="explore-categories-button">
                                    <button class="home-thread-categories-button">View Explore Page</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Favorite Categories -->
                    <div class="col-12 col-lg-6">
                        <div class="home-container">
                            <div class="home-favorite-categories-title">
                                <h2>Favorite Thread Categories</h2>
                                <h5>View your favorited categories!</h5>
                            </div>
                            <div class="row mt-5 categories-container">
                                <?php if (count($favorite_categories) > 0) { ?>
                                    <?php for ($i = 0; $i < count($favorite_categories); $i++) { ?>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="explore-categories-container">
                                                <div class="category-name">
                                                    <h5><?php echo htmlspecialchars($favorite_categories[$i]); ?></h5>
                                                </div>
                                                <div class="category-username">
                                                    <p>Made by: 
                                                        <a href="view-profile.php?user_id=<?php echo urlencode($favorite_categories_user_ids[$i]); ?>">
                                                            <?php echo htmlspecialchars($favorite_categories_usernames[$i]); ?>
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="category-thread-count">
                                                    <p><?php echo htmlspecialchars($favorite_categories_thread_count[$i]); ?> Total Threads</p>
                                                </div>
                                                <div class="category-year-created">
                                                    <p>Created <?php echo htmlspecialchars($favorite_categories_year[$i]); ?></p>
                                                </div>
                                                <a href="thread-category.php?category_id=<?php echo urlencode($favorite_categories_id[$i]); ?>">
                                                    <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                            </div>
                                <?php } else { ?>
                                    <div class="no-threads-container text-center mt-5">
                                        <div class="no-threads-text">
                                            <p>No favorite categories.</p>
                                        </div>
                                    </div>
                                <?php } ?>

                            <div class="view-explore-page-button">
                                <a href="favorite-thread-categories.php" id="explore-categories-button">
                                    <button class="home-thread-categories-button">View Favorite Categories</button>
                                </a>
                            </div>
                        </div>
                    </div>
            
                    <!-- BELOW: Favorite Threads -->
                    <div class="col-12 col-lg-6">
                        <div class="home-container">
                            <div class="home-favorite-threads-title">
                                <h2>Favorite Threads</h2>
                                <h5>View your favorite threads!</h5>
                            </div>
                            <div class="row mt-5 threads-container">
                                <?php if (count($favorite_threads) > 0) { ?>
                                    <?php for ($i = 0; $i < count($favorite_threads); $i++) { ?>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="explore-categories-container">
                                                <div class="thread-title">
                                                    <h5><?php echo htmlspecialchars($favorite_threads[$i]); ?></h5>
                                                </div>
                                                <div class="thread-username">
                                                    <p>Made by: 
                                                        <a href="view-profile.php?user_id=<?php echo urlencode($favorite_thread_user_ids[$i]); ?>">
                                                            <?php echo htmlspecialchars($favorite_threads_usernames[$i]); ?>
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="thread-category">
                                                    <p>Category: <?php echo htmlspecialchars($favorite_thread_category[$i]); ?></p>
                                                </div>
                                                <div class="thread-text">
                                                    <p><?php echo htmlspecialchars($favorite_thread_text[$i]); ?></p>
                                                </div>
                                                <div class="thread-year-created">
                                                    <p>Created <?php echo htmlspecialchars($favorite_thread_date[$i]); ?></p>
                                                </div>
                                                <a href="thread.php?thread_id=<?php echo urlencode($favorite_thread_id[$i]); ?>">
                                                    <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                            </div>
                                <?php } else { ?>

                                    <div class="no-threads-container text-center mt-5">
                                        <div class="no-threads-text">
                                            <p>No favorite threads.</p>
                                        </div>
                                    </div>

                                <?php } ?>

                            <div class="view-explore-page-button">
                                <a href="favorite-threads.php" id="explore-categories-button">
                                    <button class="home-thread-categories-button">View Favorite Threads</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- BELOW: Following Threads -->
                    <div class="col-12 col-lg-6">
                        <div class="home-container">
                            <div class="home-favorite-threads-title">
                                <h2>Following Threads</h2>
                                <h5>View the threads of the users you follow!</h5>
                            </div>
                            <div class="row mt-5 threads-container">
                                <?php if (count($following_threads) > 0) { ?>
                                    <?php for ($i = 0; $i < count($following_threads); $i++) { ?>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="explore-categories-container">
                                                <div class="thread-title">
                                                    <h5><?php echo htmlspecialchars($following_threads[$i]); ?></h5>
                                                </div>
                                                <div class="thread-username">
                                                    <p>Made by: 
                                                        <a href="view-profile.php?user_id=<?php echo urlencode($following_thread_user_ids[$i]); ?>">
                                                            <?php echo htmlspecialchars($following_threads_usernames[$i]); ?>
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="thread-category">
                                                    <p>Category: <?php echo htmlspecialchars($following_thread_category[$i]); ?></p>
                                                </div>
                                                <div class="thread-text">
                                                    <p><?php echo htmlspecialchars($following_thread_text[$i]); ?></p>
                                                </div>
                                                <div class="thread-year-created">
                                                    <p>Created <?php echo htmlspecialchars($following_thread_date[$i]); ?></p>
                                                </div>
                                                <a href="thread.php?thread_id=<?php echo urlencode($following_thread_id[$i]); ?>">
                                                    <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                            </div>
                                <?php } else { ?>                            

                                    <div class="no-threads-container text-center mt-5">
                                        <div class="no-threads-text">
                                            <p>No following threads.</p>
                                        </div>
                                    </div>

                                <?php } ?>

                            <div class="view-explore-page-button">
                                <a href="following-threads.php" id="explore-categories-button">
                                    <button class="home-thread-categories-button">View Following Threads</button>
                                </a>
                            </div>
                        </div>
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