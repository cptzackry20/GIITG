<?php
// Include the database configuration file
include '../includes/config.php';

// Handle editing a question
if ($_POST['action'] === 'edit_question') {
    $questionId = $_POST['question_id'];
    $questionText = $_POST['question_text'];
    $optionA = $_POST['option_a'];
    $optionB = $_POST['option_b'];
    $optionC = $_POST['option_c'];
    $optionD = $_POST['option_d'];
    $correctOption = $_POST['correct_option'];

    // You can add validation and sanitization here as needed

    $updateQuery = "UPDATE question SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ssssssi", $questionText, $optionA, $optionB, $optionC, $optionD, $correctOption, $questionId);

    if ($stmt->execute()) {
        echo "Question updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle deleting a question
if ($_POST['action'] === 'delete_question') {
    $questionId = $_POST['question_id'];

    // You can add validation and confirmation here as needed

    $deleteQuery = "DELETE FROM question WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);

    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("i", $questionId);

    if ($stmt->execute()) {
        echo "Question deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
