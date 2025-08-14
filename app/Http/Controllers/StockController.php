<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        // In a real application, you would fetch stock data from database
        // $stockItems = Product::with('stockMovements')->get();
        return view('inventory.stock');
    }

    public function getLevels()
    {
        // In a real application, calculate stock levels
        // $stockLevels = Product::selectRaw('
        //     COUNT(*) as total,
        //     SUM(CASE WHEN current_stock = 0 THEN 1 ELSE 0 END) as out_of_stock,
        //     SUM(CASE WHEN current_stock <= min_level AND current_stock > 0 THEN 1 ELSE 0 END) as low_stock,
        //     SUM(CASE WHEN current_stock > min_level AND current_stock <= max_level THEN 1 ELSE 0 END) as normal,
        //     SUM(CASE WHEN current_stock > max_level * 1.2 THEN 1 ELSE 0 END) as overstocked
        // ')->first();

        return response()->json([
            'success' => true,
            'levels' => [
                'out_of_stock' => 3,
                'low_stock' => 7,
                'normal' => 18,
                'overstocked' => 2,
                'total' => 30
            ]
        ]);
    }

    public function getAlerts()
    {
        // In a real application, fetch alerts from database
        // $alerts = Product::where('current_stock', '<=', DB::raw('min_level'))
        //     ->orWhere('current_stock', '>', DB::raw('max_level * 1.2'))
        //     ->get();

        return response()->json([
            'success' => true,
            'alerts' => [
                [
                    'id' => 1,
                    'type' => 'critical',
                    'title' => 'Out of Stock',
                    'message' => '3 items are completely out of stock',
                    'count' => 3,
                    'items' => [
                        ['name' => 'Club Sandwich', 'sku' => 'CLUB-001', 'stock' => 0, 'min_level' => 5],
                        ['name' => 'Caesar Wrap', 'sku' => 'WRAP-001', 'stock' => 0, 'min_level' => 5],
                        ['name' => 'Iced Tea', 'sku' => 'TEA-001', 'stock' => 0, 'min_level' => 10]
                    ],
                    'created_at' => now()->subHours(2)->toISOString()
                ],
                [
                    'id' => 2,
                    'type' => 'warning',
                    'title' => 'Low Stock',
                    'message' => '7 items need restocking soon',
                    'count' => 7,
                    'items' => [
                        ['name' => 'Americano', 'sku' => 'AME-001', 'stock' => 8, 'min_level' => 10],
                        ['name' => 'Croissant', 'sku' => 'CRO-001', 'stock' => 4, 'min_level' => 5],
                        ['name' => 'Green Tea', 'sku' => 'GT-001', 'stock' => 9, 'min_level' => 15],
                        ['name' => 'Blueberry Muffin', 'sku' => 'MUF-001', 'stock' => 3, 'min_level' => 5]
                    ],
                    'created_at' => now()->subHours(1)->toISOString()
                ],
                [
                    'id' => 3,
                    'type' => 'info',
                    'title' => 'Overstocked',
                    'message' => '2 items may be overstocked',
                    'count' => 2,
                    'items' => [
                        ['name' => 'Paper Cups', 'sku' => 'CUP-001', 'stock' => 500, 'max_level' => 200],
                        ['name' => 'Sugar Packets', 'sku' => 'SUG-001', 'stock' => 800, 'max_level' => 300]
                    ],
                    'created_at' => now()->subMinutes(30)->toISOString()
                ]
            ]
        ]);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // In a real application:
        // 1. Find the product
        // 2. Update stock level
        // 3. Log the stock movement
        // 4. Check for alerts
        // 5. Send notifications if needed

        // $product = Product::findOrFail($id);
        // $oldStock = $product->current_stock;
        // $product->current_stock = $request->new_stock;
        // $product->last_restocked = now();
        // $product->save();

        // StockMovement::create([
        //     'product_id' => $id,
        //     'old_stock' => $oldStock,
        //     'new_stock' => $request->new_stock,
        //     'difference' => $request->new_stock - $oldStock,
        //     'reason' => $request->reason,
        //     'notes' => $request->notes,
        //     'user_id' => auth()->id(),
        //     'created_at' => now()
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'product_id' => $id,
            'new_stock' => $request->new_stock
        ]);
    }

    public function getStockData()
    {
        // In a real application, fetch from database with filters
        // $products = Product::select('id', 'name', 'sku', 'current_stock', 'min_level', 'max_level', 'category')
        //     ->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'products' => [
                [
                    'id' => 1,
                    'name' => 'Espresso',
                    'sku' => 'ESP-001',
                    'category' => 'Coffee',
                    'current_stock' => 45,
                    'min_level' => 10,
                    'max_level' => 100,
                    'status' => 'normal',
                    'last_restocked' => '2024-01-15 08:00:00'
                ],
                [
                    'id' => 4,
                    'name' => 'Americano',
                    'sku' => 'AME-001',
                    'category' => 'Coffee',
                    'current_stock' => 8,
                    'min_level' => 10,
                    'max_level' => 80,
                    'status' => 'low',
                    'last_restocked' => '2024-01-14 16:30:00'
                ],
                [
                    'id' => 8,
                    'name' => 'Club Sandwich',
                    'sku' => 'CLUB-001',
                    'category' => 'Food',
                    'current_stock' => 0,
                    'min_level' => 5,
                    'max_level' => 20,
                    'status' => 'out',
                    'last_restocked' => '2024-01-13 12:00:00'
                ],
                [
                    'id' => 15,
                    'name' => 'Paper Cups',
                    'sku' => 'CUP-001',
                    'category' => 'Supplies',
                    'current_stock' => 500,
                    'min_level' => 50,
                    'max_level' => 200,
                    'status' => 'overstocked',
                    'last_restocked' => '2024-01-10 09:00:00'
                ]
            ]
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.product_id' => 'required|integer',
            'updates.*.new_stock' => 'required|integer|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        // In a real application:
        // foreach ($request->updates as $update) {
        //     $product = Product::findOrFail($update['product_id']);
        //     $oldStock = $product->current_stock;
        //     $product->current_stock = $update['new_stock'];
        //     $product->save();
        //     
        //     StockMovement::create([...]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Bulk stock update completed',
            'updated_count' => count($request->updates)
        ]);
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:low_stock,out_of_stock,overstocked,movement_history',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'category' => 'nullable|string'
        ]);

        // In a real application, generate reports based on type
        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'report_type' => $request->type,
            'download_url' => '/reports/stock-' . $request->type . '-' . now()->format('Y-m-d') . '.pdf'
        ]);
    }

    public function setThresholds(Request $request, $id)
    {
        $request->validate([
            'min_level' => 'required|integer|min:0',
            'max_level' => 'required|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'auto_reorder' => 'boolean'
        ]);

        // In a real application:
        // $product = Product::findOrFail($id);
        // $product->update([
        //     'min_level' => $request->min_level,
        //     'max_level' => $request->max_level,
        //     'reorder_point' => $request->reorder_point,
        //     'auto_reorder' => $request->auto_reorder
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock thresholds updated successfully'
        ]);
    }

    public function getMovementHistory($id)
    {
        // In a real application:
        // $movements = StockMovement::where('product_id', $id)
        //     ->with('user')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(20);

        return response()->json([
            'success' => true,
            'movements' => [
                [
                    'id' => 1,
                    'old_stock' => 15,
                    'new_stock' => 45,
                    'difference' => 30,
                    'reason' => 'restock',
                    'notes' => 'Weekly delivery received',
                    'user' => 'Admin User',
                    'created_at' => '2024-01-15 08:00:00'
                ],
                [
                    'id' => 2,
                    'old_stock' => 17,
                    'new_stock' => 15,
                    'difference' => -2,
                    'reason' => 'sale',
                    'notes' => 'Sold to customer',
                    'user' => 'Cashier',
                    'created_at' => '2024-01-14 14:30:00'
                ]
            ]
        ]);
    }

    public function dismissAlert(Request $request, $alertId)
    {
        // In a real application:
        // StockAlert::findOrFail($alertId)->update(['dismissed' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Alert dismissed successfully'
        ]);
    }

    public function getNotifications()
    {
        // In a real application, fetch recent stock notifications
        return response()->json([
            'success' => true,
            'notifications' => [
                [
                    'id' => 1,
                    'type' => 'stock_alert',
                    'title' => 'Low Stock Alert',
                    'message' => 'Americano is running low (8 remaining)',
                    'priority' => 'high',
                    'read' => false,
                    'created_at' => now()->subMinutes(15)->toISOString()
                ],
                [
                    'id' => 2,
                    'type' => 'out_of_stock',
                    'title' => 'Out of Stock',
                    'message' => 'Club Sandwich is out of stock',
                    'priority' => 'critical',
                    'read' => false,
                    'created_at' => now()->subHours(2)->toISOString()
                ]
            ]
        ]);
    }
}