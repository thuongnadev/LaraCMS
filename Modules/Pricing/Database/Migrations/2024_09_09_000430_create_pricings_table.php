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
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('name', '255');
            $table->boolean('show')
                ->default(false);
            $table->timestamps();

            $table->foreignId('pricing_type_id')
                ->nullable()
                ->constrained('pricing_types')
                ->onDelete('cascade');
            $table->foreignId('pricing_group_id')
                ->nullable()
                ->constrained('pricing_groups')
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
        Schema::dropIfExists('pricings');
    }
};
