<?php
// Start the session (if not started)
session_start();

if (isset($_SESSION['user'])) {
    $username = $_SESSION['staff_name'];
    $loggedIn = true;
} else {
    $loggedIn = false;

    // After successfully retrieving user data from the database
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();

        // Set the session variable based on user type
        if ($userData['user_type'] == 1) {
            // Normal staff
            $_SESSION['staff'] = $userData;
        } elseif ($userData['user_type'] == 2) {
            // Instructor
            $_SESSION['instructor'] = $userData;
        } elseif ($userData['user_type'] == 3) {
            // Admin
            $_SESSION['admin'] = $userData;
        }

        // ... (rest of your login code)
    }
}

// Now, in your navigation code, you can check the existence of these session variables
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
<nav id="web-navbar" class="navbar navbar-expand-lg navbar-dark fixed-top py-2">
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="managecourse.php">Manage Courses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="managelesson.php">Manage Lessons</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="managequiz.php">Manage Quiz</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="instuctormanagestaff.php">Manage Staff</a>
        </li>

        <?php
        if (isset($_SESSION['admin'])) {
            echo '<li class="nav-item dropdown">';
            echo '<a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            echo 'Hello, ' . $_SESSION['admin']['username'];
            echo '</a>';
            echo '<div class="dropdown-menu" aria-labelledby="adminDropdown">';
            echo '<a class="dropdown-item" href="adminprofile.php">Admin Profile</a>';
            echo '<div class="dropdown-divider"></div>';
            echo '<a class="dropdown-item" href="../logout.php">Logout</a>'; // Using the same logout for admin and instructor
            echo '</div>';
            echo '</li>';
        } elseif (isset($_SESSION['instructor'])) {
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="../logout.php">Logout</a>';
            echo '</li>';
        }
        ?>

        <li class="nav-item">
            <a class="nav-link" href="../index.php">Homepage</a>
        </li>
    </ul>
    </div>
</nav>
<script>
$(document).ready(function () {
    // Get the current page's URL
    var currentURL = window.location.href;

    // Iterate through each navigation item and check if its href matches the current URL
    $('#web-navbar .nav-item').each(function () {
        var link = $(this).find('.nav-link');
        var linkURL = link.attr('href');

        // If the current URL contains the link's URL, add the 'active' class
        if (currentURL.indexOf(linkURL) !== -1) {
            link.addClass('active');
        }
    });
});
</script>

</body>
</html>
