<?php


// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Get the user type and name
    $userType = $_SESSION['user']['user_type'];
    $userName = $_SESSION['user']['name'];
    $loggedIn = true;  // Define $loggedIn for use in conditional statements
} else {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit;
    
}
// Function to get the image file type
function getImageType($url)
{
    $imageInfo = getimagesize($url);
    if ($imageInfo !== false) {
        $mimeType = $imageInfo['mime'];
        if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg' || $mimeType === 'image/png') {
            return $mimeType;
        }
    }
    return null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>GIITG</title>
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="../style/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(window).scroll(function () {
                var scroll = $(window).scrollTop();
                if (scroll > 50) {
                    $("#web-navbar").addClass("scroll-bg");
                } else {
                    $("#web-navbar").removeClass("scroll-bg");
                }
            });
        });
    </script>
</head>
<body>
<nav id="web-navbar" class="navbar navbar-expand-lg navbar-dark fixed-top py-2">
    <a class="navbar-brand" href="index.php"><img src="https://i.ibb.co/BsCvKCj/giitglogo-01.png" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'index.php') !== false) echo 'active'; ?>">
    <a class="nav-link" href="index.php">Home</a>
</li>

            
            <?php
            if ($loggedIn) {
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="coursesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo 'Courses';
                echo '</a>';
                echo '<div class="dropdown-menu" aria-labelledby="coursesDropdown">';
                echo '<a class="dropdown-item" href="course.php">All Courses</a>';
                echo '<a class="dropdown-item" href="staff/mycourse.php">My Courses</a>';
                echo '</div>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="feedback.php">Feedback</a>';
                echo '</li>';
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo 'Hello, ' . $userName;  // Corrected here
                echo '</a>';
                echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                echo '<a class="dropdown-item" href="staff/detailsstaff.php">Profile</a>';
                echo '<div class="dropdown-divider"></div>';
                echo '<a class="dropdown-item" href="logout.php">Logout</a>';
                echo '</div>';
                echo '</li>';
            } else {
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="course.php">Courses</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a class="nav-link" href="login.php">Login</a>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if (scroll > 50) {
                $("#web-navbar").addClass("scroll-bg");
            } else {
                $("#web-navbar").removeClass("scroll-bg");
            }
        });
    });
</script>
</body>
</html>
