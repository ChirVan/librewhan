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
            $table->string('order_type')->nullable()->after('customer_name');
            $table->string('payment_mode')->nullable()->after('order_type');
            $table->decimal('subtotal', 10, 2)->default(0)->after('payment_mode');
            $table->decimal('total', 10, 2)->default(0)->after('subtotal');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('total');
            $table->decimal('change_due', 10, 2)->default(0)->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'payment_mode', 'subtotal', 'total', 'amount_paid', 'change_due']);
        });
    }
};
