<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->foreignId('account_id')->constrained('users')->onDelete('cascade');
            $table->text('text')->nullable();
            $table->boolean('show')->default(false);
            $table->boolean('pin')->default(false);
            $table->boolean('flag')->default(false);
            $table->boolean('like')->default(false);
            $table->boolean('dislike')->default(false);
            $table->timestamps();

            $table->index('account_id');
            $table->index(['commentable_id', 'commentable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
