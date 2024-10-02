<?php
include('./config.php');
include('./functions.php');

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];
    if (!verifyRecaptcha($recaptcha_response)) {
        $_SESSION['message'] = "Invalid reCAPTCHA. Please try again.";
        header("location: login.php");
        exit();
    }
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {
                        session_start();
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        header("location: welcome.php");
                    } else {
                        $_SESSION['message'] = "Invalid password.";
                    }
                }
            } else {
                $_SESSION['message'] = "Invalid username.";
            }
        } else {
            $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Custom styles to center the form */
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            margin-top: 50px;
        }

        .card {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <h2 class="text-center mb-4">Login</h2>
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php 
                                echo $_SESSION['message']; 
                                unset($_SESSION['message']); 
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Email</label>
                            <input type="email" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <center><div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div></center>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <p class="mt-3 text-center">Don't have an account? <a href="register.php">Sign up now</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
