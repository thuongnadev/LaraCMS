<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::create('comp_page_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comp_page_id')->constrained('comp_pages')->onDelete('cascade');
            $table->foreignId('comp_config_id')->constrained('comp_configs')->onDelete('cascade');
            $table->longText('value');
            $table->string('type')->default('string');
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
        Schema::dropIfExists('page_component_configuration_values');
    }
};
