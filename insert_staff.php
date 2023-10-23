<?php
include('includes/config.php');

// Function to add a staff member
function addStaff() {
    global $con;

    // Check if the form is submitted
    if(isset($_POST['stf_id']) && isset($_POST['staffname']) && isset($_POST['staffemail']) && isset($_POST['staffpass'])) {
        $stf_id = $_POST['stf_code'];
        $staffname = $_POST['staffname'];
        $staffemail = $_POST['staffemail'];
        $staffpass = $_POST['staffpass'];

        // Perform database insertion
        $sql = "INSERT INTO staff (stf_code, stf_name, stf_email, stf_pass) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssss", $stf_id, $staffname, $staffemail, $staffpass);

        if ($stmt->execute()) {
            // Registration successful
            echo "success";
        } else {
            // Registration failed
            echo "failed";
        }

        $stmt->close();
        $con->close();
    }
}

// Check if a staff registration request is submitted
if (isset($_POST['signup'])) {
    addStaff();
}
?>
