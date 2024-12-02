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
        Schema::create('pricing_contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bg_color')->nullable();
            $table->string('color_key')->nullable();
            $table->string('color_value')->nullable();
            $table->boolean('bold_key')->default(false);
            $table->boolean('bold_value')->default(false);
            $table->boolean('status')->default(true);
//            $table->text('image_key')->nullable();
//            $table->text('image_value')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_contents');
    }
};
