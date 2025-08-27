<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        StockMovement::insert([
            [
                'product_id' => 1,
                'old_quantity' => 10,
                'new_quantity' => 20,
                'difference' => 10,
                'reason' => 'Initial restock',
                'adjustment_type' => 'add',
                'user_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 2,
                'old_quantity' => 5,
                'new_quantity' => 0,
                'difference' => -5,
                'reason' => 'Sold out',
                'adjustment_type' => 'subtract',
                'user_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'product_id' => 1,
                'old_quantity' => 20,
                'new_quantity' => 15,
                'difference' => -5,
                'reason' => 'Damaged items',
                'adjustment_type' => 'subtract',
                'user_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
