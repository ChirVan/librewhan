<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function page(){ return view('inventory.products'); }

    public function create(){ 
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('inventory.products-create', compact('categories')); 
    }

    public function list(Request $request){
        $q = Product::with('category');
        if ($s = $request->input('search')) {
            $q->where(function(Builder $qq) use ($s){
                $qq->where('name','like',"%$s%")
                   ->orWhere('sku','like',"%$s%");
            });
        }
        if ($cid = $request->input('category_id')) $q->where('category_id',$cid);
        if (($status = $request->input('status')) && $status !== 'all') $q->where('status',$status);
        $q->orderBy('display_order')->orderBy('name');
        return response()->json(['data'=>$q->get()]);
    }

    public function store(Request $request){
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'sku' => 'required|string|unique:products,sku',
            'base_price' => 'required|numeric|min:0',
            'customizable' => 'boolean',
            'status' => 'required|in:active,inactive',
            'current_stock' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'display_order' => 'integer|min:0',
            'description' => 'nullable|string'
        ]);
        $data['customizable'] = $request->boolean('customizable');
        if (empty($data['slug'])) $data['slug'] = str($data['name'])->slug();
        $product = Product::create($data);
        return response()->json(['success'=>true,'product'=>$product->load('category')],201);
    }

    public function show(Product $product){ return response()->json(['product'=>$product->load('category')]); }

    public function update(Request $request, Product $product){
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'base_price' => 'required|numeric|min:0',
            'customizable' => 'boolean',
            'status' => 'required|in:active,inactive',
            'current_stock' => 'required|integer|min:0',
            'low_stock_alert' => 'required|integer|min:0',
            'display_order' => 'integer|min:0',
            'description' => 'nullable|string'
        ]);
        $data['customizable'] = $request->boolean('customizable');
        if (empty($data['slug'])) $data['slug'] = str($data['name'])->slug();
        $product->update($data);
        return response()->json(['success'=>true,'product'=>$product->load('category')]);
    }

    public function destroy(Product $product){ $product->delete(); return response()->json(['success'=>true]); }

    public function toggleStatus(Product $product){
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();
        return response()->json(['success'=>true,'status'=>$product->status]);
    }

    public function updateStock(Request $request, Product $product){
        $data = $request->validate([
            'action' => 'required|in:add,remove,set',
            'quantity' => 'required|integer|min:0'
        ]);
        $qty = (int)$data['quantity'];
        $original = $product->current_stock;
        match($data['action']) {
            'add' => $product->current_stock += $qty,
            'remove' => $product->current_stock = max(0,$product->current_stock - $qty),
            'set' => $product->current_stock = $qty,
        };
        $product->save();
        return response()->json(['success'=>true,'stock'=>$product->current_stock,'changed'=>$product->current_stock - $original]);
    }

    public function metaCategories(){
        return response()->json(['categories'=>Category::active()->orderBy('name')->get(['id','name'])]);
    }
}
