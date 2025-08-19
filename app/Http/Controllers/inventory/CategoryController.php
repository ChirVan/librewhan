<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CategoryController extends Controller
{
    // Blade page
    public function indexPage() {
        return view('inventory.categories');
    }

    // JSON list with filters
    public function index(Request $request) {
        $q = Category::withCount('products');

        if ($search = $request->input('search')) {
            $q->where(function($qq) use ($search){
                $qq->where('name','like',"%$search%")
                   ->orWhere('description','like',"%$search%");
            });
        }
        if ($status = $request->input('status')) {
            if ($status !== 'all') $q->where('status',$status);
        }
        if ($sort = $request->input('sort')) {
            match($sort) {
                'name-desc'      => $q->orderBy('name','desc'),
                'products'       => $q->orderBy('products_count','desc'),
                'created'        => $q->latest(),
                'created-desc'   => $q->oldest(),
                default          => $q->orderBy('name')
            };
        } else {
            $q->orderBy('display_order')->orderBy('name');
        }

        return response()->json([
            'data' => $q->get()
        ]);
    }

    public function store(StoreCategoryRequest $request) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = str($data['name'])->slug();
        $cat = Category::create($data);
        return response()->json(['success'=>true,'category'=>$cat->loadCount('products')]);
    }

    public function show(Category $category) {
        return response()->json(['category'=>$category->loadCount('products')]);
    }

    public function update(UpdateCategoryRequest $request, Category $category) {
        $data = $request->validated();
        if (empty($data['slug'])) $data['slug'] = str($data['name'])->slug();
        $category->update($data);
        return response()->json(['success'=>true,'category'=>$category->loadCount('products')]);
    }

    public function destroy(Category $category) {
        if ($category->products()->exists()) {
            return response()->json(['success'=>false,'message'=>'Category has products. Deactivate instead.'],422);
        }
        $category->delete();
        return response()->json(['success'=>true]);
    }

    public function toggle(Category $category) {
        $category->status = $category->status === 'active' ? 'inactive' : 'active';
        $category->save();
        return response()->json(['success'=>true,'status'=>$category->status]);
    }

    public function export(Request $request): StreamedResponse {
        $rows = Category::withCount('products')->orderBy('display_order')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories.csv"'
        ];
        return response()->stream(function() use ($rows) {
            $out = fopen('php://output','w');
            fputcsv($out,['ID','Name','Slug','Status','Featured','Products','Order','Created']);
            foreach ($rows as $r) {
                fputcsv($out,[
                    $r->id,$r->name,$r->slug,$r->status,
                    $r->featured ? 'Yes':'No',
                    $r->products_count,$r->display_order,
                    $r->created_at
                ]);
            }
            fclose($out);
        },200,$headers);
    }
}