<?php
session_start();
include 'config.php';

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