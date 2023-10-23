<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Side Bar</title>
</head>
<body>

<div id="sidebar-wrapper">
    <div id="sidebar">
        <div id="sidebar-title">
            <h3>SideBar</h3>
        </div>

        <ul id="list">
            <li class="list-items"><i class="fa fa-home" aria-hidden="true"></i>Home</li>
            <li class="list-items"><i class="fa fa-user" aria-hidden="true"></i>About Me</li>
            <li class="list-items"> <i class="fa fa-camera-retro" aria-hidden="true"></i>Portfolio</li>
            <li class="list-items"> <i class="fa fa-envelope-open-o" aria-hidden="true"></i>Contact Me</li>
        </ul>

        <div id="profile">
            <img src="<?php echo $staffImage; ?>" alt="Profile Picture">
            <h4><?php echo $staffName; ?></h4>
        </div>
    </div>
</div>

</body>
</html>
