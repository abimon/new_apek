<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('tech_stacks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->string('summary');
            $table->text('challenge');
            $table->text('solution');
            $table->json('screenshots')->nullable();
            $table->string('live_url')->nullable();
            $table->string('accent')->default('#D4A373');
            $table->boolean('owner_involvement')->default(true);
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
            $table->index(['is_published', 'is_featured']);
        });

        Schema::create('category_project', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'project_id']);
        });

        Schema::create('project_tech_stack', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tech_stack_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'tech_stack_id']);
        });

        Schema::create('contact_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->index();
            $table->string('company')->nullable();
            $table->string('service')->nullable();
            $table->text('message');
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->timestamps();
        });

        Schema::create('analytics_logs', function (Blueprint $table) {
            $table->id();
            $table->string('path')->index();
            $table->string('session_hash', 64)->index();
            $table->string('event')->default('page_view')->index();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('referrer')->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_logs');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('contact_inquiries');
        Schema::dropIfExists('project_tech_stack');
        Schema::dropIfExists('category_project');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('tech_stacks');
        Schema::dropIfExists('categories');
    }
};