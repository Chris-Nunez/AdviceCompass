<?php
include 'config.php';

// Check if category_id is passed
if (!isset($_GET['category_id'])) {
    die("No category selected.");
}

$category_id = intval($_GET['category_id']);  

// Prepare and execute query
$query = $conn->prepare("SELECT Threads.Thread_ID, Threads.Thread_Title, Threads.Thread_Text, DATE(Threads.Thread_Date_Time) AS Thread_Date, Users.Username, Users.User_ID 
                        FROM Threads 
                        INNER JOIN Users ON Threads.User_ID = Users.User_ID
                        WHERE Threads.Industry_Thread_Category_ID = ?");
$query->bind_param("i", $category_id);
$query->execute();
$result = $query->get_result();

$thread_id = [];
$thread_title = [];
$thread_text = [];
$thread_date = [];
$thread_username = [];
$thread_user_id = [];

while ($row = $result->fetch_assoc()) {
    $thread_id[] = $row['Thread_ID'];  
    $thread_title[] = $row['Thread_Title'];
    $thread_text[] = $row['Thread_Text'];
    $thread_date[] = $row['Thread_Date'];
    $thread_username[] = $row['Username'];  
    $thread_user_id[] = $row['User_ID'];  
}

$query->close();
$conn->close();
?>
