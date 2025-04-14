<?php
session_start();
include 'config.php';

$query = $conn->prepare("SELECT IndustryThreadCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, COUNT(Threads.Thread_ID) AS Thread_Count, YEAR(Industry_Thread_Category_Year) AS Industry_Thread_Category_Year FROM IndustryThreadCategories 
                        INNER JOIN Users ON IndustryThreadCategories.User_ID = Users.User_ID
                        LEFT JOIN Threads ON IndustryThreadCategories.Industry_Thread_Category_ID = Threads.Industry_Thread_Category_ID
                        GROUP BY IndustryThreadCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, IndustryThreadCategories.Industry_Thread_Category_Year
                        ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC LIMIT 4");
$query->execute();
$result = $query->get_result();

$explore_categories = [];
$explore_usernames = [];
$explore_thread_count = [];
$explore_category_year = [];
$explore_category_id = [];
while ($row = $result->fetch_assoc()) {
    $explore_category_id[] = $row['Industry_Thread_Category_ID'];
    $explore_categories[] = $row['Industry_Thread_Category_Name'];
    $explore_usernames[] = $row['Username'];
    $explore_user_id[] = $row['User_ID'];
    $explore_thread_count[] = $row['Thread_Count'];
    $explore_category_year[] = $row['Industry_Thread_Category_Year'];
}

$query = $conn->prepare("SELECT UserPreferredCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, COUNT(Threads.Thread_ID) AS Thread_Count, YEAR(IndustryThreadCategories.Industry_Thread_Category_Year) AS Industry_Thread_Category_Year FROM UserPreferredCategories
                        INNER JOIN IndustryThreadCategories ON UserPreferredCategories.Industry_Thread_category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                        INNER JOIN Users ON UserPreferredCategories.User_ID = Users.User_ID
                        LEFT JOIN Threads ON IndustryThreadCategories.Industry_Thread_Category_ID = Threads.Industry_Thread_Category_ID
                        WHERE UserPreferredCategories.User_ID = ?
                        GROUP BY UserPreferredCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username, Users.User_ID, IndustryThreadCategories.Industry_Thread_Category_Year
                        ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC LIMIT 4");
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
    $favorite_categories_thread_count[] = $row['Thread_Count'];
    $favorite_categories_year[] = $row['Industry_Thread_Category_Year'];
}


$query = $conn->prepare("SELECT Threads.Thread_ID, Threads.Thread_Title, Threads.Thread_Text, DATE(Threads.Thread_Date_Time) AS Thread_Date, Users.User_ID, Users.Username, IndustryThreadCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name
                        FROM FavoriteThreads 
                        JOIN Threads ON FavoriteThreads.Thread_ID = Threads.Thread_ID
                        JOIN Users ON Threads.User_ID = Users.User_ID
                        JOIN IndustryThreadCategories ON Threads.Industry_Thread_Category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                        WHERE FavoriteThreads.User_ID = ?
                        ORDER BY Threads.Thread_Date_Time DESC LIMIT 4");
$query->bind_param("i", $_SESSION['User_ID']);
$query->execute();
$result = $query->get_result();

$favorite_threads = [];
$favorite_threads_user_ids = [];
$favorite_threads_usernames = [];
$favorite_thread_category = [];
$favorite_thread_text = [];
$favorite_thread_date = [];
$favorite_categories_id = [];
while ($row = $result->fetch_assoc()) {
    $favorite_threads[] = $row['Thread_Title'];
    $favorite_threads_user_ids[] = $row['User_ID'];
    $favorite_threads_usernames[] = $row['Username'];
    $favorite_thread_category[] = $row['Industry_Thread_Category_Name'];
    $favorite_thread_text[] = $row['Thread_Text'];
    $favorite_thread_date[] = $row['Thread_Date'];
    $favorite_thread_id[] = $row['Thread_ID'];
}


$query = $conn->prepare("SELECT Threads.Thread_ID, 
                            Threads.Thread_Title, 
                            Threads.Thread_Text, 
                            DATE(Threads.Thread_Date_Time) AS Thread_Date, 
                            Users.User_ID, 
                            Users.Username, 
                            IndustryThreadCategories.Industry_Thread_Category_ID, 
                            IndustryThreadCategories.Industry_Thread_Category_Name
                        FROM Threads
                        JOIN Users ON Threads.User_ID = Users.User_ID
                        JOIN IndustryThreadCategories ON Threads.Industry_Thread_Category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                        JOIN UserFollowers ON UserFollowers.Following_ID = Threads.User_ID
                        WHERE UserFollowers.Follower_ID = ?
                        ORDER BY Threads.Thread_Date_Time DESC
                        LIMIT 4");
$query->bind_param("i", $_SESSION['User_ID']);
$query->execute();
$result = $query->get_result();

$following_threads = [];
$following_threads_user_ids = [];
$following_threads_usernames = [];
$following_thread_category = [];
$following_thread_text = [];
$following_thread_date = [];
$following_categories_id = [];
while ($row = $result->fetch_assoc()) {
    $following_threads[] = $row['Thread_Title'];
    $following_threads_user_ids[] = $row['User_ID'];
    $following_threads_usernames[] = $row['Username'];
    $following_thread_category[] = $row['Industry_Thread_Category_Name'];
    $following_thread_text[] = $row['Thread_Text'];
    $following_thread_date[] = $row['Thread_Date'];
    $following_thread_id[] = $row['Thread_ID'];
}

$query->close();
$conn->close();

?>