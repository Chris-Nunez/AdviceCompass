<?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION['User_ID'])) {
        echo '';
        exit;
    }

    $user_id = $_SESSION['User_ID'];
    $search = isset($_GET['query']) ? trim($_GET['query']) : '';

    $stmt = $conn->prepare("
        SELECT itc.Industry_Thread_Category_ID, 
               itc.Industry_Thread_Category_Name, 
               itc.Industry_Thread_Category_Description, 
               itc.Industry_Thread_Category_Thread_Count, 
               itc.Industry_Thread_Category_Year, 
               u.User_ID, 
               u.Username
        FROM UserPreferredCategories upc
        INNER JOIN IndustryThreadCategories itc ON upc.Industry_Thread_Category_ID = itc.Industry_Thread_Category_ID
        INNER JOIN Users u ON itc.User_ID = u.User_ID
        WHERE upc.User_ID = ? AND itc.Industry_Thread_Category_Name LIKE ?
    ");
    
    $searchParam = "%$search%";
    $stmt->bind_param("is", $user_id, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-2 category-item">
        <div class="explore-categories-container">
            <div class="category-name">
                <h5><?php echo htmlspecialchars($row['Industry_Thread_Category_Name']); ?></h5>
            </div>

            <div class="category-username">
                <p>Made by:
                    <a href="view-profile.php?user_id=<?php echo urlencode($row['User_ID']); ?>">
                        <?php echo htmlspecialchars($row['Username']); ?>
                    </a>
                </p>
            </div>

            <div class="category-thread-count">
                <p><?php echo htmlspecialchars($row['Industry_Thread_Category_Thread_Count']); ?> Total Threads</p>
            </div>

            <div class="category-year-created">
                <p>Created <?php echo htmlspecialchars($row['Industry_Thread_Category_Year']); ?></p>
            </div>

            <a href="thread-category.php?category_id=<?php echo urlencode($row['Industry_Thread_Category_ID']); ?>">
                <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
            </a>
        </div>
    </div>
<?php
    }

    $stmt->close();
    $conn->close();
?>
