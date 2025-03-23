<?php
    session_start();
    include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Category</title>
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

        <section id="create-category">
            <h2 id="create-category-title">Create Category</h2>
            <div class="create-category-container">
                <div id="errormessage" style="color:red;"></div>  <!-- This div will hold the error messages -->
                <form class="create-category-form" id="create-category-form" action="create-category-process.php" method="POST">
                    <label for="category-name">Category Name</label><br>
                    <input type="text" id="category-name" name="category-name" required> <br> <br>

                    <label for="category-description">Category Description</label><br>
                    <textarea id="category-description" name="category-description" rows="5" cols="50" required></textarea><br><br>

                    <button type="submit">Create Category</button> <br> <br>
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
            document.getElementById("create-category-form").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent default form submission

                var errormessage = "";
                var nameRegex = /^[A-Za-z0-9 ]{3,50}$/; // Category name validation (3-50 alphanumeric characters and spaces)

                var categoryName = document.getElementById("category-name").value.trim();
                var categoryDescription = document.getElementById("category-description").value.trim();

                // Validate category name
                if (!nameRegex.test(categoryName)) {
                    errormessage += "*Category Name must be 3-50 characters long and contain only letters, numbers, and spaces.<br>";
                }

                // Validate category description
                if (categoryDescription.length < 10) {
                    errormessage += "*Category Description must be at least 10 characters long.<br>";
                }

                // Display validation errors if any
                var messageBox = document.getElementById("errormessage");
                if (errormessage !== "") {
                    messageBox.innerHTML = errormessage;
                    messageBox.style.color = "red";
                    messageBox.style.display = "block";
                    return; // Stop further execution
                }

                // If validation passes, submit form via AJAX
                var formData = new FormData(this);

                fetch("create-category-process.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text()) // Expect plain text response
                .then(data => {
                    messageBox.style.display = "block";
                    if (data.includes("Category created successfully")) {
                        messageBox.style.color = "green";
                        messageBox.innerText = "Category created successfully!";
                        
                        window.location.href = "explore-thread-categories.php";
                        
                    } else {
                        messageBox.style.color = "red";
                        messageBox.innerText = data; // Show error message from PHP
                    }
                })
                .catch(error => console.error("Error:", error));
            });


        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>