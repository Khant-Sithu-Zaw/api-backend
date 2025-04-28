<?php
// app/Services/FoodService.php
namespace App\Services;

use App\DAO\FoodDAO;
use App\Models\Food;

class FoodService
{
    protected $foodDAO;

    public function __construct(FoodDAO $foodDAO)
    {
        $this->foodDAO = $foodDAO;
    }

    public function getFoods()
    {
        return $this->foodDAO->getAllFoods();
    }
    public function getAllTags()
    {
        return $this->foodDAO->getAllTag();
    }
   
}