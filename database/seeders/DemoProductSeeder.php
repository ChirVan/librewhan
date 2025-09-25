<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class DemoProductSeeder extends Seeder
{
    public function run(): void
    {
        // Official menu data (name => price string). Price strings are '|' delimited sizes (small|medium|large or two-tier).
        $menu = [
            'Hot Coffee' => [
                'Cafe Americano'      => '60|75',
                'Capuccino'          => '60|75',
                'Flat White'         => '60|75',
                'Macchiato'          => '60|75',
                'Matcha Latte'       => '60|75',
            ],
            'Iced Coffee' => [
                'Cafe Americano'      => '89|99',
                'Caramel Macchiato'   => '89|99',
                'Mocha/Choco Latte'   => '89|99',
                'Spanish Latte'       => '89|99',
                'Matcha Latte'        => '89|99',
                'Strawberry Matcha'   => '89|99',
                'Salted Caramel'      => '89|99',
                'Toffeenut Latte'     => '89|99',
            ],
            'Fruitea' => [
                'Fizz Soda'           => '79|89|99',
                'Lemon'               => '39|49|59',
                'Lychee'              => '39|49|59',
                'Kiwi'                => '39|49|59',
                'Blueberry'           => '39|49|59',
                'Strawberry'          => '39|49|59',
                'Passion Fruit'       => '39|49|59',
                'Green Apple'         => '39|49|59',
            ],
            'Milktea' => [
                'Brown Sugar'         => '39|49|59',
                'Wintermelon'         => '39|49|59',
                'Chocolate'           => '39|49|59',
                'Cookies & Cream'     => '39|49|59',
                'Salted Caramel'      => '39|49|59',
                'Matcha'              => '39|49|59',
            ],
            'Frappe' => [
                'Caramel'             => '99|109',
                'Salted Caramel'      => '99|109',
                'Matcha'              => '99|109',
                'Chocolate'           => '99|109',
                'Strawberry'          => '99|109',
            ],
            'Snacks' => [
                'Fries'               => '49',
                'Mini Donuts'         => '99',
                'Pancakes'            => '69',
                'Cookies'             => '39',
                'Brownies'            => '49',
            ],
        ];

        foreach ($menu as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();
            if (! $category) {
                // create category as fallback
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'status' => 'active',
                    'display_order' => 99,
                ]);
            }

            $idx = 1;
            foreach ($items as $productName => $priceString) {
                // Parse prices; choose the smallest price as base_price
                $parts = array_filter(array_map('trim', explode('|', $priceString)));
                $numeric = array_map(function($p){ return (float) preg_replace('/[^\d.]/','',$p); }, $parts);
                sort($numeric, SORT_NUMERIC);
                $basePrice = $numeric[0] ?? 0;

                // Create SKU: CAT-<slug>-NNN
                $slug = Str::slug($productName);
                $sku = strtoupper(substr($category->slug,0,3)) . '-' . strtoupper(substr($slug,0,6)) . '-' . str_pad($idx, 3, '0', STR_PAD_LEFT);

                // Build a category-prefixed slug to ensure uniqueness across categories
                $uniqueSlug = Str::slug("{$category->slug}-{$productName}");

                // Description will include the price tiers for frontend to display
                $description = "Price tiers: " . implode(' | ', $parts);

                // sensible default stocks: drinks higher, snacks lower
                $defaultStock = in_array($categoryName, ['Snacks']) ? 80 : 200;
                $lowStockAlert = max(5, (int)($defaultStock * 0.08));

                Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'category_id' => $category->id,
                        'name' => $productName,
                        'slug' => $uniqueSlug,
                        'sku' => $sku,
                        'base_price' => $basePrice,
                        'customizable' => false,
                        'status' => 'active',
                        'current_stock' => $defaultStock,
                        'low_stock_alert' => $lowStockAlert,
                        'display_order' => $idx,
                        'description' => $description,
                    ]
                );

                $idx++;
            }
        }
    }
}
