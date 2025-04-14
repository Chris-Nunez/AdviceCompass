<?php
    include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
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
                        <a href="index.html">
                            <button class="navbar-login-button me-4">Login</button>
                        </a>
                        <a href="register.html">
                            <button class="navbar-signup-button">Sign Up</button>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <section id="forgot-password">
            <h2 id="forgot-password-title">Forgot Password</h2>
            <div class="forgot-password-container">
                <div id="errormessage" style="color:red;"></div>  <!-- This div will hold the error messages -->
                <form class="forgot-password-form" id="forgot-password-form" action="forgot-password-email-process.php" method="POST">

                    <label for="email">Email</label><br>
                    <input type="text" id="email" name="email" required> <br> <br>

                    <button type="submit">Send Forgot Password Link</button> <br> <br>
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
            document.getElementById("forgot-password-form").addEventListener("submit", function(event) {
                var errormessage = "";
                var emailRegex = /^[A-Za-z0-9._%+-]+@[a-z]+\.[a-z]{2,3}$/;

                var email = document.getElementById("email").value;
                var messageBox = document.getElementById("errormessage");

                // Validate email
                if (!emailRegex.test(email)) {
                    errormessage += "*Invalid Email format<br>";
                }

                // If there are validation errors, display them and stop submission
                if (errormessage !== "") {
                    event.preventDefault(); // Only prevent if there's an error
                    messageBox.innerHTML = errormessage;
                    messageBox.style.color = "red";
                    messageBox.style.display = "block";
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>