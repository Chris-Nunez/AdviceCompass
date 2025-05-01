<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>

        <nav class="navbar navbar-expand-md fixed-top" style="background-color: #303030;">
            <div class="container">
                <a href="#home" class="navbar-brand text-white">
                    <h1 class="text-white mb-0">AdviceCompass</h1>
                </a>
        
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav-collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="nav-collapse">
                    <div class="navbar-nav ms-auto text-center text-md-end mobile-nav-buttons">
                        <a href="login.php">
                            <button class="navbar-login-button me-md-4 mb-2 mb-md-0">Login</button>
                        </a>
                        <a href="register.php">
                            <button class="navbar-signup-button">Sign Up</button>
                        </a>
                        <a href="help.php">
                            <i class="bi bi-question-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <section id="login">
            <h2 id="login-title">Login</h2>
            <div class="login-container">
                <div id="errormessage" style="color:red;">
                    <?php
                        if (isset($_GET['error']) && $_GET['error'] == 1) {
                            echo "Please Login.";
                        }
                    ?>
                </div>  
                <form class="login-form" id="login-form" action="login-process.php" method="POST">
                    <label for="email">Email</label><br>
                    <input type="text" id="email" name="email" required> <br> <br>

                    <label for="password">Password</label><br>
                    <input type="password" id="password" name="password" required> <br> <br>

                    <button type="submit">Login</button> <br> <br>
                    <a href="forgot-password-email.php" id="forgot-password-link">Forgot Password?</a>
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

            if (window.location.search.includes("error=")) {
                const url = new URL(window.location.href);
                url.search = ""; // Remove all query parameters
                window.history.replaceState({}, document.title, url.pathname);
            }

            const navCollapse = document.getElementById('nav-collapse');
            const navbar = document.querySelector('.navbar');

            navCollapse.addEventListener('show.bs.collapse', () => {
                navbar.classList.add('expanded');
            });

            navCollapse.addEventListener('hide.bs.collapse', () => {
                navbar.classList.remove('expanded');
            });

            
            document.getElementById("login-form").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent default form submission

                var errormessage = "";
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email pattern

                var email = document.getElementById("email").value;
                var password = document.getElementById("password").value;

                if (!emailRegex.test(email)) {
                    errormessage += "*Invalid Email format<br>";
                }

                // If there are validation errors, display them and stop submission
                var messageBox = document.getElementById("errormessage");
                if (errormessage !== "") {
                    messageBox.innerHTML = errormessage;  // Display error messages
                    messageBox.style.color = "red";
                    messageBox.style.display = "block";
                    return;  
                }

                // If validation passes, submit form via AJAX
                var formData = new FormData(this);

                fetch("login-process.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text()) // Expect plain text response
                .then(data => {
                    messageBox.style.display = "block";

                    if (data.includes("Login successful")) {
                        messageBox.style.color = "green";
                        messageBox.innerText = "Login successful! Redirecting...";
                        window.location.href = "home.php";
                    } 
                    else {
                        messageBox.style.color = "red";
                        messageBox.innerText = "Invalid Login"; // Show error message from PHP
                    }
                })
                .catch(error => console.error("Error:", error));
            });

        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
