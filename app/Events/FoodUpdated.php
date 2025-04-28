<?php

namespace App\Events;

use App\Models\Food;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FoodUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $food;

    public function __construct(Food $food)
    {
        $this->food = $food;
    }

    public function broadcastOn()
    {
        return new Channel('food-updates');
    }

    public function broadcastAs()
    {
        return 'food.updated';
    }
}
