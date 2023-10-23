<?php
// Include the database configuration file
include '../includes/config.php';

// Handle the form submission to save multiple-choice questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
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
        $stmt->bind_param("issssss", $quizId, $questionTexts[$i], $optionAs[$i], $optionBs[$i], $optionCs[$i], $optionDs[$i], $correctOptions[$i]);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
            exit;
        }
    }

    // Redirect to the editquiz.php page with the quiz ID
    header("Location: editquiz.php?id=" . $quizId);
    exit;
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