<?php
// Include the database configuration file
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'edit_question') {
            // Handle editing a question
            $questionId = $_POST['question_id'];
            $questionText = $_POST['question_text'];
            $optionA = $_POST['option_a'];
            $optionB = $_POST['option_b'];
            $optionC = $_POST['option_c'];
            $optionD = $_POST['option_d'];
            $correctOption = $_POST['correct_option'];

            // You can add validation and sanitization here as needed

            // SQL query to update the question
            $updateQuery = "UPDATE question SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);

            if ($stmt === false) {
                echo "Error: " . $conn->error;
                exit;
            }

            // Bind parameters
            $stmt->bind_param("ssssssi", $questionText, $optionA, $optionB, $optionC, $optionD, $correctOption, $questionId);

            if ($stmt->execute()) {
                echo "Question updated successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
        } elseif ($_POST['action'] === 'delete_question') {
            // Handle deleting a question
            $questionId = $_POST['question_id'];

            // You can add validation and confirmation here as needed

            // SQL query to delete the question
            $deleteQuery = "DELETE FROM question WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);

            if ($stmt === false) {
                echo "Error: " . $conn->error;
                exit;
            }

            // Bind parameter
            $stmt->bind_param("i", $questionId);

            if ($stmt->execute()) {
                echo "Question deleted successfully";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } elseif (isset($_POST['submit'])) {
        // Handle the form submission to save multiple-choice questions
        $quizId = $_GET['id']; // Retrieve the quiz ID from the URL

        // Get the posted data
        $questionTexts = $_POST['question_text'];
        $optionAs = $_POST['option_a'];
        $optionBs = $_POST['option_b'];
        $optionCs = $_POST['option_c'];
        $optionDs = $_POST['option_d'];
        $correctOptions = $_POST['correct_option'];

        // Prepare and execute SQL statement to insert the questions into the database
        $stmt = $conn->prepare("INSERT INTO question (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        for ($i = 0; $i < count($questionTexts); $i++) {
            if ($stmt->bind_param("issssss", $quizId, $questionTexts[$i], $optionAs[$i], $optionBs[$i], $optionCs[$i], $optionDs[$i], $correctOptions[$i])) {
                if (!$stmt->execute()) {
                    echo "Error: " . $stmt->error;
                    exit;
                }
            } else {
                echo "Error binding parameters: " . $stmt->error;
                exit;
            }
        }

        // Redirect to the editquiz.php page with the quiz ID
        header("Location: editquiz.php?id=" . $quizId);
        exit;
    }
}

// Fetch existing questions for the specific quiz
$quizId = $_GET['id'];
$selectQuestionsQuery = "SELECT * FROM question WHERE quiz_id = ?";
$stmt = $conn->prepare($selectQuestionsQuery);

if ($stmt === false) {
    echo "Error: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $quizId);

if ($stmt->execute()) {
    $questionsResult = $stmt->get_result();
    $questions = [];

    while ($row = $questionsResult->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>