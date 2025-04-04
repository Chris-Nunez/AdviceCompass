<?php
    session_start();
    include 'config.php';

    if (!isset($_GET['user_id'])) {
        die("User ID not provided.");
    }
    
    $user_id = intval($_GET['user_id']); 
    
    $query = $conn->prepare("SELECT Threads.Thread_ID, Threads.Thread_Title, Threads.Thread_Text, Threads.Thread_Date_Time, 
                                    Users.Username, IndustryThreadCategories.Industry_Thread_Category_Name
                            FROM Threads 
                            JOIN Users ON Threads.User_ID = Users.User_ID
                            JOIN IndustryThreadCategories ON Threads.Industry_Thread_Category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                            WHERE Threads.User_ID = ?
                            ORDER BY Threads.Thread_Date_Time DESC");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    
    $threads = [];
    $threads_usernames = [];
    $thread_category = [];
    $thread_text = [];
    $thread_year = [];
    $thread_id = [];

    while ($row = $result->fetch_assoc()) {
        $threads[] = $row['Thread_Title'];
        $threads_usernames[] = $row['Username'];
        $thread_category[] = $row['Industry_Thread_Category_Name'];
        $thread_text[] = $row['Thread_Text'];
        $thread_year[] = $row['Thread_Date_Time'];
        $thread_id[] = $row['Thread_ID'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Threads</title>
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
                        <a href="home.php">
                            <button class="explore-categories-back-button mx-2"><i class="bi bi-arrow-left"></i> Back</button>
                        </a>
                    </div>

                </div>

                <!-- Center Section: Title -->
                <div class="explore-categories-title">
                    <h1>User Threads</h1>
                </div>

                <div class="row mt-5">
                    <?php
                    // Loop through the categories and create Bootstrap columns
                    for ($i = 0; $i < count($threads); $i++) {
                    ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="thread-container">
                                <div class="thread-title">
                                    <h5><?php echo htmlspecialchars($threads[$i]); ?></h5>
                                </div>

                                <div class="thread-username">
                                    <p>Made by: 
                                        <a href="view-profile.php?user_id=<?php echo urlencode($threads_user_ids[$i]); ?>">
                                            <?php echo htmlspecialchars($threads_usernames[$i]); ?>
                                        </a>
                                    </p>
                                </div>

                                <div class="thread-category">
                                    <p> Category: <?php echo htmlspecialchars($thread_category[$i]); ?> </p>
                                </div>

                                <div class="thread-text">
                                    <p><?php echo htmlspecialchars($thread_text[$i]); ?></p>
                                </div>

                                <div class="thread-year-created">
                                    <p>Created <?php echo htmlspecialchars($thread_year[$i]); ?></p>
                                </div>

                                <a href="thread.php?thread_id=<?php echo urlencode($thread_id[$i]); ?>">
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>