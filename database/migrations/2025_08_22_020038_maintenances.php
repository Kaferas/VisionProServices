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
        Schema::create('maintenances', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('car_id');
    $table->string('service_type'); // Vidange, freins, pneus, etc.
    $table->decimal('cost', 12, 2);
    $table->date('date');
    $table->integer('mileage_at_service'); // Kilométrage lors de l’entretien
    $table->string('garage')->nullable();
    $table->text('notes')->nullable();
    $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
