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
        // Original product names grouped by category
        $menu = [
            'Hot Coffee' => [
                'Cafe Americano' => null,
                'Capuccino' => null,
                'Flat White' => null,
                'Macchiato' => null,
                'Matcha Latte' => null,
            ],
            'Iced Coffee' => [
                'Cafe Americano' => null,
                'Caramel Macchiato' => null,
                'Mocha/Choco Latte' => null,
                'Spanish Latte' => null,
                'Matcha Latte' => null,
                'Strawberry Matcha' => null,
                'Salted Caramel' => null,
                'Toffeenut Latte' => null,
            ],
            'Fruitea' => [
                'Fizz Soda' => null,
                'Lemon' => null,
                'Lychee' => null,
                'Kiwi' => null,
                'Blueberry' => null,
                'Strawberry' => null,
                'Passion Fruit' => null,
                'Green Apple' => null,
            ],
            'Milktea' => [
                'Brown Sugar' => null,
                'Wintermelon' => null,
                'Chocolate' => null,
                'Cookies & Cream' => null,
                'Salted Caramel' => null,
                'Matcha' => null,
            ],
            'Frappe' => [
                'Caramel' => null,
                'Salted Caramel' => null,
                'Matcha' => null,
                'Chocolate' => null,
                'Strawberry' => null,
            ],
            'Snacks' => [
                'Fries' => null,
                'Mini Donuts' => null,
                'Pancakes' => null,
                'Cookies' => null,
                'Brownies' => null,
            ],
        ];

        /**
         * Templates per category:
         * - sizes: numeric array of tier prices (first is base_price)
         * - toppings: array of choice arrays (key,label,price)
         */
        $templates = [
            'Hot Coffee' => [
                'sizes' => [60, 75], // small, medium
                'toppings' => [
                    ['key' => 'cheese', 'label' => 'Cheese', 'price' => 25],
                    ['key' => 'peanut', 'label' => 'Peanut Coating', 'price' => 30],
                ],
            ],
            'Iced Coffee' => [
                'sizes' => [89, 99], // small, medium
                'toppings' => [
                    ['key' => 'pearls', 'label' => 'Tapioca Pearls', 'price' => 20],
                    ['key' => 'cheese', 'label' => 'Cheese Foam', 'price' => 25],
                ],
            ],
            'Fruitea' => [
                'sizes' => [79, 89, 99], // s,m,l
                'toppings' => [
                    ['key' => 'pearls', 'label' => 'Pearls', 'price' => 20],
                    ['key' => 'extra_fruits', 'label' => 'Extra Fruits', 'price' => 20],
                    ['key' => 'spoon', 'label' => 'Edible Spoon', 'price' => 30],
                ],
            ],
            'Milktea' => [
                'sizes' => [39, 49, 59],
                'toppings' => [
                    ['key' => 'pearls', 'label' => 'Pearls', 'price' => 20],
                    ['key' => 'extra_sweet', 'label' => 'Extra Sweet', 'price' => 15],
                ],
            ],
            'Frappe' => [
                'sizes' => [99, 109], // 12oz, 16oz
                'toppings' => [
                    ['key' => 'whip', 'label' => 'Whipped Cream', 'price' => 10],
                    ['key' => 'shot', 'label' => 'Extra Shot', 'price' => 15],
                ],
            ],
            'Snacks' => [
                // Snacks will be handled with overrides per product below (single-tier)
                'sizes' => [],
                'toppings' => [],
            ],
        ];

        // explicit single-tier price overrides for Snacks (so we keep the original intended values)
        $snackOverrides = [
            'Fries' => 49,
            'Mini Donuts' => 99,
            'Pancakes' => 69,
            'Cookies' => 39,
            'Brownies' => 49,
        ];

        foreach ($menu as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();
            if (! $category) {
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'status' => 'active',
                    'display_order' => 99,
                ]);
            }

            $idx = 1;
            foreach ($items as $productName => $_) {
                // Determine template (if available)
                $tpl = $templates[$categoryName] ?? null;

                // Build customization JSON structure
                $custom = ['groups' => []];

                $basePrice = 0;
                $isCustomizable = false;

                if ($categoryName === 'Snacks') {
                    // Single-tier snack: use override price if present
                    $price = $snackOverrides[$productName] ?? 49;
                    $basePrice = (float) $price;
                    $custom['groups'][] = [
                        'key' => 'size',
                        'label' => 'Size',
                        'type' => 'single',
                        'choices' => [
                            ['key' => 'default', 'label' => 'Regular', 'price' => $basePrice]
                        ]
                    ];
                    $isCustomizable = true;
                } elseif ($tpl && !empty($tpl['sizes'])) {
                    $sizes = $tpl['sizes'];
                    // build size choices
                    $choices = [];
                    foreach ($sizes as $i => $p) {
                        // generate a stable key
                        $choiceKey = is_numeric($i) ? 'tier_' . ($i+1) : (string)$i;
                        // but if size label exists (like oz), try to use it; otherwise use S/M/L depending on count
                        $label = null;
                        if (count($sizes) === 2) {
                            $label = ($i === 0) ? 'Small' : 'Medium';
                        } elseif (count($sizes) === 3) {
                            $label = ($i === 0) ? 'Small' : (($i === 1) ? 'Medium' : 'Large');
                        } else {
                            $label = 'Tier ' . ($i+1);
                        }
                        $choices[] = [
                            'key' => $choiceKey,
                            'label' => $label,
                            'price' => (float) $p
                        ];
                    }

                    $custom['groups'][] = [
                        'key' => 'size',
                        'label' => 'Size',
                        'type' => 'single',
                        'required' => true,
                        'choices' => $choices
                    ];

                    // toppings if defined
                    if (!empty($tpl['toppings'])) {
                        $custom['groups'][] = [
                            'key' => 'toppings',
                            'label' => 'Add-ons',
                            'type' => 'multiple',
                            'choices' => $tpl['toppings']
                        ];
                    }

                    $basePrice = (float) ($sizes[0] ?? 0);
                    $isCustomizable = true;
                } else {
                    // fallback: not customizable, set base price to 49
                    $basePrice = 49;
                    $isCustomizable = false;
                }

                // Create SKU: CAT-<slug>-NNN
                $slug = Str::slug($productName);
                $sku = strtoupper(substr($category->slug,0,3)) . '-' . strtoupper(substr($slug,0,6)) . '-' . str_pad($idx, 3, '0', STR_PAD_LEFT);

                // Build a category-prefixed slug to ensure uniqueness across categories
                $uniqueSlug = Str::slug("{$category->slug}-{$productName}");

                // sensible default stocks: drinks higher, snacks lower
                $defaultStock = in_array($categoryName, ['Snacks']) ? 80 : 200;
                $lowStockAlert = max(5, (int)($defaultStock * 0.08));

                // final description is JSON string of the customization object — frontend expects JSON here
                $description = json_encode($custom, JSON_UNESCAPED_UNICODE);

                Product::updateOrCreate(
                    ['sku' => $sku],
                    [
                        'category_id' => $category->id,
                        'name' => $productName,
                        'slug' => $uniqueSlug,
                        'sku' => $sku,
                        'base_price' => $basePrice,
                        'customizable' => $isCustomizable ? true : false,
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
