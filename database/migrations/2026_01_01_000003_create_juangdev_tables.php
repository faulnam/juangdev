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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('role')->default('admin');
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service')->nullable();
            $table->string('budget')->nullable();
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read, replied
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('base_price')->default(0);
            $table->string('starting_price')->nullable();
            $table->string('delivery_time')->nullable();
            $table->boolean('popular')->default(false);
            $table->json('features')->nullable();
            $table->json('technologies')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('service_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('addon');
            $table->integer('price')->default(0);
            $table->boolean('popular')->default(false);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('design_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->string('badge')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // landing-page, company-profile, ecommerce, sistem-informasi, custom-app
            $table->string('name');
            $table->string('badge')->nullable();
            $table->string('price');
            $table->string('period')->default('proyek');
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->json('not_included')->nullable();
            $table->boolean('popular')->default(false);
            $table->string('cta_text')->default('Pilih Paket');
            $table->string('cta_href')->default('/contact');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('client')->nullable();
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('live_url')->nullable();
            $table->json('technologies')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('company')->nullable();
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->boolean('featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('image_url')->nullable();
            $table->string('category')->default('Web Development');
            $table->string('author')->default('JuangDev Team');
            $table->string('read_time')->default('5 min read');
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('design_tiers');
        Schema::dropIfExists('service_features');
        Schema::dropIfExists('services');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('admins');
    }
};
