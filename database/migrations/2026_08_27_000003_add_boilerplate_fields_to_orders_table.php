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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'boilerplate_id')) {
                $table->foreignId('boilerplate_id')->nullable()->after('package_name')->constrained('portfolios')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'boilerplate_name')) {
                $table->string('boilerplate_name')->nullable()->after('boilerplate_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'boilerplate_id')) {
                $table->dropForeign(['boilerplate_id']);
                $table->dropColumn('boilerplate_id');
            }
            if (Schema::hasColumn('orders', 'boilerplate_name')) {
                $table->dropColumn('boilerplate_name');
            }
        });
    }
};
