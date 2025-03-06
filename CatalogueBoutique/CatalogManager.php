<?php

class CatalogManager
{
    private string $filePath = "data/catalog.json";

    public function addProductToCatalog($catalogData): void
    {
        $catalog = $this->loadCatalog();
        $catalog[] = $catalogData;
        $this->saveCatalog($catalog);
    }

    public function loadCatalog()
    {
        if (file_exists($this->filePath)) {
            $data = file_get_contents($this->filePath);
            return json_decode($data, true);
        }
        return [];
    }

    public function saveCatalog($data): void
    {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}