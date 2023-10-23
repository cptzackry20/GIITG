<?php
session_start();
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1);
include("../includes/config.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Use plain text password

    // Query to check if username and password match
    $ret = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' and password='$password'");

    if (!$ret) {
        die('Error: ' . mysqli_error($conn)); // Display any MySQL errors
    }

    $num = mysqli_num_rows($ret);

    if ($num > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        $row = mysqli_fetch_assoc($ret);
        $_SESSION['id'] = $row['id'];
        if ($username == 'superadmin') { // Redirect if superadmin
            header("Location: superadmin/dashboard2.php");
            exit();
        } else { // Redirect if not superadmin
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $_SESSION['errmsg'] = "Invalid username or password";
        header("Location: index.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        .uk-form-icon .uk-icon {
            color: #333;
        }
        
        .uk-password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="../style/uikit-rtl.min.css" />
    <link rel="stylesheet" href="../style/uikit.min.css" />
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../style/loginstyle.css" />
    
    <script src="../js/uikit.min.js"></script>
    <script src="../js/uikit-icons.min.js"></script>
    
</head>
<body>

<div uk-sticky="media: 960" class="uk-navbar-container tm-navbar-container uk-sticky uk-active" style="position: fixed; top: 0px; width: 1903px;">
    <div class="uk-container uk-container-expand">
        <nav uk-navbar>
            <div class="uk-navbar-left">
                <a href="#" class="uk-navbar-item uk-logo">
                    <img src="https://i.ibb.co/BsCvKCj/giitglogo-01.png" alt="GIITG Logo" style="height: 50px; margin-right: 10px;">
                    GIITG Admin
                </a>
            </div>
        </nav>
    </div>
</div>


    <div class="content-background">
        <div class="uk-section-large">
            <div class="uk-container uk-container-large">
                <div uk-grid class="uk-child-width-1-1@s uk-child-width-2-3@l">
                    <div class="uk-width-1-1@s uk-width-1-5@l uk-width-1-3@xl"></div>
                    <div class="uk-width-1-1@s uk-width-3-5@l uk-width-1-3@xl">
                        <div class="uk-card uk-card-default">

                            <div class="uk-card-body">
                                <center>
                                <img src="https://i.ibb.co/BsCvKCj/giitglogo-01.png" alt="GIITG Logo" style="width: 150px;">
                                    
                                </center>
                                <?php
                                if (isset($_SESSION['errmsg'])) {
                                    ?>
                                    <div class="uk-alert uk-alert-danger">
                                        <a class="uk-alert-close" uk-close></a>
                                        <p><?php echo htmlentities($_SESSION['errmsg']); ?></p>
                                    </div>
                                    <?php
                                    unset($_SESSION['errmsg']);
                                }
                                ?>
                                <form method="post">
                                    <fieldset class="uk-fieldset">
                                        <div class="uk-margin">
                                            <div class="uk-position-relative">
                                                <span class="uk-form-icon ion-android-person"></span>
                                                <input name="username" class="uk-input" type="text" placeholder="Username">
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <div class="uk-position-relative">
                                            <input name="password" id="password" class="uk-input" type="password" placeholder="Password">
                                                <span class="uk-password-toggle" onclick="togglePasswordVisibility()">
                                                    <span class="ion-eye" style="color: #333;"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <button type="submit" class="uk-button uk-button-primary" name="submit">
                                                <span class="ion-forward"></span>&nbsp; Login
                                            </button>
                                        </div>
                                        <hr />
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-1@s uk-width-1-5@l uk-width-1-3@xl"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var passwordToggleIcon = document.querySelector(".uk-password-toggle .ion-eye");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordToggleIcon.classList.remove("ion-eye");
                passwordToggleIcon.classList.add("ion-eye-disabled");
                
                setTimeout(function() {
                    passwordField.type = "password";
                    passwordToggleIcon.classList.remove("ion-eye-disabled");
                    passwordToggleIcon.classList.add("ion-eye");
                }, 3000);
            }
        }
    </script>
</body>
</html>