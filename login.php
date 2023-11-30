<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("includes/config.php");

if (isset($_POST['submit_staff'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM staff WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            if (password_verify($password, $storedPassword)) {
                $_SESSION['user'] = $row;

                // Redirect to profile page based on user type
                if ($row['user_type'] == 1) {
                    // Normal staff
                    header("Location: staff/detailsstaff.php?type=staff");
                } elseif ($row['user_type'] == 2) {
                    // Instructor
                    header("Location: staff/detailsstaff.php?type=instructor");
                } elseif ($row['user_type'] == 3) {
                    // Admin
                    header("Location: staff/detailsstaff.php?type=admin");
                }
                
                exit;
            } else {
                $_SESSION['errmsg'] = "Invalid email or password for staff";
            }
        } else {
            $_SESSION['errmsg'] = "Invalid email or password for staff";
        }

        $stmt->close();
    } else {
        echo "Statement preparation failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login GIITG</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style/header.css">
    <link rel="stylesheet" type="text/css" href="style/util.css">
    <link rel="stylesheet" type="text/css" href="style/login.css">
    <link rel="stylesheet" type="text/css" href="js/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="js/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                        <!-- Staff Login Form -->
                        <span class="login100-form-title">
                            Staff Login
                        </span>
                        <form method="post" id="staff-form">
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="email" type="text" placeholder="Email" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="wrap-input100 validate-input">
                                <input class="input100" name="password" id="password_staff" type="password" placeholder="Password" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="container-login100-form-btn">
                                <button type="submit" class="login100-form-btn" name="submit_staff">
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
        $(document).ready(function () {
            $('.js-tilt').tilt({
                scale: 1.1
            });
        });
    </script>
</body>

</html>