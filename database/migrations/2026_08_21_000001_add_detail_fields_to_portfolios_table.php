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
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('client_industry')->nullable()->after('client');
            $table->string('duration')->nullable()->after('client_industry');
            $table->text('overview')->nullable()->after('description');
            $table->json('key_features')->nullable()->after('overview');
            $table->json('gallery')->nullable()->after('key_features');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['client_industry', 'duration', 'overview', 'key_features', 'gallery']);
        });
    }
};
