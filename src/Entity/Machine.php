<?php

namespace App\Entity;

class Machine {

    public $id = '';
    public $brand = '';
    public $model = '';
    public $manufacturer = '';
    public $price = 0;
    public $images = [];

    
    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    public function setInDataBase($machine){
        $this->var_dump($machine);
    }
}