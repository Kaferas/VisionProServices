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
        Schema::create('ocpos_customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_tin')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_file')->nullable();
            $table->integer('customer_type')->default(1);
            $table->integer('customer_status')->default(0);
            $table->integer('customer_vatpayer')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocpos_customers');
    }
};
