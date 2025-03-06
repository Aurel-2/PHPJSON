<?php

class MedicalFile
{
    public int $id;
    public string $name;
    public int $age;
    public array $diagnosisList = [];

    public function __construct($name, $age, $id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }

    public function addDiagnosis($date, $diagnosis): void
    {
        $this->diagnosisList[] = ['Date' => $date, 'Diagnostic' => $diagnosis];
    }
}