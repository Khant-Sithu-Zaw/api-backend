<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false; // We use `order_date` instead of created_at/updated_at

    protected $fillable = [
        'user_name',
        'email',
        'phone',
        'address',
        'total_price',
        'order_status',
        'order_date',
    ];

    // Relationship: One Order has many OrderDetails
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
