<?php
include('./config.php');
include('./functions.php');

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require PHPMailer library files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $cpass = $_POST['c-password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    if ($cpass === $password) {
      $_SESSION['user_name'] = $username;
      $_SESSION['pass_word'] = $password;
      $_SESSION['recaptcha'] = $recaptcha_response;
  
      // Initialize PHPMailer
      $mail = new PHPMailer(true);
  
      try {
          // Server settings
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'aniamaesantos0@gmail.com'; // Replace with your email
          $mail->Password = 'eskmnqzpoblrpruw'; // Replace with your email password or app password
          $mail->SMTPSecure = 'ssl';
          $mail->Port = 465;
  
          // Sender and recipient settings
          $mail->setFrom('aniamaesantos0@gmail.com', 'IAS2.1'); // Replace with your email and name
          $mail->addAddress($username);
  
          // Email content settings
          $mail->isHTML(true);
          $mail->Subject = 'Verify Email';
          $mail->Body = '<div style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
              <p><b>Hello!</b></p>
              <p>You are receiving this email because we received a verification email request for your account.</p>
              <br>
              <div style="text-align: center;">
                  <a href="http://localhost/hash/verify.php" style="
                      background-color: #512da8;
                      color: #fff;
                      padding: 12px 40px;
                      font-size: 14px;
                      border-radius: 8px;
                      text-decoration: none;
                      font-weight: bold;
                      letter-spacing: 1px;
                      display: inline-block;
                      margin: 15px 0;">
                      Verify Email
                  </a>
              </div>
              <br>
              <p>If you did not request a verification email, no further action is required.</p>
              <hr style="border: none; border-top: 1px solid #ddd;">
              <footer style="text-align: center; margin-top: 20px;">
                  <p style="font-size: 12px; color: #999;">&copy; 2024 IAS2.1. All rights reserved.</p>
              </footer>
          </div>';
  
          // Send the email
          $mail->send();
  
          // Display success message
          echo "<script>alert('Please check your email to see the link for verification register!');</script>";
      } catch (Exception $e) {
          // Display error message if email fails to send
          echo "<script>alert('There was an error sending the email. Please try again later.');</script>";
      } 
    } else {
      echo '<script>alert("password do not match")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Custom styles to center the form */
        body {
            background-color: #f8f9fa;
        }

        .signup-container {
            margin-top: 50px;
        }

        .card {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container signup-container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <h2 class="text-center mb-4">Sign Up</h2>
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
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <label for="c-password" class="form-label">Confirm Password</label>
                            <input type="password" name="c-password" id="c-password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
