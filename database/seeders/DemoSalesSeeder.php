<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSalesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DemoCategorySeeder::class,
            DemoProductSeeder::class,
            DemoOrderSeeder::class,
            DemoStockMovementSeeder::class,
        ]);
    }
}
