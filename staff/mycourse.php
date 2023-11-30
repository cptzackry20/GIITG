<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed


// Fetch staff details from the database based on the user's session information
$staffID = $_SESSION['user']['id']; // Assuming you store the user's ID in the session

// Query to fetch staff details based on the user's ID
$query = "SELECT * FROM staff WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Replace the following placeholders with actual data from the database
    $staffCode = $row['code'];
    $staffName = $row['name'];
    $staffEmail = $row['email'];
    $staffPosition = $row['position'];
    $staffImage = $row['dp'];

    // Now, let's fetch the courses enrolled by the staff member
    $coursesQuery = "SELECT course.* FROM coursetaken
                     INNER JOIN course ON coursetaken.course_id = course.id
                     WHERE coursetaken.staff_id = ?";

    $coursesStmt = $conn->prepare($coursesQuery);
    $coursesStmt->bind_param("i", $staffID);
    $coursesStmt->execute();
    $coursesResult = $coursesStmt->get_result();
} else {
    // Handle the case where the staff member's details are not found
    // You can redirect to an error page or display an error message
    echo "Staff details not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Profile Details</title>
    <link rel="stylesheet" href="../style/mycourse.css">
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<?php include '../includes/navbar2.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>My Course</h1>
                <div class="container">
                    <div class="course-container">
                        <?php
                            while ($course = $coursesResult->fetch_assoc()) {
                                // Display course details
                                echo '<div class="course-card">';
                                echo '<img src="../' . $course['img'] . '" alt="Course Image">';                                echo '<h2>' . $course['name'] . '</h2>';
                                echo '<p>' . $course['desc'] . '</p>';

                                // Add a "Start" button for each course with the class name
                                echo '<a class="start-button" href="coursetaken.php?course_id=' . $course['id'] . '">Continue</a>';

                                echo '</div>';
                            }
                        ?>

                    </div>
                   
                </div>
                
            </div>
            
        </div>
        <br>
    </div>

    <?php include('../includes/footer.php'); ?>
    
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