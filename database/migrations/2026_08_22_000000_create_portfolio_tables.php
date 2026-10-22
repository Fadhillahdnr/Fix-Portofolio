<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->index();
        });
        Schema::create('profiles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('headline');
            $t->text('short_bio');
            $t->longText('full_bio')->nullable();
            $t->string('location')->nullable();
            $t->string('availability')->nullable();
            $t->string('avatar_path')->nullable();
            $t->string('resume_path')->nullable();
            $t->timestamps();
        });
        Schema::create('experiences', function (Blueprint $t) {
            $t->id();
            $t->string('company');
            $t->string('position');
            $t->string('employment_type')->nullable();
            $t->text('description');
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->boolean('is_current')->default(false);
            $t->unsignedInteger('sort_order')->default(0)->index();
            $t->timestamps();
        });
        Schema::create('skills', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('category')->nullable()->index();
            $t->string('icon_path')->nullable();
            $t->unsignedTinyInteger('proficiency')->nullable();
            $t->unsignedInteger('sort_order')->default(0)->index();
            $t->boolean('is_featured')->default(false)->index();
            $t->timestamps();
        });
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->timestamps();
        });
        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('summary');
            $t->longText('problem')->nullable();
            $t->longText('solution')->nullable();
            $t->longText('result')->nullable();
            $t->string('role')->nullable();
            $t->string('cover_path')->nullable();
            $t->string('cover_public_id')->nullable();
            $t->string('demo_url')->nullable();
            $t->string('repository_url')->nullable();
            $t->string('status')->default('draft')->index();
            $t->timestampTz('published_at')->nullable()->index();
            $t->boolean('is_featured')->default(false)->index();
            $t->unsignedInteger('sort_order')->default(0)->index();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestampsTz();
        });
        Schema::create('category_project', function (Blueprint $t) {
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->primary(['project_id', 'category_id']);
        });
        Schema::create('project_skill', function (Blueprint $t) {
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $t->primary(['project_id', 'skill_id']);
        });
        Schema::create('project_media', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->string('path');
            $t->string('public_id');
            $t->string('media_type');
            $t->string('alt_text');
            $t->string('caption')->nullable();
            $t->unsignedInteger('width')->nullable();
            $t->unsignedInteger('height')->nullable();
            $t->unsignedInteger('sort_order')->default(0)->index();
            $t->timestamps();
        });
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('description');
            $t->string('icon')->nullable();
            $t->unsignedInteger('sort_order')->default(0)->index();
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
        });
        Schema::create('social_links', function (Blueprint $t) {
            $t->id();
            $t->string('platform');
            $t->string('label');
            $t->string('url');
            $t->string('icon')->nullable();
            $t->unsignedInteger('sort_order')->default(0)->index();
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('subject');
            $t->text('message');
            $t->string('status')->default('new')->index();
            $t->string('ip_hash', 64)->nullable();
            $t->timestampsTz();
        });
        Schema::create('site_settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->json('value')->nullable();
            $t->string('group')->default('general')->index();
            $t->boolean('is_public')->default(false)->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['site_settings', 'contact_messages', 'social_links', 'services', 'project_media', 'project_skill', 'category_project', 'projects', 'categories', 'skills', 'experiences', 'profiles'] as $table) {
            Schema::dropIfExists($table);
        } Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
