<?php
session_start();
include 'config.php';

$query = $conn->prepare("SELECT IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username FROM UserPreferredCategories
                        INNER JOIN IndustryThreadCategories ON UserPreferredCategories.Industry_Thread_category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                        INNER JOIN Users ON UserPreferredCategories.User_ID = Users.User_ID
                        WHERE UserPreferredCategories.User_ID = ?
                        ORDER BY IndustryThreadCategories.Industry_Thread_Category_Year DESC LIMIT 3");
$query->bind_param("i", $_SESSION['User_ID']);
$query->execute();
$result = $query->get_result();

$preferred_categories = [];
$preferred_categories_username = [];
while ($row = $result->fetch_assoc()) {
    $preferred_categories[] = $row['Industry_Thread_Category_Name'];
    $preferred_categories_usernames[] = $row['Username'];
}


$query = $conn->prepare("SELECT IndustryThreadCategories.Industry_Thread_Category_Name, Users.Username FROM IndustryThreadCategories 
                        INNER JOIN Users ON IndustryThreadCategories.User_ID = Users.User_ID
                        ORDER BY Industry_Thread_Category_Year DESC LIMIT 3");
$query->execute();
$result = $query->get_result();

$explore_categories = [];
$explore_usernames = [];
while ($row = $result->fetch_assoc()) {
    $explore_categories[] = $row['Industry_Thread_Category_Name'];
    $explore_usernames[] = $row['Username'];
}



// Close the connection
$query->close();
$conn->close();

?>