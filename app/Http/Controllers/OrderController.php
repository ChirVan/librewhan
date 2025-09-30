<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'nullable|string|max:255',
        'customer_phone' => 'nullable|string|max:255',
        'order_type' => 'required|string|in:dine-in,takeaway',
        'payment_mode' => 'required|string',
        'subtotal' => 'nullable|numeric|min:0',
        'total' => 'nullable|numeric|min:0',
        'amount_paid' => 'nullable|numeric|min:0',
        'change_due' => 'nullable|numeric|min:0',
        'status' => 'nullable|string',
        'user_id' => 'nullable|integer',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer|exists:products,id',
        'items.*.name' => 'required|string',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.qty' => 'required|integer|min:1',
        // optional fields for customization
        'items.*.size' => 'nullable|string',
        'items.*.toppings' => 'nullable|array',
        'items.*.sugar' => 'nullable|string',
        'items.*.ice' => 'nullable|string',
        'items.*.milk' => 'nullable|string',
        'items.*.instructions' => 'nullable|string',
    ]);

    // Ensure order_type is always valid
    if (!in_array($validated['order_type'], ['dine-in', 'takeaway'])) {
        $validated['order_type'] = 'dine-in';
    }

    // tolerate unauthenticated test calls: prefer validated user_id, then logged-in user id, then null
    $userId = $validated['user_id'] ?? optional(auth())->id() ?? null;

    try {
        $order = DB::transaction(function () use ($validated, $userId) {
            // compute subtotal/total if client didn't
            $computedSubtotal = 0;
            foreach ($validated['items'] as $it) {
                $computedSubtotal += ($it['price'] * $it['qty']);
            }
            $subtotal = $validated['subtotal'] ?? $computedSubtotal;
            $total = $validated['total'] ?? $subtotal; // taxes/discounts can be applied later

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_name' => $validated['customer_name'] ?? null,
                'order_type' => $validated['order_type'] ?? null,
                'payment_mode' => $validated['payment_mode'],
                'subtotal' => $subtotal,
                'total' => $total,
                'amount_paid' => $validated['amount_paid'] ?? 0,
                'change_due' => $validated['change_due'] ?? 0,
                'status' => $validated['status'] ?? 'pending',
                'user_id' => $userId,
                'notes' => $validated['notes'] ?? null,
            ]);

            // create order items, update stock, log movements
            foreach ($validated['items'] as $it) {
                $productId = $it['product_id'];
                $qty = (int)$it['qty'];
                $price = $it['price'];

                // lock the product row to avoid race conditions
                $product = Product::where('id', $productId)->lockForUpdate()->first();
                if (! $product) {
                    throw new \Exception("Product #{$productId} not found.");
                }

                // Prevent negative stock (change behavior here if you prefer)
                if ($product->current_stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => ["Insufficient stock for product: {$product->name} (available: {$product->current_stock})"]
                    ]);
                }

                $oldQty = $product->current_stock;
                $product->current_stock = max(0, $product->current_stock - $qty);
                $product->save();

                // create order item
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $it['name'],
                    'size' => $it['size'] ?? null,
                    'toppings' => isset($it['toppings']) ? (is_array($it['toppings']) ? implode(',', $it['toppings']) : $it['toppings']) : null,
                    'sugar' => $it['sugar'] ?? null,
                    'ice' => $it['ice'] ?? null,
                    'milk' => $it['milk'] ?? null,
                    'instructions' => $it['instructions'] ?? null,
                    'price' => $price,
                    'qty' => $qty,
                ]);

                // log stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'old_quantity' => $oldQty,
                    'new_quantity' => $product->current_stock,
                    'difference' => $product->current_stock - $oldQty,
                    'reason' => 'order:' . $order->order_number,
                    'adjustment_type' => 'subtract',
                    'user_id' => $userId,
                ]);
            }

            return $order;
        }, 5); // retry attempts for deadlocks

        return response()->json(['success' => true, 'order_id' => $order->id], 201);
    } catch (ValidationException $ve) {
        // forward validation error (stock insufficiency etc.)
        throw $ve;
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function pending()
    {
        $orders = \App\Models\Order::with('items')
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = $orders->where('status', 'pending')->count();
        $preparingCount = $orders->where('status', 'preparing')->count();
        $readyCount = $orders->where('status', 'ready')->count();
        $totalValue = $orders->sum('total');

        return view('orders.pendingOrder', compact('orders', 'pendingCount', 'preparingCount', 'readyCount', 'totalValue'));

    }

    public function updateStatus(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:pending,preparing,ready,completed'
        ]);
        $order->status = $request->input('status');
        $order->save();
        return redirect()->back()->with('success', 'Order status updated!');
    }

    public function take()
    {
        // Sample product data - you can replace this with database queries later
        $products = [
            // [
            //     'id' => 1,
            //     'name' => 'Espresso',
            //     'category' => 'Coffee',
            //     'image' => 'espresso.jpg',
            //     'description' => 'Rich and bold espresso shot'
            // ],
            // [
            //     'id' => 2,
            //     'name' => 'Cappuccino',
            //     'category' => 'Coffee',
            //     'image' => 'cappuccino.jpg',
            //     'description' => 'Espresso with steamed milk foam'
            // ],
            // [
            //     'id' => 3,
            //     'name' => 'Latte',
            //     'category' => 'Coffee',
            //     'image' => 'latte.jpg',
            //     'description' => 'Espresso with steamed milk'
            // ],
            // [
            //     'id' => 4,
            //     'name' => 'Americano',
            //     'category' => 'Coffee',
            //     'image' => 'americano.jpg',
            //     'description' => 'Espresso with hot water'
            // ],
            // [
            //     'id' => 5,
            //     'name' => 'Croissant',
            //     'price' => 2.95,
            //     'category' => 'Pastry',
            //     'image' => 'croissant.jpg',
            //     'description' => 'Buttery flaky pastry'
            // ],
            // [
            //     'id' => 6,
            //     'name' => 'Blueberry Muffin',
            //     'price' => 3.45,
            //     'category' => 'Pastry',
            //     'image' => 'muffin.jpg',
            //     'description' => 'Fresh blueberry muffin'
            // ],
            // [
            //     'id' => 7,
            //     'name' => 'Caesar Salad',
            //     'price' => 8.95,
            //     'category' => 'Food',
            //     'image' => 'caesar.jpg',
            //     'description' => 'Fresh romaine with caesar dressing'
            // ],
            // [
            //     'id' => 8,
            //     'name' => 'Club Sandwich',
            //     'price' => 9.75,
            //     'category' => 'Food',
            //     'image' => 'sandwich.jpg',
            //     'description' => 'Triple-decker club sandwich'
            // ]
        ];

        $categories = [
            // 'All', 
            // 'Coffee', 
            // 'Pastry', 
            // 'Food'
        ];
        
        return view('orders.takeOrder', compact('products', 'categories'));
    }

    public function history()
    {
        // Fetch completed orders, with optional filters and pagination
        $query = Order::with('items')->where('status', 'completed');

        // Filtering
        $search = request('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhere('customer_phone', 'like', "%$search%")
                  ->orWhere('payment_method', 'like', "%$search%")
                  ->orWhere('order_type', 'like', "%$search%")
                  ;
            });
        }

        // Date range filter
        $dateFilter = request('date_filter');
        $fromDate = request('from_date');
        $toDate = request('to_date');
        if ($dateFilter && $dateFilter !== 'custom') {
            $today = now();
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', $today->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $today->subDay()->toDateString());
                    break;
                case 'this-week':
                    $query->whereBetween('created_at', [$today->startOfWeek(), $today->endOfWeek()]);
                    break;
                case 'last-week':
                    $query->whereBetween('created_at', [$today->subWeek()->startOfWeek(), $today->subWeek()->endOfWeek()]);
                    break;
                case 'this-month':
                    $query->whereMonth('created_at', $today->month)->whereYear('created_at', $today->year);
                    break;
                case 'last-month':
                    $lastMonth = $today->copy()->subMonth();
                    $query->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year);
                    break;
            }
        } elseif ($dateFilter === 'custom' && $fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Order type filter
        $typeFilter = request('type_filter');
        if ($typeFilter && $typeFilter !== 'all') {
            $query->where('order_type', $typeFilter);
        }

        // Sorting
        $sortFilter = request('sort_filter', 'newest');
        switch ($sortFilter) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'highest':
                $query->orderBy('total', 'desc');
                break;
            case 'lowest':
                $query->orderBy('total', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = request('per_page', 25);
        $orders = $query->paginate($perPage)->appends(request()->query());

        // Statistics
        $totalOrders = $orders->total();
        $totalRevenue = $orders->sum('total');
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $todayOrders = $orders->filter(function($order) {
            return $order->created_at->isToday();
        })->count();

        return view('orders.history', compact('orders', 'totalOrders', 'totalRevenue', 'avgOrder', 'todayOrders'));
    }

    // public function store(Request $request)
    // {
    //     try {

    //         $validated = $request->validate([
    //             'customer_name' => 'nullable|string|max:255',
    //             'customer_phone' => 'nullable|string|max:255',
    //             'payment_method' => 'required|string',
    //             'total_amount' => 'required|numeric',
    //             'tax_amount' => 'nullable|numeric',
    //             'discount_amount' => 'nullable|numeric',
    //             'status' => 'nullable|string',
    //             'user_id' => 'nullable|integer',
    //             'notes' => 'nullable|string',
    //             'items' => 'required|array|min:1',
    //             'items.*.name' => 'required|string',
    //             'items.*.size' => 'nullable|string',
    //             'items.*.toppings' => 'nullable|array',
    //             'items.*.sugar' => 'nullable|string',
    //             'items.*.ice' => 'nullable|string',
    //             'items.*.milk' => 'nullable|string',
    //             'items.*.instructions' => 'nullable|string',
    //             'items.*.price' => 'required|numeric',
    //             'items.*.qty' => 'required|integer|min:1',
    //         ]);

    //         $order = Order::create([
    //             'order_number' => 'ORD-' . strtoupper(uniqid()),
    //             'customer_name' => $validated['customer_name'] ?? null,
    //             'customer_phone' => $validated['customer_phone'] ?? null,
    //             'payment_method' => $validated['payment_method'],
    //             'total_amount' => $validated['total_amount'],
    //             'tax_amount' => $validated['tax_amount'] ?? 0,
    //             'discount_amount' => $validated['discount_amount'] ?? 0,
    //             'status' => $validated['status'] ?? 'pending',
    //             'user_id' => $validated['user_id'] ?? null,
    //             'notes' => $validated['notes'] ?? null,
    //         ]);

    //         foreach ($validated['items'] as $item) {
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'name' => $item['name'],
    //                 'size' => $item['size'] ?? null,
    //                 'toppings' => isset($item['toppings']) ? implode(',', $item['toppings']) : null,
    //                 'sugar' => $item['sugar'] ?? null,
    //                 'ice' => $item['ice'] ?? null,
    //                 'milk' => $item['milk'] ?? null,
    //                 'instructions' => $item['instructions'] ?? null,
    //                 'price' => $item['price'],
    //                 'qty' => $item['qty'],
    //             ]);
    //         }

    //         return response()->json(['success' => true, 'message' => 'Order created successfully', 'order_id' => $order->id]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ], 500);
    //     }
    // }

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