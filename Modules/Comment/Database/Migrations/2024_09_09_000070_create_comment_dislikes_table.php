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
        Schema::create('comment_dislikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('reply_id')->nullable()->constrained('comment_replies')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('comment_id');
            $table->index('reply_id');
            $table->index('account_id');

            // Thêm composite index cho các trường thường được sử dụng cùng nhau
            $table->index(['comment_id', 'account_id']);
            $table->index(['reply_id', 'account_id']);

            // Thêm unique constraint để đảm bảo mỗi user chỉ có thể like một comment/reply một lần
            $table->unique(['comment_id', 'reply_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_dislikes');
    }
};
