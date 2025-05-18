<?php // app/DAOs/FoodDAO.php
namespace App\DAO;

use App\Models\Food;
use App\Models\Origin;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class FoodDAO
{
public function getAllFoods()
{
return Food::with(['tag', 'origin'])->get();
}
public function getAllTag()
{
   $tags = Tag::leftJoin('food_tag', 'tag.id', '=', 'food_tag.tag_id')
            ->select('tag.id', 'tag.name as tag_name', DB::raw('COUNT(food_tag.food_id) as food_count'))
            ->groupBy('tag.id', 'tag.name')
            ->get();
            return $tags;
}
   public function getAllOrigins(){
      $origins = Origin::leftJoin('food_origin', 'origin.id', '=', 'food_origin.origin_id')
         ->select('origin.id', 'origin.name as origin_name', DB::raw('COUNT(food_origin.food_id) as food_count'))
         ->groupBy('origin.id', 'origin.name')
         ->get();

      return $origins;
   }
}