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
        Schema::create('form_notifications', function (Blueprint $table) {
            $table->id();
            $table->text('success_message')->nullable();
            $table->text('error_message')->nullable();
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
        Schema::dropIfExists('form_notifications');
    }
};
