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
        Schema::create('ocpos_products', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('item_name')->nullable();
            $table->string('item_codebar')->index('idx_barcode')->nullable();
            $table->double('item_sellprice')->nullable();
            $table->double('item_costprice')->nullable();
            $table->double('item_corprice')->nullable()->comment('prix de reviens');
            $table->double('item_quantity',15,5)->default(0)->nullable();
            $table->double('item_tva')->nullable();
            $table->double('item_tc')->nullable();
            $table->double('item_pf')->nullable();
            $table->integer('item_status')->default('0')->comment('0:actif,1:desactif');
            $table->integer('item_type')->default('0')->comment('0:stockable,1:non stockable');
            $table->integer('item_isSellable')->default('0')->comment('0:yes,1:non');
            $table->integer('item_category')->nullable();
            $table->string('item_unity')->nullable();
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
        Schema::dropIfExists('ocpos_products');
    }
};
