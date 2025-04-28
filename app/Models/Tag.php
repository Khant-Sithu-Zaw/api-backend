<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tag';
    protected $fillable = ['name'];
    
    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_tag', 'tag_id', 'food_id');
    }
}
