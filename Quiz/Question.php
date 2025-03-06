<?php

class Question
{
    public string $question;
    public array $options;
    public int $correct;

    public function __construct($question, $options, $correct)
    {
        $this->question = $question;
        $this->options = $options;
        $this->correct = $correct;
    }
}