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
        Schema::create('ocpos_stock_flow_movements', function (Blueprint $table) {
            $table->id();
            $table->string('mov_ref_code')->index('idx_refcodeflow')->nullable();
            $table->string('mov_ref_product_code')->index('idx_barcodeflow')->nullable();
            $table->enum('mov_type',['EN','ET','EI','ER','EAJ','EAU','SN','ST','SAJ','SP','SV','SD','SC','SAU'])->nullable();
            $table->double('mov_item_previous_qty',15,5)->default(0);
            $table->double('mov_item_quantity',15,5)->default(0);
            $table->double('mov_item_price',15,5)->default(0);
            $table->integer('mov_created_by')->nullable();
            $table->integer('mov_obr_status')->default(0)->nullable();
            $table->timestamp('mov_obr_sent_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocpos_stock_flow_movements');
    }
};
