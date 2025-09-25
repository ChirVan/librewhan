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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size'); // Small, Medium, Large or Single
            $table->decimal('price', 8, 2);
            $table->boolean('available')->default(true);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();
            
            // Unique constraint: one size per product
            $table->unique(['product_id', 'size']);
            $table->index(['product_id', 'available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};