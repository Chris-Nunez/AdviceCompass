<?php
    session_start();
    include 'config.php';

    // Ensure user is logged in
    if (!isset($_SESSION['User_ID'])) {
        die("Unauthorized access");
    }

    $user_id = $_SESSION['User_ID'];

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
                            ORDER BY Threads.Thread_Date_Time DESC");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    
    $following_threads = [];
    $following_threads_user_ids = [];
    $following_threads_usernames = [];
    $following_thread_category = [];
    $following_thread_text = [];
    $following_thread_year = [];
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