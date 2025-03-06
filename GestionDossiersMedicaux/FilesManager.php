<?php

class FilesManager
{
    private string $filePath = "data/files.json";

    public function addMedicalToDocs($medicalDoc): void
    {
        $docs = $this->loadDocs();
        $docs[] = $medicalDoc;
        $this->saveDocs($docs);
    }

    public function loadDocs()
    {
        if (file_exists($this->filePath)) {
            $data = file_get_contents($this->filePath);
            return json_decode($data, true);
        }
        return [];
    }

    public function saveDocs($data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}