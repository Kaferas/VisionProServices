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

        Schema::create('ocpos_product_sorties', function (Blueprint $table) {
            $table->id('sortie_id');
            $table->string('sortie_title')->nullable();
            $table->string('sortie_code')->index('idx_srcode')->unique('unx_srcode')->nullable();
            $table->integer('sortie_status')->comment('0:onhold,1:confirmed')->default(0);
            $table->string('sortie_type')->nullable()->default('SN');
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ocpos_product_sortie_items', function (Blueprint $table) {
            $table->id();
            $table->string('ref_sortie_code')->index('idx_srdcode')->nullable();
            $table->string('ref_product_code')->index('idx_sprcode')->nullable();
            $table->string('sortie_type')->nullable()->default('SN');
            $table->integer('item_sortie_status')->comment('0:onhold,1:confirmed')->default(0);
            $table->string('item_sortie_name')->nullable();
            $table->double('item_sortie_quantity',15,5)->nullable();
            $table->double('item_sortie_price',15,5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocpos_product_sorties');
        Schema::dropIfExists('ocpos_product_sortie_items');
    }
};
