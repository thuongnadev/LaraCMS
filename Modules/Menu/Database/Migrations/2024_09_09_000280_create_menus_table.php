<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Enums\LinkTarget;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Menu::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->nullableMorphs('linkable');
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('target', 10)->default(LinkTarget::Self);
            $table->unsignedBigInteger('page_id')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menu_items')
                  ->onDelete('set null');

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onDelete('set null');
        });

        Schema::create('menu_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Menu::class)->constrained()->cascadeOnDelete();
            $table->string('location')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('menu_locations'));
        Schema::dropIfExists(config('menu_items'));
        Schema::dropIfExists(config('menus'));
    }
};
