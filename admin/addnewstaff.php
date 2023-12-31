<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$staffCode = $staffName = $staffEmail = $staffPassword = $staffPosition = $staffDepartment = $staffStatus = $staffType = "";
$staffCodeErr = $staffNameErr = $staffEmailErr = $staffPasswordErr = $staffPositionErr = $staffDepartmentErr = $staffStatusErr = $staffTypeErr = "";

// Handle the form submission to add a new staff member
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $staffCode = htmlspecialchars($_POST['staff_code']);
    $staffName = htmlspecialchars($_POST['staff_name']);
    $staffEmail = htmlspecialchars($_POST['staff_email']);
    $staffPassword = $_POST['staff_password']; // Not sanitized
    $staffPosition = htmlspecialchars($_POST['staff_position']);
    $staffDepartment = htmlspecialchars($_POST['staff_department']);
    $staffStatus = htmlspecialchars($_POST['staff_status']);
    $staffType = htmlspecialchars($_POST['staff_type']);

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

    if (empty($staffType)) {
        $staffTypeErr = "Staff type is required";
    }

    // Check if there are no errors
    if (empty($staffCodeErr) && empty($staffNameErr) && empty($staffEmailErr) && empty($staffPasswordErr) && empty($staffPositionErr) && empty($staffDepartmentErr) && empty($staffStatusErr) && empty($staffTypeErr)) {
        // Hash the password before saving it to the database
        $hashedPassword = password_hash($staffPassword, PASSWORD_DEFAULT);

        // Insert the new staff member into the database
        $insertQuery = "INSERT INTO staff (code, name, email, password, position, dp, department_id, status, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        // Check if the prepare statement was successful
        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        // Bind parameters
        $stmt->bind_param("ssssssisi", $staffCode, $staffName, $staffEmail, $hashedPassword, $staffPosition, $dpPath, $staffDepartment, $staffStatus, $staffType);

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
    <title>Add New Staff</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">
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
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="staff_status" id="active" value="1" checked>
                    <label class="form-check-label" for="active">
                        Active
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="staff_status" id="inactive" value="0">
                    <label class="form-check-label" for="inactive">
                        Inactive
                    </label>
                </div>
                <span class="text-danger"><?php echo $staffStatusErr; ?></span>
            </div>
            <!-- Add form field for staff type -->
            <div class="form-group">
                <label for="staff_type">Staff Type</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="staff_type" id="userType1" value="1" checked>
                    <label class="form-check-label" for="userType1">Normal Staff</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="staff_type" id="userType2" value="2">
                    <label class="form-check-label" for="userType2">Instructor</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="staff_type" id="userType3" value="3">
                    <label class="form-check-label" for="userType3">Admin</label>
                </div>
                <span class="text-danger"><?php echo $staffTypeErr; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Add Staff</button>
        </form>
        <br>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
