<?php
include 'config.php';
session_start();

if (!isset($_SESSION['User_ID'])) {
    echo '<p class="text-muted">Please log in to view your favorite threads.</p>';
    exit;
}

$user_id = $_SESSION['User_ID'];
$search = isset($_GET['query']) ? trim($_GET['query']) : '';

$sql = "
    SELECT t.Thread_ID, t.Thread_Title, t.Thread_Text, DATE(t.Thread_Date_Time) AS Thread_Date, 
           u.Username, u.User_ID, itc.Industry_Thread_Category_Name
    FROM FavoriteThreads ft
    INNER JOIN Threads t ON ft.Thread_ID = t.Thread_ID
    INNER JOIN Users u ON t.User_ID = u.User_ID
    INNER JOIN IndustryThreadCategories itc ON t.Industry_Thread_Category_ID = itc.Industry_Thread_Category_ID
    WHERE ft.User_ID = ? AND (t.Thread_Title LIKE ? OR t.Thread_Text LIKE ?)
    ORDER BY t.Thread_Date_Time DESC";

$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("iss", $user_id, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

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
                        <p>' . htmlspecialchars($row["Thread_Text"]) . '</p>
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

$stmt->close();
$conn->close();
?>
