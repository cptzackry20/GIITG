<?php
// Include the navbar before starting the session
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

// Check if the user is logged in and has the necessary session variables
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'staff') {
    $staffDepartmentId = $_SESSION['department_id'];

    // Query to fetch courses from the database for the specific department
    $query = "SELECT * FROM course WHERE department_id = $staffDepartmentId";
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Courses</title>
    <link rel="stylesheet" href="style/course.css">
    <link rel="stylesheet" href="style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php include 'includes/navbar.php'; ?>

<body>
    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>COURSE</h1>
            </div>
        </div>
    </div>

    <section class="main-container">
        <h1>Choose Courses</h1>

        <div class="row">
            <?php
            // Check if there are any courses available for the staff member's department
            if (isset($result) && $result->num_rows > 0) {
                // Loop through the courses and display them
                while ($row = $result->fetch_assoc()) {
                    $courseId = $row['id'];
                    $courseName = $row['name'];
                    $courseImg = $row['img']; // Retrieve the image URL from the database

                    // Get the image type
                    $imageType = getImageType($courseImg);

                    // Check if the image type is supported (jpeg, jpg, or png)
                    if ($imageType !== null) {
                        echo '<div class="col-lg-3 col-md-6">';
                        echo '<button type="button" data-toggle="modal" data-target="#Modal' . $courseId . '">';
                        echo '<div class="course" style="background-image: url(' . $courseImg . ');">';
                    } else {
                        // Handle unsupported image types or show a default image
                        echo '<div class="col-lg-3 col-md-6">';
                        echo '<button type="button" data-toggle="modal" data-target="#Modal' . $courseId . '">';
                        echo '<div class="course default-image">';
                    }
                    ?>
                    <div class="course-container">
                        <p><?php echo $courseName; ?></p>
                    </div>
                </div>
                </button>
            </div>

            <!-- Modal for course -->
            <div class="modal fade" id="Modal<?php echo $courseId; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Customize your modal content here as per your requirements -->
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                // Display a message if there are no available courses for the staff member's department
                echo '<div class="col-lg-12 text-center">Sorry, there are no available courses for your department right now.</div>';
            }
            ?>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.js" integrity="sha512-Gx/C4x1qubng2MWpJIxTPuWch9O88dhFFfpIl3WlqH0jPHtCiNdYsmJBFX0q5gIzFHmwkPzzYTlZC/Q7zgbwCw==" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>
