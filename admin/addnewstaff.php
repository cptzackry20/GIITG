<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$staffCode = $staffName = $staffEmail = $staffPassword = $staffPosition = $staffDepartment = $staffStatus = "";
$staffCodeErr = $staffNameErr = $staffEmailErr = $staffPasswordErr = $staffPositionErr = $staffDepartmentErr = $staffStatusErr = "";

// Handle the form submission to add a new staff member
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $staffCode = htmlspecialchars($_POST['staff_code']);
    $staffName = htmlspecialchars($_POST['staff_name']);
    $staffEmail = htmlspecialchars($_POST['staff_email']);
    $staffPassword = htmlspecialchars($_POST['staff_password']);
    $staffPosition = htmlspecialchars($_POST['staff_position']);
    $staffDepartment = htmlspecialchars($_POST['staff_department']);
    $staffStatus = htmlspecialchars($_POST['staff_status']);

    // Validate form inputs (you can add more specific validation rules)
    if (empty($staffCode)) {
        $staffCodeErr = "Staff code is required";
    }

    if (empty($staffName)) {
        $staffNameErr = "Staff name is required";
    }

    if (empty($staffEmail)) {
        $staffEmailErr = "Staff email is required";
    }

    if (empty($staffPassword)) {
        $staffPasswordErr = "Staff password is required";
    }

    if (empty($staffPosition)) {
        $staffPositionErr = "Staff position is required";
    }

    if (empty($staffDepartment)) {
        $staffDepartmentErr = "Staff department is required";
    }

    if (empty($staffStatus)) {
        $staffStatusErr = "Staff status is required";
    }

    // Check if there are no errors
    if (empty($staffCodeErr) && empty($staffNameErr) && empty($staffEmailErr) && empty($staffPasswordErr) && empty($staffPositionErr) && empty($staffDepartmentErr) && empty($staffStatusErr)) {
        // Insert the new staff member into the database
        $insertQuery = "INSERT INTO staff (code, name, email, password, position, department_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        // Check if the prepare statement was successful
        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        // Bind parameters
        $stmt->bind_param("ssssiss", $staffCode, $staffName, $staffEmail, $staffPassword, $staffPosition, $staffDepartment, $staffStatus);

        // Check if the bind_param was successful
        if ($stmt->execute()) {
            // Staff member added successfully
            header("Location: managestaff.php"); // Redirect to the manage staff page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Query to fetch department names for the select option
$departmentQuery = "SELECT id, name FROM department";
$departmentResult = $conn->query($departmentQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary meta tags, stylesheets, and scripts -->
    <title>Add New Staff</title>
    <!-- Add any additional styles or scripts as needed -->
</head>
<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Add New Staff</h1>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <br>
        <h2>Add New Staff</h2>
        <form method="POST" action="">
            <!-- Add form fields for staff details (code, name, email, password, position, department, status) -->
            <div class="form-group">
                <label for="staff_code">Staff Code</label>
                <input type="text" class="form-control" id="staff_code" name="staff_code" required>
                <span class="text-danger"><?php echo $staffCodeErr; ?></span>
            </div>
            <!-- Add form fields for other staff details -->
            <div class="form-group">
                <label for="staff_name">Staff Name</label>
                <input type="text" class="form-control" id="staff_name" name="staff_name" required>
                <span class="text-danger"><?php echo $staffNameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="staff_email">Staff Email</label>
                <input type="email" class="form-control" id="staff_email" name="staff_email" required>
                <span class="text-danger"><?php echo $staffEmailErr; ?></span>
            </div>
            <!-- Add form fields for other staff details (password, position, department, status) -->
            <div class="form-group">
                <label for="staff_password">Staff Password</label>
                <input type="password" class="form-control" id="staff_password" name="staff_password" required>
                <span class="text-danger"><?php echo $staffPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <label for="staff_position">Staff Position</label>
                <input type="text" class="form-control" id="staff_position" name="staff_position" required>
                <span class="text-danger"><?php echo $staffPositionErr; ?></span>
            </div>
            <div class="form-group">
                <label for="staff_department">Staff Department</label>
                <select class="form-control" id="staff_department" name="staff_department" required>
                    <option value="">Select a department</option>
                    <?php
                    if ($departmentResult && $departmentResult->num_rows > 0) {
                        while ($departmentRow = $departmentResult->fetch_assoc()) {
                            echo '<option value="' . $departmentRow['id'] . '">' . $departmentRow['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo $staffDepartmentErr; ?></span>
            </div>
            <div class="form-group">
                <label for="staff_status">Staff Status</label>
                <input type="text" class="form-control" id="staff_status" name="staff_status" required>
                <span class="text-danger"><?php echo $staffStatusErr; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Add Staff</button>
        </form>
        <br>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
