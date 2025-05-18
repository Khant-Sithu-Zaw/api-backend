<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Services\FoodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FoodController extends Controller
{
    protected $foodService;

    public function __construct(FoodService $foodService)
    {
        $this->foodService = $foodService;
    }

    // Fetch all foods with tags and origins
    public function index()
    {
        $foods = $this->foodService->getFoods();
        Log::info($foods);
        return response()->json($foods);
    }
    public function getTags()
    {
        return response()->json($this->foodService->getAllTags());
    }
    public function getOrigins(){
        return response()->json($this->foodService->getAllOrigins());
    }
}
