<?php
include 'config.php';

// Check if category_id is passed
if (!isset($_GET['category_id'])) {
    die("No category selected.");
}

$category_id = intval($_GET['category_id']);  // Ensure it's an integer

// Prepare and execute query
$query = $conn->prepare("SELECT Threads.Thread_ID, Threads.Thread_Title, Threads.Thread_Text, DATE(Threads.Thread_Date_Time) AS Thread_Date, Users.Username 
                        FROM Threads 
                        INNER JOIN Users ON Threads.User_ID = Users.User_ID
                        WHERE Threads.Industry_Thread_Category_ID = ?");
$query->bind_param("i", $category_id);
$query->execute();
$result = $query->get_result();

// Initialize arrays
$thread_id = [];
$thread_title = [];
$thread_text = [];
$thread_date = [];
$thread_username = [];

// Fetch data
while ($row = $result->fetch_assoc()) {
    $thread_id[] = $row['Thread_ID'];  // Remove "Threads."
    $thread_title[] = $row['Thread_Title'];
    $thread_text[] = $row['Thread_Text'];
    $thread_date[] = $row['Thread_Date'];
    $thread_username[] = $row['Username'];  // Remove "Users."
}

// Close resources
$query->close();
$conn->close();
?>
