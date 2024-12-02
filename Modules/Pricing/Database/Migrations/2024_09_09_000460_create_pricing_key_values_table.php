<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_key_values', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->default(0);
            $table->timestamps();
            $table->foreignId('pricing_id')
                ->constrained('pricings')
                ->onDelete('cascade');
            $table->foreignId('pricing_key_id')
                ->nullable()
                ->constrained('pricing_keys')
                ->onDelete('set null');
            $table->foreignId('pricing_value_id')
                ->nullable()
                ->constrained('pricing_values')
                ->onDelete('set null');
            $table->foreignId('pricing_content_id')
                ->nullable()
                ->constrained('pricing_contents')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('key_values');
    }
};
