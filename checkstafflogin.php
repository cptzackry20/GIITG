<?php
session_start(); // Start the session if it's not already started

if (isset($_POST['staffLogEmail']) && isset($_POST['staffLogPass'])) {
    include('includes/config.php'); // Include your database connection file

    $staffLogEmail = $_POST['staffLogEmail'];
    $staffLogPass = $_POST['staffLogPass'];

    // Perform user authentication (modify this part based on your database structure)
    $sql = "SELECT * FROM staff WHERE stf_email = ? AND stf_pass = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $staffLogEmail, $staffLogPass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Authentication successful
        $row = $result->fetch_assoc();

        // Store user data in a session (modify this as needed)
        $_SESSION['user'] = $row;

        // Close the database connection
        $stmt->close();
        $con->close();

        // Redirect to the desired page after successful login
        header("Location: index.php");
        exit();
    } else {
        // Authentication failed
        $_SESSION['errmsg'] = "Invalid email or password";

        // Close the database connection
        $stmt->close();
        $con->close();

        // Redirect back to the login page with an error message
        header("Location: login.php");
        exit();
    }
} else {
    // Redirect back to the login page if POST data is missing
    header("Location: login.php");
    exit();
}
?>
