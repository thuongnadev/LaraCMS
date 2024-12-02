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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_viewed')
                ->default(false);
            $table->foreignId('viewed_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamp('viewed_at')
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
        Schema::dropIfExists('form_submissions');
    }
};
