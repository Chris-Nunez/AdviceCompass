<?php
session_start();
include 'config.php';

// Check token from URL or session
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $query = $conn->prepare("SELECT User_ID, Expiration FROM PasswordResets WHERE Token = ?");
    $query->bind_param("s", $token);
    $query->execute();
    $query->bind_result($user_id, $expiration);
    $query->fetch();
    $query->close();

    if (!$user_id || strtotime($expiration) < time()) {
        die("Token is invalid or expired.");
    }

    // Store token and user ID in session
    $_SESSION['reset_token'] = $token;
    $_SESSION['reset_user_id'] = $user_id;
} else {
    // If token is missing, prevent form access
    if (!isset($_SESSION['reset_token'])) {
        die("Access denied.");
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
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

        <section id="reset-password">
            <h2 id="reset-password-title">Reset Your Password</h2>
            <div class="reset-password-container">
                <form class="reset-password-form" action="reset-password-process.php" method="POST">
                    
                    <label for="password">New Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <p>*Must be at least 8 characters long</p>
                    <p>*Must include at least one letter</p>
                    <p>*Must include at least one number</p>
                    <p>*Must include at least one special character</p>
                    
                    <button type="submit">Reset Password</button>
                </form>
            </div>
        </section>

        <script>

            if (window.location.search.includes("token=")) {
                const url = new URL(window.location.href);
                url.searchParams.delete("token");
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

            document.querySelector(".reset-password-form").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent form from submitting normally

                var errormessage = "";

                var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;

                var password = document.getElementById('password').value;

                // Validate new password
                if (!passwordRegex.test(password)) {
                    errormessage += "*Password must contain at least 8 characters, including letters, numbers and at least one special character<br>";
                }

                // If there are validation errors, display them and stop submission
                var messageBox = document.getElementById("errormessage");

                if (errormessage !== "") {
                    if (!messageBox) {
                        var newBox = document.createElement("div");
                        newBox.id = "errormessage";
                        newBox.style.color = "red";
                        newBox.style.display = "block";
                        document.querySelector(".reset-password-form").prepend(newBox);
                    }

                    document.getElementById("errormessage").innerHTML = errormessage;
                    return;
                }

                // If validation passes, submit the form
                this.submit();
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="..." crossorigin="anonymous"></script>

    </body>
</html>
