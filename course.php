<?php
// Include the necessary files (config and start session)
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

// Check if the user is logged in as staff
if (isset($_SESSION['staff_id'])) {
    $staffId = $_SESSION['staff_id'];

    // Fetch the staff's department from the staff table
    $departmentQuery = "SELECT department_id FROM staff WHERE id = $staffId";
    $departmentResult = $conn->query($departmentQuery);

    if ($departmentResult && $departmentResult->num_rows > 0) {
        $departmentRow = $departmentResult->fetch_assoc();
        $staffDepartmentId = $departmentRow['department_id'];

        // Query to fetch courses based on the staff's department
        $coursesQuery = "SELECT c.* FROM course c
                        INNER JOIN department_courses dc ON c.id = dc.course_id
                        WHERE dc.department_id = $staffDepartmentId";

        $coursesResult = $conn->query($coursesQuery);
    }
} else {
    // If the user is not logged in as staff, show all courses
    $coursesQuery = "SELECT * FROM course";
    $coursesResult = $conn->query($coursesQuery);
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

<body>
    <?php include 'includes/navbar.php'; ?>

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
            if ($coursesResult && $coursesResult->num_rows > 0) {
                while ($row = $coursesResult->fetch_assoc()) {
                    $courseId = $row['id'];
                    $courseName = $row['name'];
                    $courseImg = $row['img'];

                    $imageType = getImageType($courseImg);

                    if ($imageType !== null) {
                        echo '<div class="col-lg-3 col-md-6">';
                        echo '<button type="button" data-toggle="modal" data-target="#Modal' . $courseId . '">';
                        echo '<div class="course" style="background-image: url(' . $courseImg . ');">';
                    } else {
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
            <div class="modal fade" id="Modal<?php echo $courseId; ?>" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?php echo $courseName; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="lesson">
                                <?php
                                $lessonQuery = "SELECT * FROM lesson WHERE course_id = $courseId";
                                $lessonResult = $conn->query($lessonQuery);

                                if ($lessonResult && $lessonResult->num_rows > 0) {
                                    while ($lessonRow = $lessonResult->fetch_assoc()) {
                                        $lessonName = $lessonRow['name'];
                                        $lessonLink = $lessonRow['link'];
                                ?>
                                <p><a href="courses/<?php echo $lessonLink; ?>"><?php echo $lessonName; ?></a></p>
                                <?php
                                    }
                                } else {
                                    echo '<p>Sorry, there are no available lessons for this course right now.</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="staffpreviewcourse.php?course_id=<?php echo $courseId; ?>"
                                class="btn btn-primary">Preview Course</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-lg-12 text-center">Sorry, there are no available courses right now.</div>';
            }
            ?>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>
    
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
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
