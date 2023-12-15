<?php

// Include the configuration file and start the session (if not already included/started)
require_once '../includes/config.php';

if (isset($_SESSION['user'])) {
    $userData = $_SESSION['user'];
} else {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit;
}

function getUserTypeString($userType) {
    switch ($userType) {
        case 1:
            return 'Staff';
        case 2:
            return 'Instructor';
        case 3:
            return 'Admin';
        default:
            return 'Unknown';
    }
}
$staffID = $_SESSION['user']['id'];

$query = "SELECT id, code, name, email, password, position, dp, user_type, department_id FROM staff WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffName = $row['name'];
    $staffEmail = $row['email'];
    $staffImage = $row['dp'];
    $staffPosition = $row['position'];
    $staffCode = $row['code'];
    $userType = $row['user_type'];
    $departmentId = $row['department_id'];

    $departmentQuery = "SELECT name FROM department WHERE id = ?";
    $departmentStmt = $conn->prepare($departmentQuery);
    $departmentStmt->bind_param("i", $departmentId);
    $departmentStmt->execute();
    $departmentResult = $departmentStmt->get_result();

    $departmentName = "Unknown Department"; // Default value if the department is not found

    if ($departmentResult && $departmentResult->num_rows > 0) {
        $departmentRow = $departmentResult->fetch_assoc();
        $departmentName = $departmentRow['name'];
    }
    // Determine the user type and set variables accordingly
    $userTypeText = '';
    $dashboardLink = '';

    if ($userType == 1) {
        $userTypeText = 'Staff';
    } elseif ($userType == 2) {
        $userTypeText = 'Instructor';
        $dashboardLink = '../instructor/dashboard.php';
    } elseif ($userType == 3) {
        $userTypeText = 'Admin';
        $dashboardLink = '../admin/dashboard.php';
    }

    // Now, let's fetch the courses enrolled by the staff member
    $coursesQuery = "SELECT course.* FROM coursetaken
                     INNER JOIN course ON coursetaken.course_id = course.id
                     WHERE coursetaken.staff_id = ?";

    $coursesStmt = $conn->prepare($coursesQuery);
    $coursesStmt->bind_param("i", $staffID);
    $coursesStmt->execute();
    $coursesResult = $coursesStmt->get_result();

    $courseCount = 0; // Initialize the course count to 0
    $completedCourseCount = 0; // Initialize the count of completed courses to 0

    if ($coursesResult && $coursesResult->num_rows > 0) {
        $courseCount = $coursesResult->num_rows; // Get the count of courses

        while ($courseRow = $coursesResult->fetch_assoc()) {
            // Check the status of each course
            $coursesQuery = "SELECT course.*, coursetaken.status FROM coursetaken
            INNER JOIN course ON coursetaken.course_id = course.id
            WHERE coursetaken.staff_id = ?";

            $completedCourseCount++;
        }
    }

} else {
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
    <link rel="stylesheet" href="../style/showstaff.css">
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
          integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
          crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.min.js"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59Aa8SWtI3F2VKkg8j7+8CIM"
        crossorigin="anonymous"></script>

</head>

<body>
<?php include '../includes/navbar2.php'; ?>

<div class="section web-header">
    <div class="header-container">
        <div class="header-content">
            <h1>Profile</h1>
            <div class="staff-details">
                <div style="text-align: center;">
                    <p><?php echo $userTypeText; ?></p>
                    <?php if (!empty($dashboardLink)): ?>
                        <a href="<?php echo $dashboardLink; ?>">Dashboard</a>
                    <?php endif; ?>
                </div>
                <img src="<?php echo $staffImage; ?>" alt="Profile Picture" style="border-radius: 50%;">
                <p>Full Name: <?php echo $staffName; ?></p>
                <p>Email: <?php echo $staffEmail; ?></p>
                <p>Department: <?php echo $departmentName; ?></p>
                <p>Position: <?php echo $staffPosition; ?></p>
                <p>Code: <?php echo $staffCode; ?></p>

                <div class="buttons">
                    <a href="mycourse.php">
                        <button class="btn btn-outline-primary px-4">Course Taken: <?php echo $courseCount; ?></button>
                    </a>
                    <button class="btn btn-primary px-4 ms-3">Course Completed: <?php echo $completedCourseCount; ?></button>
                </div>
                <div class="profile-buttons">
                    <a href="editstaff.php">
                        <button class="btn btn-outline-secondary px-4">Edit Profile</button>
                    </a>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
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
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
<script src="../js/script.js"></script>
</body>

</html>