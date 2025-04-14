<?php
    session_start();
    include 'config.php';

    // Ensure user is logged in
    if (!isset($_SESSION['User_ID'])) {
        die("Unauthorized access");
    }

    $user_id = $_SESSION['User_ID'];

    $query = $conn->prepare("SELECT Threads.Thread_ID, Threads.Thread_Title, Threads.Thread_Text, DATE(Threads.Thread_Date_Time) AS Thread_Date, Users.User_ID, Users.Username, IndustryThreadCategories.Industry_Thread_Category_ID, IndustryThreadCategories.Industry_Thread_Category_Name
                            FROM FavoriteThreads 
                            JOIN Threads ON FavoriteThreads.Thread_ID = Threads.Thread_ID
                            JOIN Users ON Threads.User_ID = Users.User_ID
                            JOIN IndustryThreadCategories ON Threads.Industry_Thread_Category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                            WHERE FavoriteThreads.User_ID = ?
                            ORDER BY Threads.Thread_Date_Time DESC");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    
    $favorite_threads = [];
    $favorite_threads_user_ids = [];
    $favorite_threads_usernames = [];
    $favorite_thread_category = [];
    $favorite_thread_text = [];
    $favorite_thread_year = [];
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

    $query->close();
    $conn->close();
?>