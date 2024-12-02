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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique()->nullable();
            $table->string('address');
            $table->string('phone'); 
            $table->string('email'); 
            $table->string('website')->nullable();
            $table->string('tax_code')->unique()->nullable(); 
            $table->text('description')->nullable(); 
            $table->timestamps(); 
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
