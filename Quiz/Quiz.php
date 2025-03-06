<?php

require_once 'Question.php';
require_once 'QuizManager.php';

$message = "";
$quizManager = new QuizManager();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['form_type'])) {
        switch ($_POST['form_type']) {
            case 'add_question':
                $question = htmlspecialchars($_POST['question']);
                $options = $_POST['options'];
                $correct = $_POST['correct'];

                if (empty($question)) {
                    $message = "<p style='color: red'>La question ne peut pas être vide.</p>\n";
                } elseif (count($options) < 2) {
                    $message = "<p style='color: red'>Veuillez fournir au moins deux options.</p>\n";
                } elseif (count($correct) !== 1) {
                    $message = "<p style='color: red'>Veuillez sélectionner exactement une bonne réponse.</p>\n";
                } else {
                    $index = "";
                    foreach ($correct as $key => $value) {
                        if ($value == "on") {
                            $index = $key;
                            break;
                        }
                    }
                    $newQuestion = new Question($question, $options, $index);
                    $quizManager->addQuestionToQuiz($newQuestion);
                    $message = "<h4 style='color: green'>Ajout de la question réussi.</h4>\n";
                }
                break;

            // !!!! Réviser cette partie !!!!!
            case 'submit_quiz':
                $data = file_get_contents("data/quiz.json");
                $data = json_decode($data, true);

                $score = 0;
                $total_questions = count($data);
                $feedback = [];

                foreach ($data as $key => $question) {
                    if (isset($_POST['question' . $key])) {
                        $user_answer = $_POST['question' . $key];
                        $correct_answer = $question['correct'];

                        if ($user_answer == $correct_answer) {
                            $score++;
                            $feedback[$key] = "<h4 style='color: green'>" . $question['options'][$correct_answer] . " est la bonne réponse." . "</h4>";
                        } else {
                            $feedback[$key] = "<h4 style='color: red'>" . $question['options'][$user_answer] . " est la mauvaise réponse." . "</h4>";
                        }
                    }
                }

                echo "<p style='color: blue;text-align: center; font-weight: bolder'>Vous avez obtenu un score de $score sur $total_questions.</p>";
                break;
        }
    } else {
        echo "<p style='color: red' >Aucun formulaire soumis.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<form method="POST">
    <input type="hidden" name="form_type" value="add_question">
    <h1>Ajouter une question</h1>
    <h3>Veuillez saisir une question</h3>
    <input type="text" id="question" name="question" placeholder="Question">
    <div id="options-container">
        <h3>Veuillez saisir des options</h3>
        <div class="option-item">
            <input type="text" name="options[0]" placeholder="Option">
            <input type="checkbox" name="correct[0]">
            <label>Bonne réponse</label>
        </div>
    </div>
    <button type="button" onclick="add_options()">Ajout d'une option</button>
    <button type="submit">Ajouter la question</button>
    <?php echo $message; ?>
</form>
<form method="post">
    <input type="hidden" name="form_type" value="submit_quiz">
    <?php
    echo "<h1>Quiz</h1>";
    if (file_exists('data/quiz.json')) {
        $data = file_get_contents("data/quiz.json");
        $data = json_decode($data, true);
        foreach ($data as $key => $value) {
            echo "<h2>" . ($key + 1) . ". " . $value["question"] . "</h2>";
            foreach ($value["options"] as $index => $option) {
                echo "<input type='radio' name='question" . $key . "' value='$index'> " . $value["options"][$index] . "<br>";
            }
            if (isset($feedback[$key])) {
                echo "<h4>" . $feedback[$key] . "</h4>";
            }
        }
        echo "<button type='submit'>Soumettre le quiz.</button>";
    }


    ?>

</form>
<script src="script.js"></script>
</body>
</html>
