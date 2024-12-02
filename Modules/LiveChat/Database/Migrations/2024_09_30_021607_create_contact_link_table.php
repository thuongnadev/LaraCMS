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
        Schema::create('contact_links', function (Blueprint $table) {
            $table->id();
            $table->string('facebook_messenger_link')->nullable();
            $table->string('zalo_link')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('text_color')->nullable();
            $table->enum('position', ['bottom-left', 'bottom-right'])->default('bottom-right');
            $table->integer('bottom_offset')->default(20)->comment('Khoảng cách từ đáy tính theo px');
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
        Schema::dropIfExists('contact_link');
    }
};
