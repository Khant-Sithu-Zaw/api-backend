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
        if (!Schema::hasTable('food_origin')) {
        Schema::create('food_origin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('origin_id');
            $table->foreign('food_id')->references('id')->on('food')->onDelete('cascade');
            $table->foreign('origin_id')->references('id')->on('origin')->onDelete('cascade');
            $table->timestamps();
        });
    }}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_origin');
    }
};
