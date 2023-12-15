<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$staffId = $staffCode = $staffName = $staffEmail = $staffPassword = $staffPosition = $staffDepartment = $staffStatus = "";
$staffCodeErr = $staffNameErr = $staffEmailErr = $staffPasswordErr = $staffPositionErr = $staffDepartmentErr = $staffStatusErr = "";

// Check if the staff ID is provided in the query string
if (isset($_GET['id'])) {
    $staffId = $_GET['id'];

    // Retrieve staff details from the database
    $selectQuery = "SELECT s.*, d.name AS department_name 
                    FROM staff s 
                    LEFT JOIN department d ON s.department_id = d.id
                    WHERE s.id = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("i", $staffId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $staffCode = $row['code'];
            $staffName = $row['name'];
            $staffEmail = $row['email'];
            $staffPassword = $row['password'];
            $staffPosition = htmlspecialchars($row['position']); // Retrieve the staff position and sanitize
            $staffDepartment = $row['department_id'];
            $staffStatus = $row['status'];
        } else {
            echo "Staff member not found.";
            exit;
        }
    } else {
        echo "Error: " . $conn->error;
        exit;
    }
}

// Query to fetch department names for the select option
$departmentQuery = "SELECT id, name FROM department";
$departmentResult = $conn->query($departmentQuery);

// Handle the form submission to update staff information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $staffCode = htmlspecialchars($_POST['staff_code']);
    $staffName = htmlspecialchars($_POST['staff_name']);
    $staffEmail = htmlspecialchars($_POST['staff_email']);
    $staffPassword = $_POST['staff_password'];
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
        // Hash the password before saving it to the database
        $hashedPassword = password_hash($staffPassword, PASSWORD_DEFAULT);

        // Update the staff member in the database
        $updateQuery = "UPDATE staff SET code = ?, name = ?, email = ?, password = ?, position = ?, department_id = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);

        // Check if the prepare statement was successful
        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        // Bind parameters
        $stmt->bind_param("sssssiis", $staffCode, $staffName, $staffEmail, $hashedPassword, $staffPosition, $staffDepartment, $staffStatus, $staffId);

        // Check if the bind_param was successful
        if ($stmt->execute()) {
            // Staff member updated successfully
            header("Location: managestaff.php"); // Redirect to the manage staff page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Staff Member</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">
</head>
<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Edit Staff</h1>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <br>
        <h2>Edit Staff</h2>
        <form method="POST" action="">
            <!-- Add form fields for staff details (code, name, email, password, position, department, status) -->
            <input type="hidden" name="staff_id" value="<?php echo $staffId; ?>">
            <div class="form-group">
                <label for="staff_code">Staff Code</label>
                <input type="text" class="form-control" id="staff_code" name="staff_code" value="<?php echo $staffCode; ?>" required>
                <span class="text-danger"><?php echo $staffCodeErr; ?></span>
            </div>
            <!-- Add form fields for other staff details -->
            <div class="form-group">
                <label for="staff_name">Staff Name</label>
                <input type="text" class="form-control" id="staff_name" name="staff_name" value="<?php echo $staffName; ?>" required>
                <span class="text-danger"><?php echo $staffNameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="staff_email">Staff Email</label>
                <input type="email" class="form-control" id="staff_email" name="staff_email" value="<?php echo $staffEmail; ?>" required>
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
                <input type="text" class="form-control" id="staff_position" name="staff_position" value="<?php echo $staffPosition; ?>" required>
                <span class="text-danger"><?php echo $staffPositionErr; ?></span>
            </div>
            <div class="form-group">
                <label for="staff_department">Staff Department</label>
                <select class="form-control" id="staff_department" name="staff_department" required>
                    <option value="">Select a department</option>
                    <?php
                    if ($departmentResult && $departmentResult->num_rows > 0) {
                        while ($departmentRow = $departmentResult->fetch_assoc()) {
                            $selected = ($staffDepartment == $departmentRow['id']) ? 'selected' : '';
                            echo '<option value="' . $departmentRow['id'] . '" ' . $selected . '>' . $departmentRow['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo $staffDepartmentErr; ?></span>
            </div>
            <div class="form-group">
    <label for="staff_status">Staff Status</label>
    <select class="form-control" id="staff_status" name="staff_status" required>
        <option value="1" <?php echo ($staffStatus == 1) ? 'selected' : ''; ?>>Active</option>
        <option value="0" <?php echo ($staffStatus == 0) ? 'selected' : ''; ?>>Inactive</option>
    </select>
    <span class="text-danger"><?php echo $staffStatusErr; ?></span>
</div>

            <button type="submit" class="btn btn-primary">Update Staff</button>
        </form>
        <br>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
