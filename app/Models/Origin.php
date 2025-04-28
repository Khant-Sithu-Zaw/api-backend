<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Origin extends Model
{
    use HasFactory;
    protected $table = 'origin';
    protected $fillable = ['name'];
    public function food()
    {
        return $this->belongsToMany(Food::class, 'food_origin');
    }
}
