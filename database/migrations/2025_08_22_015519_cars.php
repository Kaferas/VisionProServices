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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique(); // Immatriculation
            $table->string('brand'); // Marque
            $table->string('model'); // Modèle
            $table->year('year'); // Année
            $table->string('color')->nullable(); // Couleur
            $table->integer('mileage')->default(0); // Kilométrage
            $table->string('fuel_type')->nullable(); // Essence/Diesel/Hybride/Électrique
            $table->integer('tank_capacity')->nullable(); // Capacité du réservoir
            $table->string('chassis_number')->nullable(); // N° de châssis
            $table->string('engine_number')->nullable(); // N° moteur
            $table->date('insurance_expiry')->nullable(); // Expiration assurance
            $table->date('technical_check_expiry')->nullable(); // Expiration contrôle technique
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('cars');
    }
};
