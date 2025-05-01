<?php
include 'config.php';
session_start();

if (!isset($_SESSION['User_ID'])) {
    header("Location: login.php?error=1");
    exit();
}

$user_id = $_SESSION['User_ID'];
$search = isset($_GET['query']) ? trim($_GET['query']) : '';


$query = $conn->prepare("SELECT 
                            Threads.Thread_ID, 
                            Threads.Thread_Title, 
                            Threads.Thread_Text, 
                            DATE(Threads.Thread_Date_Time) AS Thread_Date, 
                            Users.Username, 
                            Users.User_ID, 
                            IndustryThreadCategories.Industry_Thread_Category_Name
                        FROM 
                            FavoriteThreads
                        INNER JOIN 
                            Threads ON FavoriteThreads.Thread_ID = Threads.Thread_ID
                        INNER JOIN 
                            Users ON Threads.User_ID = Users.User_ID
                        INNER JOIN 
                            IndustryThreadCategories ON Threads.Industry_Thread_Category_ID = IndustryThreadCategories.Industry_Thread_Category_ID
                        WHERE 
                            FavoriteThreads.User_ID = ? 
                            AND (Threads.Thread_Title LIKE ? OR Threads.Thread_Text LIKE ?)
                        ORDER BY 
                            Threads.Thread_Date_Time DESC");
$searchParam = "%" . $search . "%";
$query->bind_param("iss", $user_id, $searchParam, $searchParam);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="thread-container">
                    <div class="thread-title">
                        <h5>' . htmlspecialchars($row["Thread_Title"]) . '</h5>
                    </div>

                    <div class="thread-username">
                        <p>Made by: 
                            <a href="view-profile.php?user_id=' . urlencode($row["User_ID"]) . '">
                                ' . htmlspecialchars($row["Username"]) . '
                            </a>
                        </p>
                    </div>

                    <div class="thread-category">
                        <p>Category: ' . htmlspecialchars($row["Industry_Thread_Category_Name"]) . '</p>
                    </div>

                    <div class="thread-text">
                        <p>' . htmlspecialchars(mb_strimwidth($row["Thread_Text"], 0, 27, '...')) . '</p>
                    </div>

                    <div class="thread-year-created">
                        <p>Created ' . htmlspecialchars($row["Thread_Date"]) . '</p>
                    </div>

                    <a href="thread.php?thread_id=' . urlencode($row["Thread_ID"]) . '">
                        <button class="explore-thread-categories-button">Go <i class="bi bi-arrow-right"></i></button>
                    </a>
                </div>
            </div>';
    }

} else {

    echo '<div class="no-threads-container text-center mt-5">
            <div class="no-threads-text">
                <p>No favorite threads.</p>
            </div>
          </div>';
}

$query->close();
$conn->close();
?>
