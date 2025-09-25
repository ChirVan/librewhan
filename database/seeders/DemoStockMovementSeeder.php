<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DemoStockMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('stock_movements')) {
            return;
        }

        $products = Product::take(8)->get();
        foreach ($products as $p) {
            StockMovement::create([
                'product_id' => $p->id,
                'old_quantity' => max(0, $p->current_stock + 10),
                'new_quantity' => $p->current_stock,
                'difference' => $p->current_stock - ($p->current_stock + 10),
                'reason' => 'Demo order adjustments',
                'adjustment_type' => 'subtract',
                'user_id' => null,
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
                'updated_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
