<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_has_models', function (Blueprint $table) {
            $table->id();
            $table->string('model_type')
                ->nullable();
            $table->unsignedBigInteger('model_id')
                ->nullable();
            $table->string('model_name')
                ->nullable();
            $table->foreignId('media_id')
                ->constrained('media')
                ->onDelete('cascade');
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
        Schema::dropIfExists('media_has_models');
    }
};
