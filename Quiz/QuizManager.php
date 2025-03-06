<?php

class QuizManager
{
    private string $filePath = "data/quiz.json";

    public function addQuestionToQuiz($questionData): void
    {
        $quizData = $this->loadQuiz();
        $quizData[] = $questionData;
        $this->saveQuiz($quizData);
    }

    public function loadQuiz()
    {
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }

        $data = file_get_contents($this->filePath);
        return json_decode($data, true);
    }

    public function saveQuiz($data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}