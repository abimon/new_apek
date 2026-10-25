<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500);
            $table->json('content');
            $table->string('cover_image')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamps();
        });

        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('blog_category_post', function (Blueprint $table) {
            $table->foreignId('blog_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->primary(['blog_category_id', 'blog_post_id']);
        });

        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->foreignId('blog_tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->primary(['blog_tag_id', 'blog_post_id']);
        });

        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('email');
            $table->text('body');
            $table->timestamp('approved_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_category_post');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blog_posts');
    }
};
