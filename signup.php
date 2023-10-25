<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO staff (code, name, email, password) VALUES ('$code', '$name', '$email', '$password')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Error: ' . mysqli_error($conn));
    }

    // Redirect to login page after successful registration
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Signup</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style/header.css">
    <link rel="stylesheet" type="text/css" href="style/util.css">
    <link rel="stylesheet" type="text/css" href="style/login.css">
    <link rel="stylesheet" type="text/css" href="js/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="js/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="js/jquery/jquery-3.2.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/tilt/tilt.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
</head>

<body>
    <header class="header1">
        <div class="container-menu-header">
            <div class="wrap_header">
                <a href="index.php" class="logo">
                    <img src="img/logo.png" alt="IMG-LOGO">
                </a>
            </div>
        </div>
        <div class="wrap_header_mobile">
            <a href="index.php" class="logo-mobile">
                <img src="img/favicon.png" alt="IMG-LOGO">
            </a>
        </div>
    </header>

    <section>
        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">
                    <div class="login100-pic js-tilt" data-tilt>
                        <img src="img/favicon.png" alt="IMG">
                    </div>
                    <div class="login100-form validate-form">
                        <span class="login100-form-title">
                            Staff Signup
                        </span>
                        <form method="post">
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="code" type="text" placeholder="Code" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-id-card" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="name" type="text" placeholder="Name" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="email" type="text" placeholder="Email" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="password" id="password" type="password" placeholder="Password" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="container-login100-form-btn">
                                <button type="submit" class="login100-form-btn" name="submit">
                                    Sign Up
                                </button>
                            </div>
                            <br>
                            <a href="login.php" class="login-link" style="margin-left: 55px;">Already have an account? Login now</a>
                        </form>
                        <?php if (isset($_SESSION['errmsg'])) : ?>
                            <div class="uk-alert-danger" uk-alert>
                                <a class="uk-alert-close" uk-close></a>
                                <p><?php echo $_SESSION['errmsg']; ?></p>
                            </div>
                        <?php unset($_SESSION['errmsg']);
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        });
    </script>
</body>
</html>
