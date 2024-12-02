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
        Schema::create('comment_replies', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('users')->onDelete('cascade');
            $table->boolean('show')->default(false);
            $table->boolean('pin')->default(false);
            $table->boolean('flag')->default(false);
            $table->boolean('like')->default(false);
            $table->boolean('dislike')->default(false);
            $table->timestamps();

            $table->index('comment_id');
            $table->index('account_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_replies');
    }
};
