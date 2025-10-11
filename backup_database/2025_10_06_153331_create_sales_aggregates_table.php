<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// class CreateSalesAggregatesTable extends Migration
// {
//     public function up()
//     {
//         Schema::create('sales_aggregates', function (Blueprint $table) {
//             $table->id();
//             $table->unsignedBigInteger('product_id')->index();
//             $table->date('date')->index();
//             $table->unsignedBigInteger('quantity')->default(0);
//             $table->decimal('revenue', 14, 2)->default(0);
//             $table->timestamps();

//             $table->unique(['product_id', 'date'], 'sales_aggregates_product_date_unique');
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('sales_aggregates');
//     }
// }
