<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('type'); // 'low_stock' | 'out_of_stock'
            $table->string('priority')->default('normal'); // 'critical' etc
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_alerts');
    }
}
