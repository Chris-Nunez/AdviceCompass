<?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION['User_ID'])) {
        echo '';
        exit;
    }

    $user_id = $_SESSION['User_ID'];
    $search = isset($_GET['query']) ? trim($_GET['query']) : '';

    $stmt = $conn->prepare("SELECT 
                                IndustryThreadCategories.Industry_Thread_Category_ID,
                                IndustryThreadCategories.Industry_Thread_Category_Name,
                                IndustryThreadCategories.Industry_Thread_Category_Description,
                                COUNT(Threads.Thread_ID) AS Thread_Count,
                                YEAR(IndustryThreadCategories.Industry_Thread_Category_Year) AS Industry_Thread_Category_Year,
                                Users.User_ID,
                                Users.Username
                            FROM UserPreferredCategories
                            INNER JOIN IndustryThreadCategories ON UserPreferredCategories.Industry_Thread_Category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                            INNER JOIN Users ON IndustryThreadCategories.User_ID = Users.User_ID
                            LEFT JOIN Threads ON IndustryThreadCategories.Industry_Thread_Category_ID = Threads.Industry_Thread_Category_ID
                            WHERE UserPreferredCategories.User_ID = ? AND IndustryThreadCategories.Industry_Thread_Category_Name LIKE ?
                            GROUP BY 
                                IndustryThreadCategories.Industry_Thread_Category_ID,
                                IndustryThreadCategories.Industry_Thread_Category_Name,
                                IndustryThreadCategories.Industry_Thread_Category_Description,
                                IndustryThreadCategories.Industry_Thread_Category_Year,
                                Users.User_ID,
                                Users.Username
                            ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC;");
    
    $searchParam = "%$search%";
    $stmt->bind_param("is", $user_id, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="row mt-5" id="categories-container">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2 category-item">
                    <div class="explore-categories-container">
                        <div class="category-name">
                            <h5>' . htmlspecialchars($row['Industry_Thread_Category_Name']) . '</h5>
                        </div>
                        <div class="category-username">
                            <p>Made by: 
                                <a href="view-profile.php?user_id=' . urlencode($row['User_ID']) . '">
                                    ' . htmlspecialchars($row['Username']) . '
                                </a>
                            </p>
                        </div>
                        <div class="category-thread-count">
                            <p>' . htmlspecialchars($row['Thread_Count']) . ' Total Threads</p>
                        </div>
                        <div class="category-year-created">
                            <p>Created ' . htmlspecialchars($row['Industry_Thread_Category_Year']) . '</p>
                        </div>
                        <a href="thread-category.php?category_id=' . urlencode($row['Industry_Thread_Category_ID']) . '">
                            <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                        </a>
                    </div>
                  </div>';
        }
    } else {
        echo '<div class="no-categories-container text-center mt-5">
                <div class="no-categories-text">
                    <p>No favorite categories found.</p>
                </div>
              </div>';
    }
    
    $stmt->close();
    $conn->close();
?>
