<?php
include 'config.php';

$search = isset($_GET['query']) ? trim($_GET['query']) : '';

$sql = "SELECT IndustryThreadCategories.Industry_Thread_Category_ID, 
               IndustryThreadCategories.Industry_Thread_Category_Name, 
               Users.Username, Users.User_ID, 
               IndustryThreadCategories.Industry_Thread_Category_Thread_Count, 
               YEAR(Industry_Thread_Category_Year) AS Industry_Thread_Category_Year 
        FROM IndustryThreadCategories 
        INNER JOIN Users ON IndustryThreadCategories.User_ID = Users.User_ID 
        WHERE IndustryThreadCategories.Industry_Thread_Category_Name LIKE ? 
        ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC";

$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-2 category-item">
                <div class="explore-categories-container">
                    <div class="category-name"><h5>' . htmlspecialchars($row["Industry_Thread_Category_Name"]) . '</h5></div>
                    <div class="category-username">
                        <p>Made by: <a href="view-profile.php?user_id=' . urlencode($row["User_ID"]) . '">' . htmlspecialchars($row["Username"]) . '</a></p>
                    </div>
                    <div class="category-thread-count"><p>' . htmlspecialchars($row["Industry_Thread_Category_Thread_Count"]) . ' Total Threads</p></div>
                    <div class="category-year-created"><p>Created ' . htmlspecialchars($row["Industry_Thread_Category_Year"]) . '</p></div>
                    <a href="thread-category.php?category_id=' . urlencode($row["Industry_Thread_Category_ID"]) . '">
                        <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                    </a>
                </div>
              </div>';
    }
} else {
    echo '<p class="text-muted">No categories found.</p>';
}

$stmt->close();
$conn->close();
?>
