<?php
    session_start();
    include 'config.php';
    include 'favorite-threads-data.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Favorite Categories</title>
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

        <section id="main">
            <div class="main-container px-5">

                <div class="d-flex align-items-center justify-content-between">
            
                    <!-- Left Section: Back & Create Category Buttons -->
                    <div class="d-flex align-items-center flex-1">
                        <button class="explore-categories-back-button mx-2" onclick="history.back();">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                    </div>

                    <!-- Right Section: Search Bar -->
                    <div class="search-container flex-1 d-flex justify-content-end">
                        <input type="text" class="form-control mx-2" placeholder="Search threads..." id="thread-search" style="width: 250px;">
                    </div>

                </div>

                <!-- Center Section: Title -->
                <div class="explore-categories-title">
                    <h1>Favorite Threads</h1>
                </div>

                <div class="row mt-5" id="threads-container">
                    <?php
                    // Loop through the categories and create Bootstrap columns
                    for ($i = 0; $i < count($favorite_threads); $i++) {
                    ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="thread-container">
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
                                    <p> Category: <?php echo htmlspecialchars($favorite_thread_category[$i]); ?> </p>
                                </div>

                                <div class="thread-text">
                                    <p><?php echo htmlspecialchars($favorite_thread_text[$i]); ?></p>
                                </div>

                                <div class="thread-year-created">
                                    <p>Created <?php echo htmlspecialchars($favorite_thread_year[$i]); ?></p>
                                </div>

                                <a href="thread.php?thread_id=<?php echo urlencode($favorite_thread_id[$i]); ?>">
                                    <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                                </a>

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

        <script>
            document.getElementById('thread-search').addEventListener('input', function () {
                let searchQuery = this.value.trim();

                let xhr = new XMLHttpRequest();
                xhr.open('GET', 'search-favorite-threads.php?query=' + encodeURIComponent(searchQuery), true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById('threads-container').innerHTML = xhr.responseText;
                    }
                };

                xhr.send();
            });
        </script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>