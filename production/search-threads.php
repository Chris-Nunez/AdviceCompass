<?php
include 'config.php';

$search = isset($_GET['query']) ? trim($_GET['query']) : '';
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

$sql = "SELECT Threads.Thread_ID, Threads.Thread_Title, Threads.Thread_Text, DATE(Threads.Thread_Date_Time) AS Thread_Date, 
               Users.Username, Users.User_ID
        FROM Threads 
        INNER JOIN Users ON Threads.User_ID = Users.User_ID
        WHERE Threads.Industry_Thread_Category_ID = ? AND Threads.Thread_Title LIKE ? 
        ORDER BY Threads.Thread_Date_Time DESC";

$stmt = $conn->prepare($sql);
$searchParam = "%" . $search . "%";
$stmt->bind_param("is", $category_id, $searchParam);
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
                        <p>Made by: <a href="view-profile.php?user_id=' . urlencode($row["User_ID"]) . '">' . htmlspecialchars($row["Username"]) . '</a></p>
                    </div>

                    <div class="thread-text">
                        <p>' . htmlspecialchars($row["Thread_Text"]) . '</p>
                    </div>

                    <div class="thread-year-created">
                        <p>Created ' . htmlspecialchars($row["Thread_Date"]) . '</p>
                    </div>

                    <a href="thread.php?thread_id=' . urlencode($row["Thread_ID"]) . '">
                        <button class="thread-button">Go <i class="bi bi-arrow-right"></i></button>
                    </a>
                </div>
            </div>';
    }
} else {
    echo '<p class="text-muted">No threads found matching your query.</p>';
}

$stmt->close();
$conn->close();
?>
