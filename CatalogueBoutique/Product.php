<?php

class Product
{
    public string $Name;
    public float $Price;
    public string $Category;
    public bool $Stock;

    public string $Image;

    public function __construct(string $name, float $price, string $category, bool $stock, string $image)
    {
        $this->Name = $name;
        $this->Price = $price;
        $this->Category = $category;
        $this->Image = $image;
        $this->Stock = $stock;
    }
}