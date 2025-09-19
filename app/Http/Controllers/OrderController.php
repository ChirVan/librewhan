<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function pending()
    {
        // In a real application, you would fetch pending orders from database
        // For now, we'll use sample data in the view
        return view('orders.pendingOrder');
    }

    public function take()
    {
        // Sample product data - you can replace this with database queries later
        $products = [
            [
                'id' => 1,
                'name' => 'Espresso',
                'category' => 'Coffee',
                'image' => 'espresso.jpg',
                'description' => 'Rich and bold espresso shot'
            ],
            [
                'id' => 2,
                'name' => 'Cappuccino',
                'category' => 'Coffee',
                'image' => 'cappuccino.jpg',
                'description' => 'Espresso with steamed milk foam'
            ],
            [
                'id' => 3,
                'name' => 'Latte',
                'category' => 'Coffee',
                'image' => 'latte.jpg',
                'description' => 'Espresso with steamed milk'
            ],
            [
                'id' => 4,
                'name' => 'Americano',
                'category' => 'Coffee',
                'image' => 'americano.jpg',
                'description' => 'Espresso with hot water'
            ],
            [
                'id' => 5,
                'name' => 'Croissant',
                'price' => 2.95,
                'category' => 'Pastry',
                'image' => 'croissant.jpg',
                'description' => 'Buttery flaky pastry'
            ],
            [
                'id' => 6,
                'name' => 'Blueberry Muffin',
                'price' => 3.45,
                'category' => 'Pastry',
                'image' => 'muffin.jpg',
                'description' => 'Fresh blueberry muffin'
            ],
            [
                'id' => 7,
                'name' => 'Caesar Salad',
                'price' => 8.95,
                'category' => 'Food',
                'image' => 'caesar.jpg',
                'description' => 'Fresh romaine with caesar dressing'
            ],
            [
                'id' => 8,
                'name' => 'Club Sandwich',
                'price' => 9.75,
                'category' => 'Food',
                'image' => 'sandwich.jpg',
                'description' => 'Triple-decker club sandwich'
            ]
        ];

        $categories = ['All', 'Coffee', 'Pastry', 'Food'];
        
        return view('orders.takeOrder', compact('products', 'categories'));
    }

    public function history()
    {
    // In a real application, you would fetch completed orders from database
    // You can add pagination, filtering, and sorting here
    return view('orders.history');
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:255',
                'payment_method' => 'required|string',
                'total_amount' => 'required|numeric',
                'tax_amount' => 'nullable|numeric',
                'discount_amount' => 'nullable|numeric',
                'status' => 'nullable|string',
                'user_id' => 'nullable|integer',
                'notes' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string',
                'items.*.size' => 'nullable|string',
                'items.*.toppings' => 'nullable|array',
                'items.*.sugar' => 'nullable|string',
                'items.*.ice' => 'nullable|string',
                'items.*.milk' => 'nullable|string',
                'items.*.instructions' => 'nullable|string',
                'items.*.price' => 'required|numeric',
                'items.*.qty' => 'required|integer|min:1',
            ]);

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'payment_method' => $validated['payment_method'],
                'total_amount' => $validated['total_amount'],
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'status' => $validated['status'] ?? 'pending',
                'user_id' => $validated['user_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $item['name'],
                    'size' => $item['size'] ?? null,
                    'toppings' => isset($item['toppings']) ? implode(',', $item['toppings']) : null,
                    'sugar' => $item['sugar'] ?? null,
                    'ice' => $item['ice'] ?? null,
                    'milk' => $item['milk'] ?? null,
                    'instructions' => $item['instructions'] ?? null,
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Order created successfully', 'order_id' => $order->id]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Handle order update logic here
        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }

    public function destroy($id)
    {
        // Handle order deletion logic here
        return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
    }

    // New methods for order status management
    public function updateStatus(Request $request, $id)
    {
        // Validate the status
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed'
        ]);

        // In a real application, you would update the database
        // For now, we'll return a success response
        return response()->json([
            'success' => true, 
            'message' => 'Order status updated successfully',
            'order_id' => $id,
            'new_status' => $request->status
        ]);
    }

    public function complete($id)
    {
        // In a real application, you would:
        // 1. Update order status to 'completed'
        // 2. Move to order history
        // 3. Update inventory if needed
        
        return response()->json([
            'success' => true,
            'message' => 'Order completed successfully',
            'order_id' => $id
        ]);
    }
}