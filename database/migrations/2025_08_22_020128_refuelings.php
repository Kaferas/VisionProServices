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
        Schema::create('refuelings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('car_id');
    $table->decimal('liters', 8, 2);
    $table->decimal('price_per_liter', 8, 2);
    $table->decimal('total_cost', 12, 2);
    $table->integer('mileage');
    $table->date('date');
    $table->string('station')->nullable();
    $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refuelings');
    }
};
