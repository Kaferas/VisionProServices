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
        Schema::create('drivers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('phone')->nullable();
    $table->string('email')->nullable();
    $table->string('license_number')->nullable(); // Permis
    $table->date('license_expiry')->nullable(); // Expiration permis
    $table->string('photo')->nullable();
    $table->unsignedBigInteger('assigned_car_id')->nullable();
    $table->foreign('assigned_car_id')->references('id')->on('cars')->onDelete('set null');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::dropIfExists('drivers');
    }
};
