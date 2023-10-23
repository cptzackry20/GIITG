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
                <a class="nav-link" href="managequiz.php">Manage quiz</a>
            </li>
            <?php
            // Check if the user is logged in as an admin
            if (isset($_SESSION['admin'])) {
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo 'Hello, ' . $_SESSION['admin']['username'];
                echo '</a>';
                echo '<div class="dropdown-menu" aria-labelledby="adminDropdown">';
                echo '<a class="dropdown-item" href="adminprofile.php">Admin Profile</a>';
                echo '<div class="dropdown-divider"></div>';
                echo '<a class="dropdown-item" href="adminlogout.php">Logout</a>';
                echo '</div>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</nav>
</body>
</html>
