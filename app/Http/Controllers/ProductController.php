<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // In a real application, you would fetch products from database
        // For now, we'll use sample data in the view
        return view('inventory.products');
    }

    public function create()
    {
        return view('inventory.products-create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'customizable' => 'required|boolean'
        ]);

        // In a real application, you would save to database
        // Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => $request->all()
        ]);
    }

    public function show($id)
    {
        // In a real application, you would fetch from database
        // $product = Product::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $id,
                'name' => 'Sample Product',
                'sku' => 'SAMPLE-001',
                'category' => 'Coffee',
                'price' => 4.25,
                'stock' => 25,
                'status' => 'active'
            ]
        ]);
    }

    public function edit($id)
    {
        // In a real application, you would fetch from database
        // $product = Product::findOrFail($id);
        
        return view('inventory.products-edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'customizable' => 'required|boolean'
        ]);

        // In a real application, you would update in database
        // $product = Product::findOrFail($id);
        // $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'product' => array_merge($request->all(), ['id' => $id])
        ]);
    }

    public function destroy($id)
    {
        // In a real application, you would delete from database
        // Product::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    // Additional methods for product management
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string'
        ]);

        // In a real application, you would:
        // 1. Find the product
        // 2. Update stock based on action
        // 3. Log the stock change
        // 4. Check for low stock alerts

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'product_id' => $id,
            'action' => $request->action,
            'quantity' => $request->quantity
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        // In a real application, you would:
        // $product = Product::findOrFail($id);
        // $product->status = $product->status === 'active' ? 'inactive' : 'active';
        // $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully',
            'product_id' => $id
        ]);
    }

    public function getLowStock()
    {
        // In a real application, you would:
        // $lowStockProducts = Product::whereRaw('stock <= low_stock_alert')->get();

        return response()->json([
            'success' => true,
            'low_stock_products' => [
                // Sample low stock products
                ['id' => 4, 'name' => 'Americano', 'stock' => 8, 'low_stock_alert' => 10],
                ['id' => 8, 'name' => 'Club Sandwich', 'stock' => 0, 'low_stock_alert' => 5]
            ]
        ]);
    }

    public function getCategories()
    {
        // In a real application, you would:
        // $categories = Product::distinct('category')->pluck('category');

        return response()->json([
            'success' => true,
            'categories' => ['Coffee', 'Pastry', 'Food', 'Beverage']
        ]);
    }

    public function export(Request $request)
    {
        // In a real application, you would:
        // 1. Fetch products based on filters
        // 2. Generate CSV/Excel file
        // 3. Return download response

        $filters = $request->only(['category', 'status', 'search']);
        
        return response()->json([
            'success' => true,
            'message' => 'Export initiated',
            'filters' => $filters
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
            'action' => 'required|in:activate,deactivate,delete,update_category',
            'value' => 'nullable|string'
        ]);

        // In a real application, you would:
        // Product::whereIn('id', $request->product_ids)->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Bulk update completed successfully',
            'affected_products' => count($request->product_ids)
        ]);
    }
}