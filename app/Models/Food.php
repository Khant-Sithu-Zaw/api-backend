<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'cookTime', 'price', 'stars','fav', 'imgUrl'];
    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'food_tag');
    }

    public function origin()
    {
        return $this->belongsToMany(Origin::class, 'food_origin');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'food_id', 'id');
    }
}
