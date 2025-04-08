<?php
    session_start();
    include 'config.php';

    // Check if category_id exists
    if (!isset($_GET['thread_id'])) {
        die("No category selected.");
    }

    $thread_id = intval($_GET['thread_id']);  

    // Fetch category details
    $query = $conn->prepare("SELECT * FROM Threads 
                            INNER JOIN Users ON Threads.User_ID = Users.User_ID
                            WHERE Thread_ID = ?");
    $query->bind_param("i", $thread_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $thread = $result->fetch_assoc();
    } else {
        die("Category not found.");
    }

    $is_favorited = false;

    if (isset($_SESSION['User_ID'])) {
        $logged_in_user_id = $_SESSION['User_ID'];

        $fav_query = $conn->prepare("SELECT * FROM FavoriteThreads WHERE User_ID = ? AND Thread_ID = ?");
        $fav_query->bind_param("ii", $logged_in_user_id, $thread_id);
        $fav_query->execute();
        $fav_result = $fav_query->get_result();

        if ($fav_result->num_rows > 0) {
            $is_favorited = true;
        }
    }

    include 'fetch-comments.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($thread['Thread_Title']); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <nav class="navbar navbar-expand-md fixed-top" style="background-color: #303030;">
            <div class="container">
                <a href="home.php" class="navbar-brand text-white">
                    <h1 class="text-white mb-0">AdviceCompass</h1>
                </a>
        
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav-collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="nav-collapse">
                    <div class="navbar-nav ms-auto">
                        <a href="view-profile.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                            <i class="bi bi-person-fill me-2" id="user-icon"></i>
                        </a>
                        <span class="text-white me-4" id="navbar-username"><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
                        <a href="settings.php">
                            <i class="bi bi-gear me-4" id="gear-icon"></i>
                        </a>
                        <a href="logout.php">   
                            <button class="navbar-logout-button">Logout</button>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <section id="main">
            <div class="view-thread-container px-5">

                <div class="top-container d-flex align-items-center justify-content-between">
            
                    <div class="d-flex align-items-center flex-1">
                        <button class="explore-categories-back-button mx-2" onclick="history.back();">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>

                        <button class="favorite-thread-button mx-2 <?php echo $is_favorited ? 'favorited' : ''; ?>" 
                                id="favorite-thread-btn" 
                                thread-id="<?php echo $thread_id; ?>">
                            <i class="bi <?php echo $is_favorited ? 'bi-star-fill' : 'bi-star'; ?>"></i> 
                            <?php echo $is_favorited ? 'Favorited' : 'Favorite'; ?>
                        </button>
                        <?php if (isset($_SESSION['User_ID']) && $_SESSION['User_ID'] == $thread['User_ID']): ?>
                            <button class="delete-thread-button mx-2" id="delete-thread-btn" data-thread-id="<?php echo $thread_id; ?>">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="explore-categories-title">
                    <h1><?php echo htmlspecialchars($thread['Thread_Title']); ?></h1>
                </div>  

                <div class="explore-categories-title">
                    <h3>
                        <a href="view-profile.php?user_id=<?php echo urlencode($thread['User_ID']); ?>">
                            <?php echo htmlspecialchars($thread['Username']); ?>
                        </a>
                    </h3>
                </div> 

                <div class="thread">
                    <div class="view-thread-text">
                        <p><?php echo htmlspecialchars($thread['Thread_Text']); ?></p>
                    </div>

                    <?php if (!empty($thread['Thread_Image'])): ?>
                        <div class="view-thread-image">
                            <img src="<?php echo htmlspecialchars($thread['Thread_Image']); ?>" alt="Thread Image">
                        </div>
                    <?php endif; ?>


                    <div class="thread-actions">
                        <a href="#" class="action-button like-thread" data-thread-id="<?php echo $thread['Thread_ID']; ?>" data-is-like="1">
                            <i class="bi bi-hand-thumbs-up"></i> <span id="thread-like-count"><?php echo $thread['Thread_Like_Count']; ?></span>
                        </a>

                        <a href="#" class="action-button dislike-thread" data-thread-id="<?php echo $thread['Thread_ID']; ?>" data-is-like="0">
                            <i class="bi bi-hand-thumbs-down"></i> <span id="thread-dislike-count"><?php echo $thread['Thread_Dislike_Count']; ?></span>
                        </a>

                        <button class="action-button comment" id="comment-button">
                            <i class="bi bi-chat-dots"></i> <?php echo $thread['Thread_Comment_Count']; ?>
                        </button>
                    </div>

                </div>

                <!-- Comment Input Section (Initially Hidden) -->
                <div id="comment-box" style="display: none; margin-top: 10px;">
                    <textarea id="comment-text" class="form-control" placeholder="Write your comment..."></textarea>
                    <button id="submit-comment" class="btn btn-primary mt-2">Post Comment</button>
                </div>
                
                <?php 
                for ($i = 0; $i < count($comment_id); $i++) {
                ?>
                    <div class="comments-container">
                        <div class="comment-username">
                            <p><?php echo htmlspecialchars($comment_username[$i]); ?> </p>
                        </div>

                        <div class="comment">
                            <p><?php echo htmlspecialchars($comment_text[$i]); ?> </p>
                        </div>

                        <div class="comment-date-time">
                            <p><?php echo htmlspecialchars($comment_date_time[$i]); ?> </p>
                        </div>

                        <div class="thread-actions">
                            <a href="comment-like.php?comment_id=<?php echo $comment_id[$i]; ?>&is_like=1" class="action-button like" id="like-btn-<?php echo $comment_id[$i]; ?>" data-comment-id="<?php echo $comment_id[$i]; ?>" data-is-like="1">
                                <i class="bi bi-hand-thumbs-up"></i> <span id="like-count-<?php echo $comment_id[$i]; ?>"><?php echo $comment_like_count[$i]; ?></span>
                            </a>

                            <a href="comment-like.php?comment_id=<?php echo $comment_id[$i]; ?>&is_like=0" class="action-button dislike" id="dislike-btn-<?php echo $comment_id[$i]; ?>" data-comment-id="<?php echo $comment_id[$i]; ?>" data-is-like="0">
                                <i class="bi bi-hand-thumbs-down"></i> <span id="dislike-count-<?php echo $comment_id[$i]; ?>"><?php echo $comment_dislike_count[$i]; ?></span>
                            </a>

                            <button class="action-button comment" id="reply-button-<?php echo $comment_id[$i]; ?>">
                                <i class="bi bi-chat-dots"></i> <?php echo $comment_reply_count[$i]; ?>
                            </button>

                            <button class="view-replies-btn" id="view-replies-<?php echo $comment_id[$i]; ?>">View Replies</button>
                        </div>
            
                    </div>

                    <!-- Reply Box (Initially Hidden) -->
                    <div id="reply-box-<?php echo $comment_id[$i]; ?>" class="reply-box" style="display: none; margin-top: 10px;">
                        <textarea id="reply-text-<?php echo $comment_id[$i]; ?>" class="form-control" placeholder="Write your reply..."></textarea>
                        <button class="btn btn-primary submit-reply-btn" id="submit-reply-<?php echo $comment_id[$i]; ?>">Post Reply</button>
                    </div>

                    <div class="replies-container" id="replies-<?php echo $comment_id[$i]; ?>" style="display: none;">
                        <!-- Replies will be loaded dynamically here -->
                    </div>

                <?php } ?>

            </div>
        </section>

        <section id="footer-section">
            <div class="footer-container">
              <div class="footer-text">
                <p>&copy; AdviceCompass 2025</p>
              </div>
            </div>
        </section>

        <script>

            document.addEventListener("DOMContentLoaded", function() {
                let button = document.getElementById('favorite-thread-btn');
                
                if (button.classList.contains('favorited')) {
                    button.innerHTML = '<i class="bi bi-star-fill"></i> Favorited';
                } else {
                    button.innerHTML = '<i class="bi bi-star"></i> Favorite';
                }

                button.addEventListener('click', function() {
                    let threadId = parseInt(this.getAttribute('thread-id'), 10);
                    
                    if (!threadId) {
                        console.error("Error: thread_id is missing.");
                        return;
                    }

                    fetch('favorite-thread-process.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'thread_id=' + encodeURIComponent(threadId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (button.classList.contains('favorited')) {
                                button.innerHTML = '<i class="bi bi-star"></i> Favorite';
                                button.classList.remove('favorited');
                            } else {
                                button.innerHTML = '<i class="bi bi-star-fill"></i> Favorited';
                                button.classList.add('favorited');
                            }
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            document.addEventListener("DOMContentLoaded", () => {
                const deleteBtn = document.getElementById("delete-thread-btn");
                if (deleteBtn) {
                    deleteBtn.addEventListener("click", () => {
                        const threadId = deleteBtn.getAttribute("data-thread-id");
                        if (confirm("Are you sure you want to delete this thread?")) {
                            fetch("delete-thread.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "thread_id=" + encodeURIComponent(threadId)
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data.startsWith("success:")) {
                                    const categoryId = data.split(":")[1];
                                    alert("Thread deleted successfully.");
                                    window.location.href = "thread-category.php?category_id=" + encodeURIComponent(categoryId);
                                } else {
                                    alert(data);
                                }
                            })
                            .catch(error => {
                                console.error("Error deleting thread:", error);
                                alert("Something went wrong.");
                            });
                        }
                    });
                }
            });

            document.addEventListener("DOMContentLoaded", function () {
                function handleVote(buttonClass) {
                    document.querySelectorAll(buttonClass).forEach(button => {
                        button.addEventListener("click", function (event) {
                            event.preventDefault(); // Prevent default navigation

                            let threadId = this.dataset.threadId;
                            let isLike = this.dataset.isLike; // 1 = like, 0 = dislike

                            fetch("like.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded",
                                },
                                body: `thread_id=${threadId}&is_like=${isLike}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById("thread-like-count").innerText = data.likes;
                                    document.getElementById("thread-dislike-count").innerText = data.dislikes;
                                } else {
                                    alert("Error: " + data.message);
                                }
                            })
                            .catch(error => console.error("Error:", error));
                        });
                    });
                }

                handleVote(".like-thread");
                handleVote(".dislike-thread");
            });

            document.getElementById('comment-button').addEventListener('click', function () {
                let commentBox = document.getElementById('comment-box');
                
                // Toggle the visibility of the comment box
                if (commentBox.style.display === 'none' || commentBox.style.display === '') {
                    commentBox.style.display = 'block';
                } else {
                    commentBox.style.display = 'none';
                }
            });


            document.getElementById('submit-comment').addEventListener('click', function () {
                let commentText = document.getElementById('comment-text').value.trim();
                let threadId = "<?php echo $thread_id; ?>"; // Get thread ID from PHP

                if (commentText === '') {
                    alert("Comment cannot be empty!");
                    return;
                }

                fetch('submit-comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'thread_id=' + encodeURIComponent(threadId) + '&comment_text=' + encodeURIComponent(commentText)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // ✅ Reloads the page
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));

            });

            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll(".action-button.like, .action-button.dislike").forEach(button => {
                    button.addEventListener("click", function (event) {
                        event.preventDefault(); // Prevent default link behavior

                        // Disable the button to prevent further clicks until the request finishes
                        this.disabled = true;

                        let commentId = this.dataset.commentId;
                        let isLike = parseInt(this.dataset.isLike, 10); // Convert to integer

                        fetch("comment-like.php", {
                            method: "POST", // Use POST
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: `comment_id=${commentId}&is_like=${isLike}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the like/dislike counts dynamically
                                document.getElementById("like-count-" + commentId).textContent = data.likes;
                                document.getElementById("dislike-count-" + commentId).textContent = data.dislikes;
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error))
                        .finally(() => {
                            // Re-enable the button after the request is finished
                            this.disabled = false;
                        });
                    });
                });
            });

            // Toggle reply box visibility
            document.querySelectorAll('.action-button.comment').forEach(function(button) {
                button.addEventListener('click', function () {
                    let commentId = this.id.split('-')[2];  // Get the comment ID from button id
                    let replyBox = document.getElementById('reply-box-' + commentId);
                    let repliesContainer = document.getElementById('replies-' + commentId);

                    // Toggle the visibility of the reply box
                    replyBox.style.display = replyBox.style.display === 'none' ? 'block' : 'none';

                    // Toggle the visibility of the replies container
                    repliesContainer.style.display = repliesContainer.style.display === 'none' ? 'block' : 'none';
                });
            });

            // Submit the reply
            document.querySelectorAll('.submit-reply-btn').forEach(function(button) {
                button.addEventListener('click', function () {
                    let commentId = this.id.split('-')[2];  // Get comment ID from button id
                    let replyText = document.getElementById('reply-text-' + commentId).value.trim();

                    if (replyText === '') {
                        alert("Reply cannot be empty!");
                        return;
                    }

                    fetch('submit-reply.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'comment_id=' + encodeURIComponent(commentId) + '&reply_text=' + encodeURIComponent(replyText)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // ✅ Reloads the page
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));

                });
            });

            document.querySelectorAll('.view-replies-btn').forEach(function(button) {
                button.addEventListener('click', function () {
                    let commentId = this.id.split('-')[2]; // Extract comment ID
                    let repliesContainer = document.getElementById('replies-' + commentId);

                    // Toggle visibility of the replies container
                    if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
                        repliesContainer.style.display = 'block'; // Show replies
                        this.innerText = 'Hide Replies'; // Change button text to "Hide Replies"

                        // Fetch the replies from the server via AJAX
                        fetch(`fetch-replies.php?comment_id=${commentId}`)
                            .then(response => response.json())
                            .then(data => {
                                // Clear the replies container first
                                repliesContainer.innerHTML = '';

                                // Check if there are replies
                                if (data.length > 0) {
                                    // Loop through the replies and display them
                                    data.forEach(reply => {
                                        let replyDiv = document.createElement('div');
                                        replyDiv.classList.add('reply');
                                        replyDiv.innerHTML = `
                                            <div class="reply-username"><p>${reply.username}</p></div>
                                            <div class="reply-text"><p>${reply.text}</p></div>
                                            <div class="reply-date-time"><p>${reply.date_time}</p></div>
                                            <div class="reply-actions">
                                                <a href="#" class="action-button like" data-reply-id="${reply.reply_id}" data-is-like="1">
                                                    <i class="bi bi-hand-thumbs-up"></i><span id="like-count-${reply.reply_id}">${reply.like_count}</span>
                                                </a>
                                                <a href="#" class="action-button dislike" data-reply-id="${reply.reply_id}" data-is-like="0">
                                                    <i class="bi bi-hand-thumbs-down"></i><span id="dislike-count-${reply.reply_id}">${reply.dislike_count}</span>
                                                </a>
                                            </div>
                                        `;
                                        repliesContainer.appendChild(replyDiv);
                                    });
                                } else {
                                    repliesContainer.innerHTML = '<p>No replies yet.</p>';
                                }
                            })
                            .catch(error => console.error('Error loading replies:', error));
                    } else {
                        repliesContainer.style.display = 'none'; // Hide replies
                        this.innerText = 'View Replies'; // Change button text back to "View Replies"
                    }
                });
            });

            // Like and dislike functionality for replies
            document.addEventListener("click", function (event) {
                if (event.target.closest(".action-button.like") || event.target.closest(".action-button.dislike")) {
                    event.preventDefault();

                    let button = event.target.closest(".action-button");
                    let replyId = button.dataset.replyId;
                    let isLike = button.dataset.isLike;

                    fetch("reply-like.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `reply_id=${replyId}&is_like=${isLike}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById("like-count-" + replyId).textContent = data.likes;
                            document.getElementById("dislike-count-" + replyId).textContent = data.dislikes;
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
                }
            });

        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>