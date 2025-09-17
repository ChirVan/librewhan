<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;

class StockController extends Controller
{
    /**
     * Display stock levels page
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->select('id', 'name', 'category_id', 'current_stock', 'low_stock_alert', 'base_price', 'status');

        // Filtering
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%");
            });
        }
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sort = $request->input('sort', 'name');
        $direction = in_array($sort, ['name-desc','stock-desc']) ? 'desc' : 'asc';
        if ($sort === 'name' || $sort === 'name-desc') {
            $query->orderBy('name', $direction);
        } elseif ($sort === 'stock' || $sort === 'stock-desc') {
            $query->orderBy('current_stock', $direction);
        }

        // Pagination
        $products = $query->paginate(10)->appends($request->except('page'));

        // For stats, get all products (not paginated)
        $allProducts = Product::select('current_stock', 'low_stock_alert', 'base_price')->get();
        $totalProducts = Product::count();
        $lowStockCount = $allProducts->where('current_stock', '<=', 'low_stock_alert')->where('current_stock', '>', 0)->count();
        $outOfStockCount = $allProducts->where('current_stock', 0)->count();
        $totalStockValue = $allProducts->sum(function($product) {
            return $product->current_stock * $product->base_price;
        });

        // For filter dropdowns
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('inventory.stock.index', compact(
            'products',
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'totalStockValue',
            'categories'
        ));
    }

    /**
     * Update stock quantity for a product
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'adjustment_type' => 'required|in:add,subtract,set'
        ]);

        $product = Product::findOrFail($id);
        $oldQuantity = $product->current_stock;
        $newQuantity = $request->stock_quantity;

        // Calculate the actual new quantity based on adjustment type
        switch ($request->adjustment_type) {
            case 'add':
                $finalQuantity = $oldQuantity + $newQuantity;
                break;
            case 'subtract':
                $finalQuantity = max(0, $oldQuantity - $newQuantity);
                break;
            case 'set':
                $finalQuantity = $newQuantity;
                break;
        }

        // Update the product stock
        $product->update(['current_stock' => $finalQuantity]);

        // Log the stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $finalQuantity,
            'difference' => $finalQuantity - $oldQuantity,
            'reason' => $request->reason,
            'adjustment_type' => $request->adjustment_type,
            'user_id' => null, // No user tracking for now
        ]);

        return redirect()->route('inventory.stock')
            ->with('success', "Stock updated for {$product->name}. New quantity: {$finalQuantity}");
    }

    /**
     * Show products with low stock alerts
     */
    public function alerts()
    {
        $lowStockProducts = Product::with('category')
            ->whereRaw('current_stock <= low_stock_alert')
            ->where('current_stock', '>', 0)
            ->orderBy('current_stock')
            ->get();

        $outOfStockProducts = Product::with('category')
            ->where('current_stock', 0)
            ->get();

        return view('inventory.stock.alerts', compact('lowStockProducts', 'outOfStockProducts'));
    }

    /**
     * Show stock movement history
     */
    public function history()
    {
        // For now, return empty history - we'll implement this after creating the stock_movements table
        $movements = collect();
        
        return view('inventory.stock.history', compact('movements'));
    }

    // logStockMovement method removed (now handled inline)
}
