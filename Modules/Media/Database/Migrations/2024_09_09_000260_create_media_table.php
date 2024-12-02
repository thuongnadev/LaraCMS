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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->text('name')
                ->nullable();
            $table->text('file_name');
            $table->text('file_path');
            $table->string('file_type', 50);
            $table->string('mime_type', 100)
                ->nullable();
            $table->bigInteger('file_size');
            $table->text('alt_text')
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->nullableTimestamps();
        });
    }

        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
};
