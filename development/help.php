<?php
    session_start();
    include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
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
                            <a href="login.php">
                                <button class="navbar-login-button me-4">Login</button>
                            </a>
                            <a href="register.php">
                                <button class="navbar-signup-button">Sign Up</button>
                            </a>
                            <a href="help.php">
                                <i class="bi bi-question-circle"></i>
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

        <div class="main px-5">
            
            <div class="top-container d-flex align-items-center justify-content-between">
                    
                <!-- Left Section: Back & Create Category Buttons -->
                <div class="d-flex align-items-center flex-1">
                    <button class="explore-categories-back-button mx-2" onclick="history.back();">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                </div>

            </div>

            <div class="help-title">
                <h1>User Manual</h1>
            </div>

            <div class="toc-section">
                <div class="toc-card">
                    <h2 class="toc-title">📘 Table of Contents</h2>
                    <ul class="toc-list">
                        <li><a href="#getting-started">Getting Started</a></li>
                        <li><a href="#help-forgot-password">Forgot Password</a></li>
                        <li><a href="#help-navbar">Navbar</a></li>
                        <li><a href="#help-profile">View Profile</a></li>
                        <li><a href="#help-settings">Settings</a></li>
                        <li><a href="#help-home">Home</a></li>
                        <li><a href="#help-explore-categories">Explore Categories</a></li>
                        <li><a href="#help-create-category">Create Category</a></li>
                        <li><a href="#help-thread-category">Thread Category</a></li>
                        <li><a href="#help-create-thread">Create Thread</a></li>
                        <li><a href="#help-thread">Thread</a></li>
                        <li><a href="#help-favorite-categories">Favorite Categories</a></li>
                        <li><a href="#help-favorite-threads">Favorite Threads</a></li>
                        <li><a href="#help-view-followers">Followers</a></li>
                        <li><a href="#help-view-following">Following</a></li>
                        <li><a href="#help-following-threads">Following Thread</a></li>
                        <li><a href="#help-create-thread">Create Thread</a></li>
                        <li><a href="#help-search-users">Search Users</a></li>
                    </ul>
                </div>
            </div>

            <div class="help-section" id="getting-started">
                <div class="help-title">
                    <h3>Getting Started</h3>
                </div>
                <div class="help-text">
                    <p>
                        Welcome to AdviceCompass! This user manual will guide you through the features and functionalities of our platform.
                        To get started, you need to create an account. Click on the "Sign Up" button on the top right corner of the page found in the navbar and fill in the required information below.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Registration-Page.png" alt="Registration Page">
                </div>
                <div class="help-text">
                    <p>
                        Once you have created an account, you can log in using your email and password.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Login-Page.png" alt="Login Page">
                </div>
            </div>

            <div class="help-section" id="help-forgot-password">
                <div class="help-title">
                    <h3>Forgot Password</h3>
                </div>
                <div class="help-text">
                    <p>
                        If you forget your password, you can reset it by clicking on the "Forgot Password?" link on the login page. 
                        You will be prompted to enter your email address and a link to reset your password will be sent to your email.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Forgot-Password-Email-Page.png" alt="Forgot Password Email Page">
                </div>
                <div class="help-text">
                    <p>
                        After clicking the link in your email, you will be taken to a page where you can enter a new password. 
                        Once you have entered your new password, click on the "Reset Password" button to save it.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Reset-Password-Page.png" alt="Reset Password Page">  
                </div>
            </div>

            <div class="help-section" id="help-navbar">
                <div class="help-title">
                    <h3>Navbar</h3>
                </div>
                <div class="help-text">
                    <p>
                        After logging in, you will be directed to the home page. From here, you will now see that in the navbar you can
                        access your profile, settings, and logout options. To view your profile, click on the user icon or the username in the navbar.
                        The gear icon will take you to your settings page. The logout button will bring you back to the login page.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Navbar.png" alt="Navbar after logging in">
                </div>
            </div>

            <div class="help-section" id="help-profile">
                <div class="help-title">
                    <h3>View Profile</h3>
                </div>
                <div class="help-text">
                    <p>
                        On your profile page, you can view your personal information, including your username, first and last name, occupation title, profile image, location and bio. 
                        You can also see how many followers you have, how many people you follow, and threads you have created. To edit your profile information, each section has an edit button under it, as well as
                        buttons to view your followers and the people you follow. You can also view the threads you have created and see how many comments you've made to show your activity on the app.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Profile-Page.png" alt="Profile Page for logged in user">
                </div>
                <div class="help-text">
                    <p>
                        Alternatively, you can view other users' profiles by clicking on their usernames or profile images on other pages. 
                        This will take you to their profile page where you can see their information and threads as seen below.
                        You can also follow and unfollow them from their profile page using the follow button at the bottom of their profile.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Profile-Page-2.png" alt="Profile Page for other user">
                </div>
            </div>

            <div class="help-section" id="help-settings">
                <div class="help-title">
                    <h3>Settings</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the settings page, there are buttons to access your profile, view your followers, view the people you follow, 
                        search for other users and their profiles, view your favorite categories, view your favorite threads, and also logout.
                        The logout functionality can also be accessed from the logout button in the navbar.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Settings-Page.png" alt="Settings Page">
                </div>
            </div>

            <div class="help-section" id="help-home">
                <div class="help-title">
                    <h3>Home Page</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the home page, you have several sections you can navigate to. The section on the top left is the explore page which will
                        bring you to all the categories that exist on the platform. It will display the 4 most recent categories made and there will be a button
                        to bring all the categories into view. The next section on the right will show the 4 most recent categories that you've favorited and have 
                        a button to view all of them. The next section is similar to the previous one, but will show the threads that you've favorited. 
                        The last section will show the threads of the users that you follow, essentially helping to tailor to what you like to see.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Home-Page.png" alt="Home page">
                </div>
            </div>

            <div class="help-section" id="help-explore-categories">
                <div class="help-title">
                    <h3>Explore Categories</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the explore categories page, you can view all the categories that exist on the platform. You can also create a new category by clicking on the button at the top left corner of the page.
                        Each category will show how many threads exist in it. You can also view the threads in each category by clicking on the button below each category.
                        There is also a back button to the left of the create category button that will bring you to the previous page.
                        You can also filter the categories by name using the search bar in the top right corner.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Explore-Categories-Page.png" alt="Explore Categories Page">
                </div>
            </div>

            <div class="help-section" id="help-create-category">
                <div class="help-title">
                    <h3>Create Category</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the create category page, you can create a new category by filling in the required information.
                        You will need to provide a name for the category, along with a description for it. 
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Create-Category-Page.png" alt="Create Category Page">
                </div>
            </div>

            <div class="help-section" id="help-thread-category">
                <div class="help-title">
                    <h3>Thread Category</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the thread category page, you can view all the threads that exist in the category and click on them to view them individually. 
                        You can also create a new thread by clicking on the button at the top left corner of the page.
                        There is also a back button to the left of the create thread button that will bring you to the previous page.
                        You can also favorite a category and unfavorite it by clicking the favorite button in the top left corner of the page.
                        You can also filter the threads by name using the search bar in the top right corner.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Thread-Category-Page.png" alt="Thread Category Page">
                </div>
            </div>

            <div class="help-section" id="help-create-thread">
                <div class="help-title">
                    <h3>Create Thread</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the create thread page, you can create a new thread by filling in the required information.
                        You will need to provide a title for the thread, along with a description for it and you have the
                        option to upload an image in your thread. 
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Create-Thread-Page.png" alt="Create Thread Page">
                </div>
            </div>

            <div class="help-section" id="help-thread">
                <div class="help-title">
                    <h3>Thread</h3>
                </div>
                <div class="help-text">
                    <p>
                        On the thread page, you can view the thread and all the comments that exist in it.
                        There will be a back button and a favorite button in the top left corner of the page. 
                        There is also a delete thread button that will only appear if you are the creator of the thread being viewed.
                        The initial thread will be displayed at the top and all of its comments are indented below it.
                        Each comment will also have its own section full of the replies to that particular comment, which can be viewed
                        by clicking view replies. 
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Thread-Page.png" alt="Thread Page">
                </div>
                <div class="help-text">
                    <p>
                        In order to make a comment on the thread, or make a reply to a comment, you can click the 
                        chat bubble icon on either the thread or comment to bring up the text box and submit. You may also like and dislike threads, comments
                        and replies by clicking the thumbs up or thumbs down icons.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Thread-Page-Comment-Box.png" alt="Thread Page with comment box">
                </div>
            </div>

            <div class="help-section" id="help-favorite-categories">
                <div class="help-title">
                    <h3>Favorite Categories</h3>
                </div>
                <div class="help-text">
                    <p>
                        This page will show all of the categories that you have favorited from the explore page.
                        It has the same layout and functionality as the explore categories page, 
                        but will only show the categories that you have favorited. The search bar will only filter categories you have favorited.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Favorite-Categories-Page.png" alt="Favorite Categories Page">
                </div>
            </div>

            <div class="help-section" id="help-favorite-threads">
                <div class="help-title">
                    <h3>Favorite Threads</h3>
                </div>
                <div class="help-text">
                    <p>
                        This page will show all of the threads that you have favorited from the thread category page.
                        It has the same layout and functionality as the thread category page, 
                        but will only show the threads that you have favorited. Each thread will also say what category it is from since there will
                        be a mix of them. The search bar will only filter threads you have favorited.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Favorite-Threads.png" alt="Favorite Threads Page">
                </div>
            </div>

            <div class="help-section" id="help-view-followers">
                <div class="help-title">
                    <h3>View Followers</h3>
                </div>
                <div class="help-text">
                    <p>
                        This page will show all of the users that follow you. It will display boxes that have each follower's username and profile picture.
                        From these boxes, you will be able to go view that follower's profile. The search bar will only filter users that follow you.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/View-Followers-Page.png" alt="Followers Page">
                </div>
            </div>

            <div class="help-section" id="help-view-following">
                <div class="help-title">
                    <h3>View Following</h3>
                </div>
                <div class="help-text">
                    <p>
                        This page will show all of the users that you follow. It will display boxes that have each user's username and profile picture.
                        From these boxes, you will be able to go view that user's profile. The search bar will only filter users that you follow.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/View-Following-Page.png" alt="Following Page">
                </div>
            </div>

            <div class="help-section" id="help-following-threads">
                <div class="help-title">
                    <h3>Following Threads</h3>
                </div>
                <div class="help-text">
                    <p>
                        This page will show all of the threads posted by the users you follow. It will display boxes that have each thread's title and category.
                        From these boxes, you will be able to go view that thread. 
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Following-Threads-Page.png" alt="Following Threads Page">
                </div>
            </div>

            <div class="help-section" id="help-search-users">
                <div class="help-title">
                    <h3>Search Users</h3>
                </div>
                <div class="help-text">
                    <p>
                        This page will allow you to search for other users on the platform using the search bar. 
                        It will display boxes that have each user's username and profile picture.
                        From these boxes, you will be able to go view that user's profile.
                    </p>
                </div>
                <div class="help-image">
                    <img src="help-images/Search-Users-Page.png" alt="Search Users Page">
                </div>
            </div>

        </div>


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
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>