<?php
// welcome.php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles to center the content vertically and horizontally */
        body, html {
            height: 100%;
        }

        .welcome-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: pink;
        }

        .welcome-card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: palevioletred;
            text-align: center;
        }

        .welcome-message {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .logout-button {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container welcome-container">
        <div class="welcome-card">
            <h1 class="welcome-message">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
            <a href="logout.php" class="btn btn-success logout-button">Sign Out of Your Account</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
