<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('title', 255);
            $table->string('slug', 255)
                ->unique();
            $table->text('summary')
                ->nullable();
            $table->longText('content');
            $table->enum('status', ['draft', 'published', 'archived'])
                ->default('draft');
            $table->string('seo_title', 255)
                ->nullable();
            $table->text('seo_description')
                ->nullable();
            $table->text('seo_keywords')
                ->nullable();
            $table->timestamp('published_at')
                ->nullable();
            $table->timestamps();

            $table->foreignId('author_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('editor_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
        });

        DB::unprepared('
            CREATE TRIGGER update_published_at_and_updated_at
            BEFORE UPDATE ON posts
            FOR EACH ROW
            BEGIN
                IF NEW.status = "published" AND OLD.status != "published" THEN
                    SET NEW.published_at = NOW();
                END IF;
                SET NEW.updated_at = NOW();
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_published_at_and_updated_at');
        Schema::dropIfExists('posts');
    }
};
