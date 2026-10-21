<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('portfolio_enabled')->default(false);
            $table->string('portfolio_slug', 40)->nullable()->unique();
            $table->boolean('portfolio_is_public')->default(false);
            $table->string('professional_title', 160)->nullable();
            $table->text('portfolio_summary')->nullable();
            $table->string('portfolio_email')->nullable();
            $table->string('portfolio_phone', 40)->nullable();
            $table->string('portfolio_location', 160)->nullable();
            $table->string('portfolio_website')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('professional_photo')->nullable();
            $table->json('portfolio_skills')->nullable();
            $table->json('portfolio_experience')->nullable();
            $table->json('portfolio_education')->nullable();
            $table->json('portfolio_certifications')->nullable();
            $table->json('portfolio_languages')->nullable();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('portfolio_owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('portfolio_review_status', 20)->default('not_submitted')->index();
            $table->foreignId('portfolio_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('portfolio_reviewed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('portfolio_owner_id');
            $table->dropConstrainedForeignId('portfolio_reviewed_by');
            $table->dropColumn(['portfolio_review_status', 'portfolio_reviewed_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['portfolio_slug']);
            $table->dropColumn([
                'portfolio_enabled',
                'portfolio_slug',
                'portfolio_is_public',
                'professional_title',
                'portfolio_summary',
                'portfolio_email',
                'portfolio_phone',
                'portfolio_location',
                'portfolio_website',
                'linkedin_url',
                'github_url',
                'professional_photo',
                'portfolio_skills',
                'portfolio_experience',
                'portfolio_education',
                'portfolio_certifications',
                'portfolio_languages',
            ]);
        });
    }
};
