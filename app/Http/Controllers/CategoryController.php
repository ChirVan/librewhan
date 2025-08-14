<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // In a real application, you would fetch categories from database
        // $categories = Category::with('products')->get();
        return view('inventory.categories');
    }

    public function create()
    {
        return view('inventory.categories-create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'nullable|string',
            'icon' => 'required|string',
            'color' => 'required|string',
            'status' => 'required|in:active,inactive',
            'display_order' => 'required|integer|min:0',
            'featured' => 'boolean'
        ]);

        // In a real application, you would save to database
        // Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $request->all()
        ]);
    }

    public function show($id)
    {
        // In a real application, you would fetch from database
        // $category = Category::with('products')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'category' => [
                'id' => $id,
                'name' => 'Sample Category',
                'slug' => 'sample-category',
                'description' => 'Sample category description',
                'products_count' => 5
            ]
        ]);
    }

    public function edit($id)
    {
        // In a real application, you would fetch from database
        // $category = Category::findOrFail($id);
        
        return view('inventory.categories-edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'required|string',
            'color' => 'required|string',
            'status' => 'required|in:active,inactive',
            'display_order' => 'required|integer|min:0',
            'featured' => 'boolean'
        ]);

        // In a real application, you would update in database
        // $category = Category::findOrFail($id);
        // $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => array_merge($request->all(), ['id' => $id])
        ]);
    }

    public function destroy($id)
    {
        // In a real application, you would:
        // 1. Check if category has products
        // 2. Either reassign products or prevent deletion
        // 3. Delete the category
        // Category::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    // Additional methods for category management
    public function toggleStatus(Request $request, $id)
    {
        // In a real application, you would:
        // $category = Category::findOrFail($id);
        // $category->status = $category->status === 'active' ? 'inactive' : 'active';
        // $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category status updated successfully',
            'category_id' => $id
        ]);
    }

    public function getProducts($id)
    {
        // In a real application, you would:
        // $category = Category::with('products')->findOrFail($id);
        // return $category->products;

        return response()->json([
            'success' => true,
            'products' => [
                // Sample products for the category
                ['id' => 1, 'name' => 'Sample Product 1', 'price' => 4.25, 'stock' => 20],
                ['id' => 2, 'name' => 'Sample Product 2', 'price' => 3.50, 'stock' => 15]
            ]
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|integer',
            'categories.*.display_order' => 'required|integer'
        ]);

        // In a real application, you would:
        // foreach ($request->categories as $categoryData) {
        //     Category::where('id', $categoryData['id'])
        //         ->update(['display_order' => $categoryData['display_order']]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Category order updated successfully'
        ]);
    }

    public function export(Request $request)
    {
        // In a real application, you would:
        // 1. Fetch categories based on filters
        // 2. Generate CSV/Excel file
        // 3. Return download response

        $filters = $request->only(['status', 'search']);
        
        return response()->json([
            'success' => true,
            'message' => 'Export initiated',
            'filters' => $filters
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer',
            'action' => 'required|in:activate,deactivate,delete,set_featured,unset_featured',
        ]);

        // In a real application, you would:
        // Category::whereIn('id', $request->category_ids)->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Bulk update completed successfully',
            'affected_categories' => count($request->category_ids)
        ]);
    }

    public function getStats()
    {
        // In a real application, you would calculate real statistics
        return response()->json([
            'success' => true,
            'stats' => [
                'total_categories' => 4,
                'active_categories' => 4,
                'total_products' => 24,
                'popular_category' => 'Coffee'
            ]
        ]);
    }
}