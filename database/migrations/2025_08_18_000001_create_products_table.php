<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->decimal('base_price',8,2)->default(0);
            $table->boolean('customizable')->default(false);
            $table->string('status',20)->default('active');
            $table->unsignedInteger('current_stock')->default(0);
            $table->unsignedInteger('low_stock_alert')->default(0);
            $table->unsignedInteger('display_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['category_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
