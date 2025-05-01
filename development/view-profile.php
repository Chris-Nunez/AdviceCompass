<?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION['User_ID'])) {
        header("Location: login.php?error=1");
        exit();
    }
    
    $user_id = intval($_GET['user_id']); 
    
    $query = $conn->prepare("SELECT Users.*,
                            COUNT(DISTINCT Threads.Thread_ID) AS Thread_Count, 
                            COUNT(DISTINCT ThreadComments.Thread_Comment_Id) AS Comment_Count,
                            COUNT(DISTINCT UserFollowers.Follower_ID) AS Follower_Count,
                            COUNT(DISTINCT UserFollowers.Following_ID) AS Following_Count
                            FROM Users 
                            LEFT JOIN Threads ON Users.User_ID = Threads.User_ID
                            LEFT JOIN ThreadComments ON Threads.Thread_ID = ThreadComments.Thread_ID
                            LEFT JOIN UserFollowers ON Users.User_ID = UserFollowers.Following_ID
                            WHERE Users.User_ID = ?
                            GROUP BY Users.User_ID");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
    }

    $first_name = $user["First_Name"];
    $last_name = $user["Last_Name"];
    $username = $user["Username"];
    $occupation_title = $user["Occupation_Title"];
    $bio_text = $user["Bio_Text"];
    $location_state = $user["Location_State"];
    $profile_image = $user["Profile_Image"];
    $follower_count = $user["Follower_Count"];
    $following_count = $user["Following_Count"];
    $thread_count = $user["Thread_Count"];
    $comment_count = $user["Comment_Count"];
    $year_created = $user["Year_Created"];

    $is_following = false;
    if (isset($_SESSION['User_ID'])) {
        $logged_in_user_id = $_SESSION['User_ID'];

        $follow_query = $conn->prepare("SELECT * FROM UserFollowers WHERE Follower_ID = ? AND Following_ID = ?");
        $follow_query->bind_param("ii", $logged_in_user_id, $user_id);
        $follow_query->execute();
        $follow_result = $follow_query->get_result();

        if ($follow_result->num_rows > 0) {
            $is_following = true;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
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
                        <?php if (!isset($_SESSION['User_ID']) || !isset($_SESSION['Username'])): ?>
                            <a href="index.html">
                                <button class="navbar-login-button me-4">Login</button>
                            </a>
                            <a href="register.php">
                                <button class="navbar-signup-button">Sign Up</button>
                            </a>
                        <?php else: ?>
                            <a href="view-profile.php?user_id=<?php echo $_SESSION['User_ID']; ?>">
                                <i class="bi bi-person-fill me-2" id="user-icon"></i>
                                <span class="text-white me-4" id="navbar-username"><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
                            </a>
                            
                            <a href="settings.php">
                                <i class="bi bi-gear me-4" id="gear-icon"></i>
                            </a>
                            <a href="logout.php">   
                                <button class="navbar-logout-button">Logout</button>
                            </a>
                            <a href="help.php">
                                <i class="bi bi-question-circle"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <section id="profile">
            <div class="profile px-5">
                <div class="top-container d-flex align-items-center justify-content-between">
                        
                    <!-- Left Section: Back & Create Category Buttons -->
                    <div class="d-flex align-items-center flex-1">
                        <button class="explore-categories-back-button mx-2" onclick="history.back();">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                    </div>

                </div>
                <h2 id="profile-title">Profile</h2>
                <div class="profile-container">                
                    <div class="profile-top-row">
                        <div class="profile-image-container">
                            <div class="profile-image" id="profile-image-wrapper" style="background-color: black;">
                                <?php if (!empty($profile_image)) : ?>
                                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" id="profile-image">
                                <?php endif; ?>
                            </div>

                            <?php if ($user_id == $_SESSION["User_ID"]) { ?>
                                <div class="edit-image-button-container mt-2">
                                    <input type="file" id="image-upload" style="display: none;" accept="image/*">
                                    <button class="edit-btn" id="edit-image-button" onclick="document.getElementById('image-upload').click();">
                                        <i class="bi bi-pencil"></i> Edit Image
                                    </button>
                                    <?php if (!empty($profile_image)) : ?>
                                        <button class="edit-btn btn-danger mt-2" id="remove-image-button">
                                            <i class="bi bi-trash"></i> Remove Image
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="profile-info">
                            <div class="profile-name">
                                <?php echo htmlspecialchars($first_name . " " . $last_name . " (" . $username . ")"); ?>
                            </div>
                            <div class="profile-occupation-title">
                                <p id="occupation-title"><?php echo htmlspecialchars($occupation_title); ?></p>
                                <?php if ($user_id == $_SESSION["User_ID"]) { ?>
                                    <button class="edit-btn" onclick="editOccupation()">
                                        <i class="bi bi-pencil"></i> Edit Occupation Title
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="profile-bio-container">
                        <h4>Bio</h4>
                        <p id="bio-text"><?php echo htmlspecialchars($bio_text); ?></p>
                        <?php if ($user_id == $_SESSION["User_ID"]) { ?>
                            <button class="edit-btn" onclick="editBio()">
                                <i class="bi bi-pencil"></i> Edit Bio
                            </button>
                        <?php } ?>
                    </div>
                    <div class="profile-bottom-row">
                        <div class="location-state-container">
                            <div class="location-state-title">From</div>
                            <div id="location-text"><?php echo htmlspecialchars($location_state); ?></div>
                            <?php if ($user_id == $_SESSION["User_ID"]) { ?>
                                <button class="edit-btn" onclick="editLocation()">
                                    <i class="bi bi-pencil"></i> Edit Location
                                </button>
                            <?php } ?>
                        </div>
                        <div class="follower-count-container">
                            <div class="follower-count-title">
                                Follower Count
                            </div>
                            <div class="follower-count">
                                <?php echo htmlspecialchars($follower_count); ?>
                            </div>
                            <a href="view-followers.php?user_id=<?php echo htmlspecialchars($user_id); ?>">
                                <button class="view-followers-button">
                                    View Followers
                                </button>
                            </a>
                        </div>
                        <div class="following-count-container">
                            <div class="following-count-title">
                                Following Count
                            </div>
                            <div class="following-count">
                                <?php echo htmlspecialchars($following_count); ?>
                            </div>
                            <a href="view-following.php?user_id=<?php echo htmlspecialchars($user_id); ?>">
                                <button class="view-following-button">
                                    View Following
                                </button>
                            </a>
                        </div>
                        <div class="thread-count-container">
                            <div class="thread-count-title">
                                Thread Count
                            </div>
                            <div class="thread-count">
                                <?php echo htmlspecialchars($thread_count); ?>
                            </div>
                            <a href="view-user-threads.php?user_id=<?php echo htmlspecialchars($user_id); ?>">
                                <button class="view-threads-button">
                                    View Threads
                                </button>
                            </a>
                        </div>
                        <div class="comment-count-container">
                            <div class="comment-count-title">
                                Comment Count
                            </div>
                            <div class="comment-count">
                                <?php echo htmlspecialchars($comment_count); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php if ($user_id !== $_SESSION["User_ID"]) { ?>
                <div class="follow-user">
                    <button class="follow-user-button" id="follow-button" data-user-id="<?php echo $user_id; ?>">
                        <?php echo $is_following ? "Unfollow" : "Follow"; ?>
                    </button>
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

            const navCollapse = document.getElementById('nav-collapse');
            const navbar = document.querySelector('.navbar');

            navCollapse.addEventListener('show.bs.collapse', () => {
                navbar.classList.add('expanded');
            });

            navCollapse.addEventListener('hide.bs.collapse', () => {
                navbar.classList.remove('expanded');
            });

            const CSRF_TOKEN = "<?php echo $_SESSION['csrf_token']; ?>";

            document.addEventListener("DOMContentLoaded", function () {
                let followButton = document.getElementById("follow-button");

                if (followButton) {
                    followButton.addEventListener("click", function () {
                        let userId = followButton.getAttribute("data-user-id");

                        fetch("follow-user.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `following_id=${userId}` +
                                '&csrf_token=' + encodeURIComponent(CSRF_TOKEN)
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data === "followed") {
                                followButton.textContent = "Unfollow";
                            } else if (data === "unfollowed") {
                                followButton.textContent = "Follow";
                            } else {
                                alert("Error processing request.");
                            }
                        })
                        .catch(error => console.error("Fetch error:", error));
                    });
                }
            });


            document.addEventListener("DOMContentLoaded", function () {
                const imageUploadInput = document.getElementById("image-upload");
                const removeBtn = document.getElementById("remove-image-button");
                const profileImageWrapper = document.getElementById("profile-image-wrapper");

                // Handle image upload
                if (imageUploadInput) {
                    imageUploadInput.addEventListener("change", function () {
                        const file = imageUploadInput.files[0];
                        if (!file) return;

                        const formData = new FormData();
                        formData.append("profile-image", file);

                        fetch("update-profile.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.text())
                        .then(result => {
                            if (result.startsWith("success:")) {
                                const imagePath = result.split(":")[1];
                                let imgTag = document.getElementById("profile-image");

                                if (imgTag) {
                                    imgTag.src = imagePath;
                                } else {
                                    // If image tag doesn't exist, create it
                                    imgTag = document.createElement("img");
                                    imgTag.id = "profile-image";
                                    imgTag.alt = "Profile Picture";
                                    imgTag.src = imagePath;
                                    profileImageWrapper.appendChild(imgTag);
                                }

                                // Add remove button if it doesn’t already exist
                                if (!document.getElementById("remove-image-button")) {
                                    const removeBtn = document.createElement("button");
                                    removeBtn.className = "edit-btn btn-danger mt-2";
                                    removeBtn.id = "remove-image-button";
                                    removeBtn.innerHTML = '<i class="bi bi-trash"></i> Remove Image';
                                    imageUploadInput.parentElement.appendChild(removeBtn);
                                    bindRemoveButton(removeBtn);
                                }
                            } else {
                                alert(result);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Something went wrong uploading the image.");
                        });
                    });
                }

                // Function to bind remove button logic
                function bindRemoveButton(btn) {
                    btn.addEventListener("click", function () {
                        if (!confirm("Are you sure you want to remove your profile picture?")) return;

                        fetch("update-profile.php", {
                            method: "POST",
                            body: new URLSearchParams({ action: "remove_profile_image" })
                        })
                        .then(res => res.text())
                        .then(result => {
                            if (result.includes("success")) {
                                const img = document.getElementById("profile-image");
                                if (img) img.remove();

                                const removeBtn = document.getElementById("remove-image-button");
                                if (removeBtn) removeBtn.remove();
                            } else {
                                alert("Error: " + result);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Something went wrong removing the image.");
                        });
                    });
                }

                // Initial bind if button exists at load
                if (removeBtn) {
                    bindRemoveButton(removeBtn);
                }
            });



            // Edit occupation title functionality
            function editOccupation() {
                let occupationTextElement = document.getElementById('occupation-title');
                let originalOccupation = occupationTextElement.innerText;

                // Create a textarea to edit occupation title
                let textarea = document.createElement('textarea');
                textarea.value = originalOccupation;
                textarea.classList.add('edit-textarea');

                // Create a submit button for saving the new occupation title
                let submitButton = document.createElement('button');
                submitButton.textContent = 'Save Occupation Title';
                submitButton.classList.add('submit-btn');
                submitButton.onclick = function () {
                    saveOccupation(textarea.value);
                };

                // Replace the current occupation title with the textarea and submit button
                occupationTextElement.innerHTML = '';
                occupationTextElement.appendChild(textarea);
                occupationTextElement.appendChild(submitButton);
            }

            // Save occupation title functionality
            function saveOccupation(newOccupation) {
                fetch('update-profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `field=occupation_title&value=${encodeURIComponent(newOccupation)}` +
                        '&csrf_token=' + encodeURIComponent(CSRF_TOKEN)
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        document.getElementById('occupation-title').innerText = newOccupation; // Update the occupation title dynamically
                    } else {
                        alert('Error updating occupation title.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }


            // Edit bio functionality
            function editBio() {
                let bioTextElement = document.getElementById('bio-text');
                let originalBio = bioTextElement.innerText;

                // Create a textarea to edit bio
                let textarea = document.createElement('textarea');
                textarea.value = originalBio;
                textarea.classList.add('edit-textarea');

                // Create a submit button for saving the new bio
                let submitButton = document.createElement('button');
                submitButton.textContent = 'Save Bio';
                submitButton.classList.add('submit-btn');
                submitButton.onclick = function () {
                    saveBio(textarea.value);
                };

                // Replace the current bio text with the textarea and submit button
                bioTextElement.innerHTML = '';
                bioTextElement.appendChild(textarea);
                bioTextElement.appendChild(submitButton);
            }

            // Save bio functionality
            function saveBio(newBio) {
                fetch('update-profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `field=bio_text&value=${encodeURIComponent(newBio)}` +
                        '&csrf_token=' + encodeURIComponent(CSRF_TOKEN)
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        document.getElementById('bio-text').innerText = newBio; // Update the bio text dynamically
                    } else {
                        alert('Error updating bio.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            // Edit location state functionality
            function editLocation() {
                let locationTextElement = document.getElementById('location-text');
                let originalLocation = locationTextElement.innerText;

                // Create a dropdown for selecting a state
                const states = [
                    'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia',
                    'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts',
                    'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey',
                    'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
                    'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia',
                    'Wisconsin', 'Wyoming'
                ];

                let select = document.createElement('select');
                states.forEach(state => {
                    let option = document.createElement('option');
                    option.value = state;
                    option.textContent = state;
                    if (state === originalLocation) option.selected = true; // Pre-select the original location
                    select.appendChild(option);
                });

                // Create a submit button for saving the new location
                let submitButton = document.createElement('button');
                submitButton.textContent = 'Save Location';
                submitButton.classList.add('submit-btn');
                submitButton.onclick = function () {
                    saveLocation(select.value);
                };

                // Replace the current location text with the dropdown and submit button
                locationTextElement.innerHTML = '';
                locationTextElement.appendChild(select);
                locationTextElement.appendChild(submitButton);
            }

            // Save location functionality
            function saveLocation(newLocation) {
                fetch('update-profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `field=location_state&value=${encodeURIComponent(newLocation)}` +
                        '&csrf_token=' + encodeURIComponent(CSRF_TOKEN)
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        document.getElementById('location-text').innerText = newLocation; // Update the location dynamically
                    } else {
                        alert('Error updating location.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

        </script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>