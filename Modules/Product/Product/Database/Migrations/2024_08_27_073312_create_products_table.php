<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')
                ->unique();
            $table->text('description')
                ->nullable();
            $table->text('content')
                ->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft');
            $table->string('seo_title')
                ->nullable();
            $table->text('seo_description')
                ->nullable();
            $table->text('seo_keywords')
                ->nullable();
            $table->timestamp('published_at')
                ->nullable();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('products');
    }
}
