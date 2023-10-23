<?php
session_start();
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1);
include("includes/config.php");

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $ret = mysqli_query($conn, "SELECT * FROM staff WHERE email='$email' AND password='$password'");
    if (!$ret) {
        die('Error: ' . mysqli_error($conn));
    }

    $num = mysqli_fetch_array($ret);

    if ($num > 0) {
        $_SESSION['user'] = $num;
        $_SESSION['staff_id'] = $num['id']; // Set the staff_id in the session
        $_SESSION['staff_name'] = $num['name']; // Set the user's name in the session
        // Redirect to index.php or any other page
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['errmsg'] = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizller</title>
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
    <!-- Header -->
    <header class="header1">
        <!-- Header desktop -->
        <div class="container-menu-header">
            <div class="wrap_header">
                <!-- Logo -->
                <a href="index.php" class="logo">
                    <img src="img/logo.png" alt="IMG-LOGO">
                </a>
            </div>
        </div>

        <!-- Header Mobile -->
        <div class="wrap_header_mobile">
            <!-- Logo moblie -->
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
                            Staff Login
                        </span>

                        <form method="post">
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="email" type="text" placeholder="Email" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
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
                                    Login
                                </button>
                            </div>
                        </form>

                        <!-- Display an error message if login fails -->
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
