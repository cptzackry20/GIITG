<?php

include 'includes/config.php';

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

// Query to fetch feedback along with staff information from the database
$query = "SELECT feedback.*, staff.name AS staff_name, staff.dp AS staff_dp FROM feedback
          INNER JOIN staff ON feedback.staff_id = staff.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Feedback</title>
    <link rel="stylesheet" href="style/course.css"> <!-- Use your existing CSS -->
    <link rel="stylesheet" href="style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .comments-container {
            display: flex;
            overflow: hidden;
            white-space: nowrap;
        }

        .comment {
            display: inline-block;
            width: 33.33%; /* Display three comments per page */
            white-space: normal;
        }
    </style>
</head>

<?php include 'includes/navbar.php'; ?>
<body>
    <!-- Your HTML content -->
    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Feedback</h1>
            </div>
        </div>
    </div>

    <section class="main-container">
        <h1>View Feedback</h1>

        <!-- Display existing comments in a horizontal arrangement -->
        <div class="comments-container">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $feedbackComment = $row['comment'];
                    $staffName = $row['staff_name'];
                    $staffDP = $row['staff_dp'];
            ?>
            <div class="comment">
                <div class="feedback">
                    <div class="comment">
                        <img src="<?php echo $staffDP; ?>" alt="Staff DP">
                        <p><strong><?php echo $staffName; ?>:</strong> <?php echo $feedbackComment; ?></p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-lg-12 text-center">Sorry, there is no feedback available right now.</div>';
            }
            ?>
        </div>

        <!-- Comment submission form -->
        <div class="row">
            <div class="col-lg-12">
                <form action="submit_feedback.php" method="post">
                    <h2>Submit Your Comment</h2>
                    <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>

    <script>
        $(document).ready(function () {
            // Set the initial slide to display
            var currentIndex = 0;
            var comments = $(".comment");

            // Function to show the next three comments
            function showNextSlide() {
                comments.eq(currentIndex).css("display", "none");
                currentIndex = (currentIndex + 3) % comments.length;
                comments.slice(currentIndex, currentIndex + 3).css("display", "inline-block");
            }

            // Set an interval to switch slides every 5 seconds
            setInterval(showNextSlide, 5000);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.js"
        integrity="sha512-Gx/C4x1qubng2MWpJIxTPuWch9O88dhFFfpIl3WlqH0jPHtCiNdYsmJBFX0q5gIzFHmwkPzzYTlZC/Q7zgbwCw=="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>