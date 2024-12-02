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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('label');
            $table->string('name');
            $table->text('options')
                ->nullable();
            $table->integer('sort_order')
                ->nullable();
            $table->boolean('is_required')
                ->default(false);
            $table->integer('min_length')
                ->nullable();
            $table->integer('max_length')
                ->nullable();
            $table->timestamps();

            $table->foreignId('form_id')
                ->constrained('forms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_fields');
    }
};
