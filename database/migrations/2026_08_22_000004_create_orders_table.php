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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('token')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('project_name')->nullable();
            $table->string('service_name');
            $table->string('package_name')->nullable();
            $table->json('addons')->nullable();
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->unsignedBigInteger('dp_amount')->default(0);
            $table->unsignedBigInteger('remaining_amount')->default(0);
            $table->string('payment_scheme')->default('dp_50'); // 'dp_50' or 'full_100'
            $table->string('payment_status')->default('unpaid'); // 'unpaid', 'dp_paid', 'fully_paid'
            $table->string('project_status')->default('pending'); // 'pending', 'in_progress', 'completed', 'cancelled'
            $table->string('pakasir_trx_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
