<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockMovement;
use App\Models\StockAlert;
use App\Mail\StockAlertMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

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
       $lowStockCount = $allProducts->filter(function($product) {
    return $product->current_stock <= $product->low_stock_alert && $product->current_stock > 0;
})->count();
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

        // Check stocks if low
        try {
            $this->checkAndNotifyProductStock($product);
        } catch (\Throwable $e) {
            Log::error('notify stock after manual update: '.$e->getMessage());
        }

        // If the product was increased above the low threshold, mark existing low/out alerts read
        if (!is_null($product->low_stock_alert) && $finalQuantity > $product->low_stock_alert) {
            StockAlert::where('product_id', $product->id)
                ->whereIn('type', ['low_stock', 'out_of_stock'])
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Now check/create alerts for the new quantity
        $this->checkAndNotifyProductStock($product);

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

    /**
     * Check product stock and create/send a StockAlert if needed.
     * Public so it can be called from other controllers/commands.
     *
     * Rate-limits via INVENTORY_ALERT_COOLDOWN_HOURS (default 24h).
     */
    public function checkAndNotifyProductStock(Product $product)
    {
        try {
            // config (fallbacks)
            $cooldownHours = (int) (config('inventory.alert_cooldown_hours') ?? env('INVENTORY_ALERT_COOLDOWN_HOURS', 24));
            $recipients = config(key: 'inventory.alert_recipients') ?? env(key: 'INVENTORY_ALERT_RECIPIENTS', default: '');

            // normalize recipients: allow comma-separated string or array in config
            if (is_string($recipients)) {
                $recipients = array_filter(array_map('trim', explode(',', $recipients)));
            } elseif (!is_array($recipients)) {
                $recipients = [];
            }

            // determine state
            $threshold = (int) ($product->low_stock_alert ?? 0);
            $current = (int) $product->current_stock;

            $type = null;
            $priority = 'info';
            $message = null;

            if ($current === 0) {
                $type = 'out_of_stock';
                $priority = 'critical';
                $message = "Product '{$product->name}' is OUT OF STOCK.";
            } elseif ($threshold > 0 && $current <= $threshold) {
                $type = 'low_stock';
                $priority = 'high';
                $message = "Product '{$product->name}' is low (remaining: {$current}). Threshold: {$threshold}.";
            } else {
                // stock above threshold: auto-clear any unread alerts for this product
                StockAlert::where('product_id', $product->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true, 'read_at' => now()]);
                return;
            }

            // rate-limit: if there's an existing unread alert for this product created within cooldown, skip
            $cutoff = now()->subHours($cooldownHours);
            $recent = StockAlert::where('product_id', $product->id)
                ->where('is_read', false)
                ->where('created_at', '>=', $cutoff)
                ->first();
            if ($recent) {
                // already alerted recently
                return;
            }

            // create alert
            $alert = StockAlert::create([
                'product_id' => $product->id,
                'type' => $type,
                'priority' => $priority,
                'message' => $message,
                'is_read' => false,
            ]);

            // send email notification once (if recipients configured)
            if (!empty($recipients)) {
                try {
                    Mail::to($recipients)->send(new StockAlertMail($alert));
                } catch (\Throwable $mailEx) {
                    Log::warning('Stock alert email failed: '.$mailEx->getMessage(), ['product'=>$product->id]);
                }
            }

        } catch (\Throwable $e) {
            Log::error('checkAndNotifyProductStock error: '.$e->getMessage(), ['product'=> $product->id ?? null]);
        }
    }


    public function apiAlerts()
    {
        $alerts = StockAlert::with('product')
            ->where('is_read', false)
            ->orderByRaw("FIELD(type, 'out_of_stock','low_stock') DESC, created_at DESC")
            ->get();

        $count = $alerts->count();
        $items = $alerts->map(function($a){
            return [
                'id' => $a->id,
                'product_id' => $a->product_id,
                'product_name' => $a->product->name ?? 'Unknown',
                'type' => $a->type,
                'priority' => $a->priority,
                'message' => $a->message,
                'created_at' => $a->created_at->toDateTimeString(),
            ];
        });

        return response()->json(['success' => true, 'count' => $count, 'alerts' => $items]);
    }

    // logStockMovement method removed (now handled inline)


    public function getAlerts(Request $request)
    {
        try {
            // unread alerts (limit optional)
            $limit = (int) $request->query('limit', 10);

            // Eager load product relationship if available, fallback to manual attach
            $alerts = StockAlert::where('is_read', false)
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            // Map to simple payload (avoid returning full model internals)
            $payload = $alerts->map(function ($a) {
                $product = $a->product ?? Product::find($a->product_id);
                return [
                    'id' => $a->id,
                    'product_id' => $a->product_id,
                    'product_name' => $product ? $product->name : null,
                    'type' => $a->type,
                    'priority' => $a->priority,
                    'message' => $a->message,
                    'is_read' => (bool) $a->is_read,
                    'created_at' => $a->created_at ? $a->created_at->toDateTimeString() : null,
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $alerts->count(),
                'alerts' => $payload->all()
            ]);
        } catch (\Throwable $e) {
            Log::error('getAlerts error: '.$e->getMessage(), ['ex' => $e]);
            return response()->json(['success'=>false, 'message'=>'Failed to load alerts'], 500);
        }
    }

    /**
     * Dismiss / mark a single alert as read via API
     */
public function dismissAlert($id)
    {
        try {
            $alert = StockAlert::find($id);
            if (! $alert) {
                return response()->json(['success'=>false, 'message'=>'Alert not found'], 404);
            }
            $alert->is_read = true;
            $alert->save();

            return response()->json(['success'=>true, 'message'=>'Alert dismissed']);
        } catch (\Throwable $e) {
            Log::error('dismissAlert error: '.$e->getMessage(), ['id'=>$id, 'ex'=>$e]);
            return response()->json(['success'=>false, 'message'=>'Failed to dismiss alert'], 500);
        }
    }

    /**
     * Mark all unread alerts as read (useful for "mark all" button)
     */
public function markAllAlertsRead(Request $request)
    {
        try {
            $updated = StockAlert::where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
            return response()->json(['success'=>true, 'updated' => $updated]);
        } catch (\Throwable $e) {
            Log::error('markAllAlertsRead error: '.$e->getMessage(), ['ex'=>$e]);
            return response()->json(['success'=>false, 'message'=>'Failed to mark alerts read'], 500);
        }
    }
}
