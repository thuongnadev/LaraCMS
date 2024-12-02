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
        Schema::create('footer_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('footer_id')->nullable()->constrained('footers')->onDelete('cascade');
            $table->string('title');
            $table->enum('content_type', ['text', 'image', 'iframe', 'social_media', 'google_map', 'menu', 'business']);
            $table->text('text_content')->nullable();
            $table->string('image_content')->nullable();
            $table->text('iframe_content')->nullable();
            $table->text('google_map')->nullable();
            $table->foreignId('business_id')->nullable()->constrained('businesses');
            $table->foreignId('menu_id')->nullable()->constrained('menus');
            $table->integer('order');
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
        Schema::dropIfExists('footer_columns');
    }
};
