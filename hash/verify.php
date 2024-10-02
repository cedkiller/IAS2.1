<?php
include('./config.php');
include('./functions.php');

session_start();

$username = $_SESSION['user_name'];
$password = $_SESSION['pass_word'];
$recaptcha_response = $_SESSION['recaptcha'];

if (!verifyRecaptcha($recaptcha_response)) {
    $_SESSION['message'] = "Invalid reCAPTCHA. Please try again.";
    header("location: register.php");
    exit();
}

$sql = "SELECT id FROM users WHERE username = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            $_SESSION['message'] = "This username is already taken.";
        } else {
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = "Registration successful. You can now log in.";
                    header("location: login.php");
                    exit();
                } else {
                    $_SESSION['message'] = "Something went wrong. Please try again later.";
                }
            }
        }

    } else {
        $_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
    }

    mysqli_stmt_close($stmt);
}
?>