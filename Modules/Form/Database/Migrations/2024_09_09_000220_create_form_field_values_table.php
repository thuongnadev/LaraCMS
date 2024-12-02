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
        Schema::create('form_field_values', function (Blueprint $table) {
            $table->id();
            $table->text('value')
                ->nullable();
            $table->timestamps();

            $table->foreignId('form_submission_id')
                ->constrained('form_submissions')
                ->onDelete('cascade');
            $table->foreignId('form_field_id')
                ->constrained('form_fields')
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
        Schema::dropIfExists('form_field_values');
    }
};
