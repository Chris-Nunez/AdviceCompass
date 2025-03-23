<?php
    session_start();
    include 'config.php';

    // Check if category_id exists
    if (!isset($_GET['thread_id'])) {
        die("No category selected.");
    }

    $thread_id = intval($_GET['thread_id']);  // Ensure it's an integer

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
                        <i class="bi bi-person-fill me-2" id="user-icon"></i>
                        <span class="text-white me-4" id="navbar-username"><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
                        <a href="settings.html">
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
            
                    <!-- Left Section: Back & Create Category Buttons -->
                    <div class="d-flex align-items-center flex-1">

                        <a href="thread-category.php">
                            <button class="thread-category-back-button mx-2"><i class="bi bi-arrow-left"></i> Back</button>
                        </a>


                        <button class="favorite-category-button mx-2" id="favorite-category-btn" thread-id="<?php echo $thread_id; ?>">
                            <i class="bi bi-star"></i> Favorite
                        </button>
                    </div>

                    <!-- Center Section: Title -->
                    <div class="explore-categories-title flex-grow-1 text-center">
                        <h1><?php echo htmlspecialchars($thread['Thread_Title']); ?></h1>
                    </div>  

                </div>

                <div class="explore-categories-title flex-grow-1 text-center">
                    <h3><?php echo htmlspecialchars($thread['Username']); ?></h3>
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
                        <!-- Like Button -->
                        <a href="like.php?thread_id=<?php echo $thread['Thread_ID']; ?>" class="action-button like">
                            <i class="bi bi-hand-thumbs-up"></i> <?php echo $thread['Thread_Like_Count']; ?>
                        </a>

                        <!-- Dislike Button -->
                        <a href="dislike.php?thread_id=<?php echo $thread['Thread_ID']; ?>" class="action-button dislike">
                            <i class="bi bi-hand-thumbs-down"></i> <?php echo $thread['Thread_Dislike_Count']; ?>
                        </a>

                        <!-- Comment Button -->
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
                            <!-- Like Button -->
                            <a href="like.php?comment_id=<?php echo $comment_id[$i]; ?>" class="action-button like">
                                <i class="bi bi-hand-thumbs-up"></i> <?php echo $comment_like_count[$i]; ?>
                            </a>
                            <!-- Dislike Button -->
                            <a href="dislike.php?comment_id=<?php echo $comment_id[$i]; ?>" class="action-button dislike">
                                <i class="bi bi-hand-thumbs-down"></i> <?php echo $comment_dislike_count[$i]; ?>
                            </a>
                            <!-- Reply Button -->
                            <button class="action-button comment" id="reply-button-<?php echo $comment_id[$i]; ?>">
                                <i class="bi bi-chat-dots"></i> <?php echo $comment_reply_count[$i]; ?>
                            </button>
                            <!-- View Replies Button -->
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let commentContainer = document.querySelector('.comments-container');

                        // Create a new comment element
                        let newComment = document.createElement('div');
                        newComment.classList.add('comments-container'); // Ensure it matches existing styles

                        newComment.innerHTML = `
                            <div class="comment-username">
                                <p>${data.username}</p>
                            </div>
                            <div class="comment">
                                <p>${data.comment_text}</p>
                            </div>
                            <div class="comment-date-time">
                                <p>${data.comment_date_time}</p>
                            </div>
                            <div class="thread-actions">
                                <a href="like.php?comment_id=${data.comment_id}" class="action-button like">
                                    <i class="bi bi-hand-thumbs-up"></i> 0
                                </a>
                                <a href="dislike.php?comment_id=${data.comment_id}" class="action-button dislike">
                                    <i class="bi bi-hand-thumbs-down"></i> 0
                                </a>
                                <a href="reply.php?comment_id=${data.comment_id}" class="action-button comment">
                                    <i class="bi bi-chat-dots"></i> 0
                                </a>
                            </div>
                        `;

                        if (commentContainer) {
                            commentContainer.parentNode.insertBefore(newComment, commentContainer);
                        } else {
                            // If no comments exist yet, add it under the thread
                            let threadContainer = document.querySelector('.thread');
                            threadContainer.parentNode.insertBefore(newComment, threadContainer.nextSibling);
                        }

                        // Clear input field
                        document.getElementById('comment-text').value = '';

                        // Hide comment box
                        document.getElementById('comment-box').style.display = 'none';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Toggle reply box visibility
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
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let repliesContainer = document.getElementById('replies-' + commentId);

                            // Create a new reply element
                            let newReply = document.createElement('div');
                            newReply.classList.add('reply');
                            newReply.innerHTML = `
                                <div class="reply-username">
                                    <p>${data.username}</p>
                                </div>
                                <div class="reply-text">
                                    <p>${data.reply_text}</p>
                                </div>
                                <div class="reply-date-time">
                                    <p>${data.reply_date_time}</p>
                                </div>
                            `;

                            // Add the new reply to the replies container
                            repliesContainer.appendChild(newReply);

                            // Clear the reply input field
                            document.getElementById('reply-text-' + commentId).value = '';

                            // Optionally hide the reply box after submission
                            document.getElementById('reply-box-' + commentId).style.display = 'none';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            // Add event listener to the "View Replies" button
            // Add event listener to the "View Replies" button
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
                                                <a href="like.php?reply_id=${reply.reply_id}" class="action-button like">
                                                    <i class="bi bi-hand-thumbs-up"></i>${reply.like_count}
                                                </a>
                                                <a href="dislike.php?reply_id=${reply.reply_id}" class="action-button dislike">
                                                    <i class="bi bi-hand-thumbs-down"></i>${reply.dislike_count}
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

        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>