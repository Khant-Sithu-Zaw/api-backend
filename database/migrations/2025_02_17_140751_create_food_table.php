<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('food')) {
            Schema::create('food', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('cookTime');
                $table->decimal('price', 10, 2); // Decimal column for price (10 digits, 2 decimal places)
                $table->decimal('stars', 2, 1)->default(0); // Decimal column for stars (2 digits, 1 decimal place)
                $table->boolean('fav')->default(false);
                $table->string('imgUrl');
                $table->timestamps();
            });
        }
    
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
