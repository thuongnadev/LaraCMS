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
        Schema::create('comment_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('comment_reply_id')->nullable()->constrained('comment_replies')->onDelete('cascade');
            $table->text('file');
            $table->timestamps();

            // Thêm index cho các cột khóa ngoại
            $table->index('comment_id');
            $table->index('comment_reply_id');

            // Thêm composite index cho trường hợp truy vấn phổ biến
            $table->index(['comment_id', 'created_at']);
            $table->index(['comment_reply_id', 'created_at']);

            // Thêm index cho cột timestamp để hỗ trợ sắp xếp và lọc theo thời gian
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
        Schema::dropIfExists('comment_files');
    }
};
