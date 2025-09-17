<?php

namespace App\Http\Controllers;

use App\Models\Order; // for findOrFail($id)
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function pending()
    {
        // Fetch pending orders
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

    public function manage()
    {
        // Fetch all orders
        return view('orders.manageOrders');
    }

    public function history()
    {
        // Fetch completed orders
        return view('orders.history');
    }

    public function store(Request $request)
    {
        // Handle order creation logic here
        return response()->json(['success' => true, 'message' => 'Order created successfully']);
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
