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
        Schema::create('ocpos_product_inventories', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->string('inventory_title')->nullable();
            $table->string('inventory_code')->index('idx_ivcode')->unique('unx_ivcode')->nullable();
            $table->integer('inventory_status')->comment('0:onhold,1:confirmed')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ocpos_product_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('ref_inventory_code')->index('idx_ivdcode')->nullable();
            $table->string('ref_product_code')->index('idx_ivrcode')->nullable();
            $table->integer('item_inventory_status')->comment('0:onhold,1:confirmed')->default(0);
            $table->string('item_inventory_name')->nullable();
            $table->double('item_system_quantity',15,5)->nullable();
            $table->double('item_physic_quantity',15,5)->nullable();
            $table->double('item_inventory_price',15,5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
