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
        Schema::create('insurances', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('car_id');
    $table->string('provider');
    $table->string('policy_number');
    $table->decimal('cost', 12, 2);
    $table->date('start_date');
    $table->date('end_date');
    $table->boolean('active')->default(true);
    $table->string('document_scan')->nullable();
    $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
