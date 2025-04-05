<?php
    session_start();
    include 'config.php';

    if (isset($_GET['category_id'])) {
        $category_id = $_GET['category_id'];
        echo "Category ID received: " . htmlspecialchars($_GET['category_id']);
    } 
    else {
        echo "No category selected.";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Thread</title>
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

        <section id="create-thread">
            <div class="top-container d-flex align-items-center justify-content-between">
                
                <!-- Left Section: Back & Create Category Buttons -->
                <div class="d-flex align-items-center flex-1">
                    <button class="explore-categories-back-button mx-2" onclick="history.back();">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                </div>

            </div>
            <h2 id="create-thread-title">Create Thread</h2>
            <div class="create-thread-container">
                <div id="errormessage" style="color:red;"></div> 
                <form class="create-thread-form" id="create-thread-form" action="create-thread-process.php" method="POST">
                    <label for="thread-title">Thread title</label><br>
                    <input type="text" id="thread-title" name="thread-title" required> <br> <br>

                    <label for="thread-text">Thread Text</label><br>
                    <textarea id="thread-text" name="thread-text" rows="5" cols="50" required></textarea><br><br>

                    <label for="thread-image">Upload Image (Optional)</label><br>
                    <input type="file" id="thread-image" name="thread-image" accept="image/*"><br><br>

                    <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category_id); ?>">

                    <button type="submit" id="create-thread-button">Create Thread</button> <br> <br>
                </form>               
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
            document.getElementById("create-thread-form").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent default form submission

                var errormessage = "";
                var nameRegex = /^[A-Za-z0-9 ]{3,50}$/; // Category name validation (3-50 alphanumeric characters and spaces)

                var threadTitle = document.getElementById("thread-title").value.trim();
                var threadText = document.getElementById("thread-text").value.trim();

                // Validate category name
                if (!nameRegex.test(threadTitle)) {
                    errormessage += "*Thread Title must be 3-50 characters long and contain only letters, numbers, and spaces.<br>";
                }

                // Validate category description
                if (threadText.length < 10) {
                    errormessage += "*Thread Text must be at least 10 characters long.<br>";
                }

                // Display validation errors if any
                var messageBox = document.getElementById("errormessage");
                if (errormessage !== "") {
                    messageBox.innerHTML = errormessage;
                    messageBox.style.color = "red";
                    messageBox.style.display = "block";
                    return; // Stop further execution
                }               

                this.submit();
            });

        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>