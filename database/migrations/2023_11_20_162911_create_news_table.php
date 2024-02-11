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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('social_title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('upper_title')->nullable();

            $table->string('title_color')->nullable();
            $table->string('title_size')->nullable();
            $table->string('sub_title_color')->nullable();
            $table->string('upper_title_color')->nullable();

            $table->longText('news_body')->nullable();
            $table->mediumText('summary')->nullable();
            $table->mediumText('social_summary')->nullable();
            //image
            $table->bigInteger('thumbnail')->nullable();
            $table->bigInteger('social_thumbnail')->nullable();

            $table->foreignId('author_id')->nullable()->constrained(
                table: 'authors',
                indexName: 'id'
            );

            $table->json('position')->nullable();
            $table->boolean('show_created_at')->default(1);
            $table->boolean('show_updated_at')->default(1);
            $table->boolean('show_thumbnail')->default(1);
            $table->enum('language', ['en', 'bn'])->default('bn');
            $table->enum('status', [
                'draft',
                'reviewing',
                'published',
            ])->default('draft');
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

        });

        Schema::create('category_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('news_id');

            $table->timestamps();
        });

        Schema::create('news_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id');
            $table->foreignId('tag_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
