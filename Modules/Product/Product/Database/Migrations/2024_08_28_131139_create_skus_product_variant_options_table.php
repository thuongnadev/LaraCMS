<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkusProductVariantOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus_product_variant_options', function (Blueprint $table) {
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('product_variant_option_id');

            $table->foreign('sku_id')
                ->references('id')
                ->on('skus');
            $table->foreign('product_variant_option_id')
                ->references('id')
                ->on('product_variant_options');

            $table->primary(['sku_id', 'product_variant_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skus_product_variant_options');
    }
}
