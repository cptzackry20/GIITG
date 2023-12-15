<?php
include '../includes/config.php';

$departmentName = "";
$departmentNameErr = "";

// Handle the form submission to add a new department
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departmentName = htmlspecialchars($_POST['department_name']);

    if (empty($departmentName)) {
        $departmentNameErr = "Department name is required";
    }

    if (empty($departmentNameErr)) {
        $insertQuery = "INSERT INTO department (name) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);

        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        $stmt->bind_param("s", $departmentName);

        if ($stmt->execute()) {
            header("Location: managedepartment.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$departmentsQuery = "SELECT id, name FROM department";
$departmentsResult = $conn->query($departmentsQuery);

$existingDepartments = array();

if ($departmentsResult && $departmentsResult->num_rows > 0) {
    while ($department = $departmentsResult->fetch_assoc()) {
        $existingDepartments[] = $department;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Manage Department</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">

    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/course.css">
</head>
<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Manage Departments</h1>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2>Manage Departments</h2>

                <!-- Display existing department list or a message if none exist -->
                <?php if (!empty($existingDepartments)): ?>
                    <h3>Existing Departments:</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Department Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $departmentNumber = 1;
                            foreach ($existingDepartments as $department):
                            ?>
                                <tr>
                                    <td><?php echo $departmentNumber; ?></td>
                                    <td><?php echo $department['name']; ?></td>
                                    <td>
                                        <a href="editdepartment.php?id=<?php echo $department['id']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="deletedepartment.php?id=<?php echo $department['id']; ?>" class="btn btn-danger" 
                                            onclick="return confirm('Are you sure you want to delete this department?')">Delete</a>
                                    </td>
                                </tr>
                            <?php
                                $departmentNumber++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <!-- Display department courses in a table -->
                    <h1>Department Courses</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Department</th>
                                <th>Courses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $courseNumber = 1;
                            foreach ($existingDepartments as $department):
                                $departmentId = $department['id'];
                                $coursesQuery = "SELECT c.name AS course_name FROM department_courses dc 
                                                JOIN course c ON dc.course_id = c.id 
                                                WHERE dc.department_id = $departmentId";

                                $coursesResult = $conn->query($coursesQuery);

                                echo "<tr>";
                                echo "<td>{$courseNumber}</td>";
                                echo "<td>{$department['name']}</td>";
                                echo "<td>";
                                if ($coursesResult && $coursesResult->num_rows > 0) {
                                    echo "<ul>";
                                    while ($course = $coursesResult->fetch_assoc()) {
                                        echo "<li>{$course['course_name']}</li>";
                                    }
                                    echo "</ul>";
                                } else {
                                    echo "No courses found for {$department['name']}";
                                }
                                echo "</td>";
                                echo "</tr>";

                                $courseNumber++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>There are no departments added yet.</p>
                <?php endif; ?>

                <h2>Add New Department</h2>
                <form method="POST" action="managedepartment.php">
                    <div class="form-group">
                        <label for="department_name">Department Name</label>
                        <input type="text" class="form-control" id="department_name" name="department_name" required>
                        <span class="text-danger"><?php echo $departmentNameErr; ?></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Department</button>
                </form>
            </div>
            <br>
        </div>
    </div>
    <br>
    <?php include '../includes/footer.php'; ?>
</body>
</html>