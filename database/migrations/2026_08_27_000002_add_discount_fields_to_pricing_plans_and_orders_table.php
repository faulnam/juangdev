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
        Schema::table('pricing_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('pricing_plans', 'original_price')) {
                $table->string('original_price')->nullable()->after('price');
            }
            if (!Schema::hasColumn('pricing_plans', 'discount_percent') && !Schema::hasColumn('pricing_plans', 'discount_percentage')) {
                $table->integer('discount_percent')->nullable()->after('original_price');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'original_amount')) {
                $table->unsignedBigInteger('original_amount')->nullable()->after('addons');
            }
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->unsignedBigInteger('discount_amount')->default(0)->after('original_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'original_amount')) {
                $table->dropColumn('original_amount');
            }
            if (Schema::hasColumn('orders', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
        });

        Schema::table('pricing_plans', function (Blueprint $table) {
            if (Schema::hasColumn('pricing_plans', 'original_price')) {
                $table->dropColumn('original_price');
            }
            if (Schema::hasColumn('pricing_plans', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }
        });
    }
};
