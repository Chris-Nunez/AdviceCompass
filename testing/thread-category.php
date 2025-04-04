<?php
    session_start();
    include 'config.php';
    $backUrl = $_SESSION['last_page'] ?? 'explore-thread-categories.php';

    // Check if category_id exists
    if (!isset($_GET['category_id'])) {
        die("No category selected.");
    }

    $category_id = intval($_GET['category_id']);  

    // Fetch category details
    $query = $conn->prepare("SELECT * FROM IndustryThreadCategories WHERE Industry_Thread_Category_ID = ?");
    $query->bind_param("i", $category_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        die("Category not found.");
    }

    $is_favorited = false;

    if (isset($_SESSION['User_ID'])) {
        $logged_in_user_id = $_SESSION['User_ID'];

        $fav_query = $conn->prepare("SELECT * FROM UserPreferredCategories WHERE User_ID = ? AND Industry_Thread_Category_ID = ?");
        $fav_query->bind_param("ii", $logged_in_user_id, $category_id);
        $fav_query->execute();
        $fav_result = $fav_query->get_result();

        if ($fav_result->num_rows > 0) {
            $is_favorited = true;
        }
    }


    // Include thread-category-data.php to fetch threads
    include 'thread-category-data.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($category['Industry_Thread_Category_Name']); ?></title>
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

                <div class="top-container d-flex align-items-center justify-content-between">
            
                    <div class="d-flex align-items-center flex-1">

                        <a href="<?= htmlspecialchars($backUrl) ?>">
                            <button class="thread-category-back-button mx-2"><i class="bi bi-arrow-left"></i> Back</button>
                        </a>

                        <a href="create-thread.php?category_id=<?php echo urlencode($category_id); ?>">
                            <button class="create-thread-button mx-2"><i class="bi bi-plus-lg"></i> Create Thread</button>
                        </a>

                        <button class="favorite-category-button mx-2 <?= $is_favorited ? 'favorited' : '' ?>" id="favorite-category-btn" category-id="<?php echo $category_id; ?>">
                            <i class="bi <?= $is_favorited ? 'bi-star-fill' : 'bi-star' ?>"></i> <?= $is_favorited ? 'Favorited' : 'Favorite' ?>
                        </button>

                    </div>

                    <div class="search-container flex-1 d-flex justify-content-end">
                        <input type="text" class="form-control mx-2" placeholder="Search threads..." id="thread-search" style="width: 250px;">
                    </div>

                </div>

                <div class="explore-categories-title">
                    <h1><?php echo htmlspecialchars($category['Industry_Thread_Category_Name']); ?></h1>
                </div>

                <div class="row mt-5" id="threads-container">
                    <?php
                    // Loop through and display all threads initially
                    for ($i = 0; $i < count($thread_id); $i++) {
                    ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="thread-container">
                                <div class="thread-title">
                                    <h5><?php echo htmlspecialchars($thread_title[$i]); ?></h5>
                                </div>

                                <div class="thread-username">
                                    <p>Made by: 
                                        <a href="view-profile.php?user_id=<?php echo urlencode($thread_user_id[$i]); ?>">
                                            <?php echo htmlspecialchars($thread_username[$i]); ?>
                                        </a>
                                    </p>
                                </div>

                                <div class="thread-text">
                                    <p><?php echo htmlspecialchars($thread_text[$i]); ?></p>
                                </div>

                                <div class="thread-year-created">
                                    <p>Created <?php echo htmlspecialchars($thread_date[$i]); ?></p>
                                </div>

                                <a href="thread.php?thread_id=<?php echo urlencode($thread_id[$i]); ?>">
                                    <button class="thread-button">Go <i class="bi bi-arrow-right"></i></button>
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
            document.getElementById('favorite-category-btn').addEventListener('click', function() {
                let button = this;
                let categoryId = button.getAttribute('category-id');

                fetch('favorite-thread-category-process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'category_id=' + encodeURIComponent(categoryId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (button.classList.contains('favorited')) {
                            button.innerHTML = '<i class="bi bi-star"></i> Favorite';
                            button.classList.remove('favorited');
                        } else {
                            button.innerHTML = '<i class="bi bi-star-fill"></i> Favorited';
                            button.classList.add('favorited');
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            document.getElementById('thread-search').addEventListener('input', function () {
                let searchQuery = this.value.trim();

                let categoryId = <?php echo $category_id; ?>;

                let xhr = new XMLHttpRequest();
                xhr.open('GET', 'search-threads.php?query=' + encodeURIComponent(searchQuery) + '&category_id=' + categoryId, true);

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