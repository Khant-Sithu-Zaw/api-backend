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
        if (!Schema::hasTable('order_details')) {
        Schema::create('order_details', function (Blueprint $table) {
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('food_id');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);

                // Composite Primary Key
                $table->primary(['order_id', 'food_id']);
                // Foreign Keys
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('food_id')->references('id')->on('food')->onDelete('cascade');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
