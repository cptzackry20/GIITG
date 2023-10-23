<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>GIITG</title>
    <link rel="stylesheet" href="style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
<?php include 'includes/navbar.php'; ?>

    <div id="fullpage">
        <div class="section web-header">
            <div class="header-container">
                <div class="header-content">
                    <h3>Welcome To Gigi Coffee</h3>
                    <h1>IT Interactive Training Guide</h1>
                    <p>Learn and Success
                    </p>
                    <a href="course.php">Go to Ebook</a>
                </div>
            </div>
        </div>

        <section class="section section1">
            <div class="info">
                <h1>A Platform for new employee To Study about IT</h1>
                <a href="" class="button">Learn More</a>
            </div>
        </section>

        <section class="section section2">
            <div class="section-container">
                <div class="info">
                    <h1>A Platform for Tutors To Teach</h1>
                    <a href="" class="button">Learn More</a>
                </div>
            </div>
        </section>
        <section class="section fp-auto-height">
            <div class="section-container" id="footer">
                <div class="icon">
                    <a target="_blank" href="mailto: info@gigicoffee.com">
                        <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fas fa-envelope fa-stack-1x white"></i>
                        </span>
                    </a>
                    <a target="_blank" href="https://www.instagram.com/gigicoffeemy//">
                        <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-instagram fa-stack-1x white"></i>
                        </span>
                    </a>
                    <a target="_blank" href="https://twitter.com/gigicoffeemy">
                        <span class="fa-stack fa-lg">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-twitter fa-stack-1x white"></i>
                        </span>
                    </a>
                </div>
                <p>GIITG &copy;2023 BY GIGI COFFEE SDN BHD. ALL RIGHT RESERVED..</p>
            </div>
        </section>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.js"
        integrity="sha512-Gx/C4x1qubng2MWpJIxTPuWch9O88dhFFfpIl3WlqH0jPHtCiNdYsmJBFX0q5gIzFHmwkPzzYTlZC/Q7zgbwCw=="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
