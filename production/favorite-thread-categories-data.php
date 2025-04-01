<?php
include 'config.php';

$query = $conn->prepare("SELECT UserPreferredCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, IndustryThreadCategories.Industry_Thread_Category_Thread_Count, YEAR(IndustryThreadCategories.Industry_Thread_Category_Year) AS Industry_Thread_Category_Year FROM UserPreferredCategories
                        INNER JOIN IndustryThreadCategories ON UserPreferredCategories.Industry_Thread_category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                        INNER JOIN Users ON UserPreferredCategories.User_ID = Users.User_ID
                        WHERE UserPreferredCategories.User_ID = ?
                        ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC");
$query->bind_param("i", $_SESSION['User_ID']);
$query->execute();
$result = $query->get_result();

$favorite_categories_id = [];
$favorite_categories = [];
$favorite_categories_username = [];
$favorite_categories_user_ids = [];
$favorite_categories_thread_count = [];
$favorite_catgories_year = [];
while ($row = $result->fetch_assoc()) {
    $favorite_categories_id[] = $row['Industry_Thread_Category_ID'];
    $favorite_categories[] = $row['Industry_Thread_Category_Name'];
    $favorite_categories_usernames[] = $row['Username'];
    $favorite_categories_user_ids[] = $row['User_ID'];
    $favorite_categories_thread_count[] = $row['Industry_Thread_Category_Thread_Count'];
    $favorite_categories_year[] = $row['Industry_Thread_Category_Year'];
}

// Close the connection
$query->close();
$conn->close();

?>