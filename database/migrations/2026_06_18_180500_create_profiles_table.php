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
        $fields = [];
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->longText('education')->nullable();
            $table->longText('experience')->nullable();
            $table->longText('skills')->nullable();
            $table->longText('certifications')->nullable();
            $table->string('languages')->nullable();
            $table->string('hobbies')->nullable();
            $table->longText('references')->nullable();
            $table->string('profile_picture')->nullable();
            $table->longText('resume')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('portfolio')->nullable();
            $table->string('website')->nullable();
            $table->string('bio')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->longText('social_media_links')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
