<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        // guard: ensure products exist
        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->warn('No products found, skipping DemoOrderSeeder.');
            return;
        }

        // detect columns
        $hasPaymentMode = Schema::hasColumn('orders', 'payment_mode');
        $hasPaymentMethod = Schema::hasColumn('orders', 'payment_method'); // try both if exists
        $orderItemsHasProductId = Schema::hasColumn('order_items', 'product_id');

        DB::transaction(function() use ($products, $hasPaymentMode, $hasPaymentMethod, $orderItemsHasProductId) {
            $faker = \Faker\Factory::create();
            $paymentMethods = ['cash', 'card', 'digital'];

            for ($i = 1; $i <= 30; $i++) {
                $createdAt = Carbon::now()->subDays(rand(0, 60))->subMinutes(rand(0, 1440));
                $itemsCount = rand(1, 4);
                $orderNumber = 'ORD-' . str_pad($i, 4, '0', STR_PAD_LEFT);

                $orderData = [
                    'order_number' => $orderNumber,
                    'customer_name' => $faker->name,
                    'order_type' => $faker->randomElement(['dine-in','takeaway']),
                    'subtotal' => 0,
                    'total' => 0,
                    'amount_paid' => 0,
                    'change_due' => 0,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                $pm = $faker->randomElement($paymentMethods);
                if ($hasPaymentMode) $orderData['payment_mode'] = $pm;
                if ($hasPaymentMethod) $orderData['payment_method'] = $pm;

                $order = Order::create($orderData);

                $subtotal = 0;
                $items = $products->random($itemsCount);
                foreach ($items as $prod) {
                    $qty = rand(1,3);
                    $price = (float)$prod->base_price;
                    $lineTotal = $price * $qty;

                    $oiData = [
                        'order_id' => $order->id,
                        'name' => $prod->name,
                        'size' => null,
                        'toppings' => null,
                        'sugar' => null,
                        'ice' => null,
                        'milk' => null,
                        'instructions' => null,
                        'price' => $price,
                        'qty' => $qty,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->created_at,
                    ];

                    if ($orderItemsHasProductId) {
                        $oiData['product_id'] = $prod->id;
                    }

                    OrderItem::create($oiData);

                    $subtotal += $lineTotal;

                    // Optionally decrement stock (but keep simple)
                    if ($prod->current_stock !== null) {
                        $prod->current_stock = max(0, $prod->current_stock - $qty);
                        $prod->save();
                    }
                }

                $tax = round($subtotal * 0.12, 2); // example 12% VAT
                $discount = 0;
                $total = round($subtotal + $tax - $discount, 2);
                $amountPaid = $total;
                $change = 0;

                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'amount_paid' => $amountPaid,
                    'change_due' => $change,
                ]);
            }
        });
    }
}
