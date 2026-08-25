<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pricing_plans')) {
            DB::table('pricing_plans')
                ->where('is_active', 0)
                ->update(['is_active' => 1]);
        }

        if (Schema::hasTable('service_features')) {
            DB::table('service_features')
                ->where('is_active', 0)
                ->update(['is_active' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
