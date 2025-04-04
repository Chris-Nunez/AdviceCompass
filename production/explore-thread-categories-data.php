<?php
include 'config.php';

$query = $conn->prepare("SELECT IndustryThreadCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, COUNT(Threads.Thread_ID) AS Thread_Count, YEAR(Industry_Thread_Category_Year) AS Industry_Thread_Category_Year FROM IndustryThreadCategories 
                        INNER JOIN Users ON IndustryThreadCategories.User_ID = Users.User_ID
                        LEFT JOIN Threads ON IndustryThreadCategories.Industry_Thread_Category_ID = Threads.Industry_Thread_Category_ID
                        GROUP BY IndustryThreadCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, IndustryThreadCategories.Industry_Thread_Category_Year
                        ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC");
$query->execute();
$result = $query->get_result();

$explore_category_id = [];
$explore_categories = [];
$explore_usernames = [];
$explore_user_id = [];
$explore_thread_count = [];
$explore_category_year = [];
while ($row = $result->fetch_assoc()) {
    $explore_category_id[] = $row['Industry_Thread_Category_ID'];
    $explore_categories[] = $row['Industry_Thread_Category_Name'];
    $explore_usernames[] = $row['Username'];
    $explore_user_id[] = $row['User_ID'];
    $explore_thread_count[] = $row['Thread_Count'];
    $explore_category_year[] = $row['Industry_Thread_Category_Year'];
}


$query->close();
$conn->close();

?>