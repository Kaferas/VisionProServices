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
        Schema::create('ocpos_product_supplies', function (Blueprint $table) {
            $table->id('supply_id');
            $table->string('title')->nullable();
            $table->string('supply_code')->index('idx_spcode')->unique('unx_spcode')->nullable();
            $table->integer('supply_status')->comment('0:onhold,1:confirmed,2:rejected')->default(0);
            $table->enum('supply_type',['EN','ET','EAJ'])->comment('0:EN:normal,1:ET:transfert')->default('EN');
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->integer('confirmed_or_rejected_by')->nullable();
            $table->timestamp('confirmed_or_rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ocpos_supplies_details', function (Blueprint $table) {
            $table->id();
            $table->string('ref_supply_code')->index('idx_spdcode')->nullable();
            $table->string('ref_product_code')->index('idx_prdcode')->nullable();
            $table->string('supply_type')->comment('0:EN:normal,1:ET:transfert')->default('EN');
            $table->string('product_name')->nullable();
            $table->double('item_quantity')->nullable();
            $table->double('purchase_price')->nullable();
            $table->integer('modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocpos_product_supplies');
        Schema::dropIfExists('ocpos_supplies_details');
    }
};
