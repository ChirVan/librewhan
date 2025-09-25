<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class DemoCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Hot Coffee',   'slug' => 'hot-coffee',   'status' => 'active', 'display_order' => 1],
            ['name' => 'Iced Coffee',  'slug' => 'iced-coffee',  'status' => 'active', 'display_order' => 2],
            ['name' => 'Fruitea',      'slug' => 'fruitea',      'status' => 'active', 'display_order' => 3],
            ['name' => 'Milktea',      'slug' => 'milktea',      'status' => 'active', 'display_order' => 4],
            ['name' => 'Frappe',       'slug' => 'frappe',       'status' => 'active', 'display_order' => 5],
            ['name' => 'Snacks',       'slug' => 'snacks',       'status' => 'active', 'display_order' => 6],
        ];

        foreach ($rows as $r) {
            Category::updateOrCreate(['slug' => $r['slug']], $r);
        }
    }
}
 