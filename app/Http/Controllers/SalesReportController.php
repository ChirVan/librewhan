<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;

class SalesReportController extends Controller
{
    /**
     * Blade pages
     */
    public function index()
    {
        // Admin dashboard (sales)
        $from = request('from_date') ? Carbon::parse(request('from_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $to = request('to_date') ? Carbon::parse(request('to_date'))->endOfDay() : Carbon::now()->endOfDay();

        // Orders in range
        $orders = Order::whereBetween('created_at', [$from, $to])->get();

        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $uniqueCustomers = $orders->whereNotNull('customer_name')->unique('customer_name')->count();

        // Top products by quantity and sales
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.id, products.name, COALESCE(SUM(order_items.qty * order_items.price),0) as total_sales, COALESCE(SUM(order_items.qty),0) as total_qty')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        // Payment method stats
        $paymentRows = $orders->groupBy('payment_mode');
        $paymentStats = [];
        $mostUsedPayment = null;
        $maxCount = 0;
        foreach ($paymentRows as $method => $group) {
            $count = $group->count();
            $amount = $group->sum('total');
            $percent = $totalOrders > 0 ? round(($count / $totalOrders) * 100, 1) : 0;
            $paymentStats[] = [
                'method' => $method ?? 'Unknown',
                'count' => $count,
                'amount' => $amount,
                'percent' => $percent,
            ];
            if ($count > $maxCount) {
                $maxCount = $count;
                $mostUsedPayment = $method ?? 'Unknown';
            }
        }

        return view('sales.report', compact(
            'totalRevenue', 'totalOrders', 'avgOrder', 'uniqueCustomers',
            'topProducts', 'paymentStats', 'mostUsedPayment'
        ));
    }

    public function smsIndex()
    {
        // Sales module main UI (sales SMS)
        return view('sales.sms.index');
    }

    /**
     * Helper: resolve date range (fallback last 30 days)
     */
    protected function resolveRange(Request $request)
    {
        $to = $request->input('to_date') ? Carbon::parse($request->input('to_date'))->endOfDay() : Carbon::now()->endOfDay();
        $from = $request->input('from_date') ? Carbon::parse($request->input('from_date'))->startOfDay() : $to->copy()->subDays(29)->startOfDay();
        return [$from, $to];
    }

    /**
     * Summary numbers: total revenue, orders, avg order, customers (guest name/email not modelled here)
     */
    public function getSummaryData(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $query = Order::whereBetween('created_at', [$from, $to]);

        $summary = $query->selectRaw('
                COALESCE(SUM(total),0) as total_revenue,
                COUNT(*) as total_orders,
                COALESCE(AVG(total),0) as average_order
            ')->first();

        // Growth vs previous period (same length)
        $periodDays = $from->diffInDays($to) + 1;
        $prevTo = $from->copy()->subDay();
        $prevFrom = $prevTo->copy()->subDays($periodDays - 1);

        $prev = Order::whereBetween('created_at', [$prevFrom->startOfDay(), $prevTo->endOfDay()])
            ->selectRaw('COALESCE(SUM(total),0) as total_revenue, COUNT(*) as total_orders, COALESCE(AVG(total),0) as average_order')
            ->first();

        $revenueGrowth = $prev->total_revenue > 0 ? (($summary->total_revenue - $prev->total_revenue) / $prev->total_revenue) * 100 : null;
        $ordersGrowth = $prev->total_orders > 0 ? (($summary->total_orders - $prev->total_orders) / $prev->total_orders) * 100 : null;

        return response()->json([
            'success' => true,
            'summary' => [
                'total_revenue' => (float) $summary->total_revenue,
                'total_orders' => (int) $summary->total_orders,
                'average_order' => (float) $summary->average_order,
                'growth' => [
                    'revenue' => $revenueGrowth === null ? null : round($revenueGrowth, 2),
                    'orders' => $ordersGrowth === null ? null : round($ordersGrowth, 2),
                ],
            ],
        ]);
    }

    /**
     * Trend: daily / weekly / monthly
     */
    public function getSalesTrend(Request $request)
    {
        $request->validate(['period' => 'required|in:daily,weekly,monthly']);
        $period = $request->period;
        [$from, $to] = $this->resolveRange($request);

        if ($period === 'daily') {
            $rows = Order::whereBetween('created_at', [$from, $to])
                ->selectRaw("DATE(created_at) as period, COALESCE(SUM(total),0) as total")
                ->groupBy('period')
                ->orderBy('period')
                ->get();
            $labels = $rows->pluck('period')->map(fn($d) => Carbon::parse($d)->format('M j'))->all();
            $data = $rows->pluck('total')->map(fn($v) => (float)$v)->all();
        } elseif ($period === 'weekly') {
            // use year-week
            $rows = Order::whereBetween('created_at', [$from, $to])
                ->selectRaw("YEAR(created_at) as yr, WEEK(created_at, 1) as wk, COALESCE(SUM(total),0) as total")
                ->groupBy('yr', 'wk')
                ->orderBy('yr')->orderBy('wk')
                ->get();
            $labels = $rows->map(fn($r) => "W{$r->wk}-{$r->yr}")->all();
            $data = $rows->pluck('total')->map(fn($v) => (float)$v)->all();
        } else { // monthly
            $rows = Order::whereBetween('created_at', [$from, $to])
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, COALESCE(SUM(total),0) as total")
                ->groupBy('period')
                ->orderBy('period')
                ->get();
            $labels = $rows->pluck('period')->map(fn($d) => Carbon::parse($d . '-01')->format('M Y'))->all();
            $data = $rows->pluck('total')->map(fn($v) => (float)$v)->all();
        }

        return response()->json(['success' => true, 'trend' => ['labels' => $labels, 'data' => $data]]);
    }

    /**
     * Category performance (sales sum per category)
     */
    public function getCategoryPerformance(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('categories.name as category, COALESCE(SUM(order_items.qty * order_items.price),0) as revenue, COUNT(DISTINCT orders.id) as orders_count')
            ->orderByDesc('revenue')
            ->get();

        return response()->json([
            'success' => true,
            'categories' => [
                'labels' => $rows->pluck('category')->all(),
                'data' => $rows->pluck('revenue')->map(fn($v) => (float)$v)->all(),
                'orders' => $rows->pluck('orders_count')->map(fn($v) => (int)$v)->all(),
            ]
        ]);
    }

    /**
     * Top products by revenue/quantity
     */
    public function getTopProducts(Request $request)
    {
        $limit = (int) ($request->limit ?? 10);
        [$from, $to] = $this->resolveRange($request);

        $rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.id, products.name as name, COALESCE(SUM(order_items.qty * order_items.price),0) as sales, COALESCE(SUM(order_items.qty),0) as quantity')
            ->orderByDesc('sales')
            ->limit($limit)
            ->get();

        return response()->json(['success' => true, 'products' => $rows->map(function($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'sales' => (float)$r->sales,
                'quantity' => (int)$r->quantity,
            ];
        })->all()]);
    }

    /**
     * Payments breakdown
     */
    public function getPaymentMethods(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $rows = Order::whereBetween('created_at', [$from, $to])
            ->groupBy('payment_mode')
            ->selectRaw('payment_mode, COUNT(*) as count, COALESCE(SUM(total),0) as amount')
            ->get();

        return response()->json([
            'success' => true,
            'payment_modes' => $rows->map(fn($r) => [
                'method' => $r->payment_mode,
                'count' => (int)$r->count,
                'amount' => (float)$r->amount,
            ])->values()->all()
        ]);
    }

    /**
     * Hourly pattern (by hour of day)
     */
    public function getHourlyPattern(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $rows = Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as orders, COALESCE(SUM(total),0) as revenue')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $labels = $rows->pluck('hour')->map(fn($h) => sprintf('%02d:00', $h))->all();
        $orders = $rows->pluck('orders')->map(fn($v) => (int)$v)->all();
        $revenue = $rows->pluck('revenue')->map(fn($v) => (float)$v)->all();

        return response()->json(['success' => true, 'hourly_pattern' => ['labels' => $labels, 'orders' => $orders, 'revenue' => $revenue]]);
    }

    /**
     * Simple customer insights (top customers, new customers, return rate).
     * Note: you don't have a customers table. We infer by customer_phone or name.
     */
    public function getCustomerInsights(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        // Top customers by number of orders (group by phone + name)
        $top = Order::whereBetween('created_at', [$from, $to])
            ->whereNotNull('customer_phone')
            ->groupBy('customer_phone')
            ->selectRaw('customer_phone, COALESCE(COUNT(*),0) as orders, COALESCE(SUM(total),0) as revenue')
            ->orderByDesc('orders')
            ->limit(10)
            ->get();

        // New customers: simple heuristic - first order in time window (requires more complex for real world)
        $newCustomers = Order::whereBetween('created_at', [$from, $to])
            ->whereNotNull('customer_phone')
            ->groupBy('customer_phone')
            ->havingRaw('MIN(created_at) between ? and ?', [$from, $to])
            ->get()
            ->count();

        return response()->json([
            'success' => true,
            'insights' => [
                'top_customers' => $top->map(fn($r) => ['phone' => $r->customer_phone, 'orders' => (int)$r->orders, 'revenue' => (float)$r->revenue])->all(),
                'new_customers_count' => $newCustomers,
                'return_rate_percent' => null // placeholder - compute with customers with multiple orders if desired
            ]
        ]);
    }

    /**
     * Paginated sales list (for tables)
     */
    public function getSalesData(Request $request)
    {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:200',
            'category' => 'nullable|string',
            'payment_mode' => 'nullable|string'
        ]);

        $perPage = (int) ($request->per_page ?? 10);
        $q = Order::with(['items', 'user'])->orderByDesc('created_at');

        if ($request->filled('payment_mode')) {
            $q->where('payment_mode', $request->payment_mode);
        }

        if ($request->filled('category')) {
            // join to limit by category
            $q->whereHas('items.product.category', function($qq) use ($request) {
                $qq->where('name', $request->category);
            });
        }

        $page = $request->page ?? 1;
        $orders = $q->paginate($perPage, ['*'], 'page', $page);

        // map result
        $data = $orders->map(fn($o) => [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'date' => $o->created_at->toDateTimeString(),
            'customer' => $o->customer_name,
            'payment_mode' => $o->payment_mode,
            'amount' => (float) $o->total,
            'status' => $o->status,
            'items' => $o->items->map(fn($it) => [
                'name' => $it->name,
                'qty' => $it->qty,
                'price' => (float) $it->price
            ])
        ]);

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage(),
            ]
        ]);
    }

    /**
     * Get order details (existing)
     */
    public function getOrderDetails($orderId)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'date' => $order->created_at->toDateTimeString(),
                'customer' => [
                    'name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                ],
                'items' => $order->items->map(fn($it) => [
                    'name' => $it->name,
                    'product_id' => $it->product_id,
                    'quantity' => (int)$it->qty,
                    'price' => (float)$it->price,
                    'total' => (float)($it->qty * $it->price),
                ]),
                'subtotal' => (float)$order->subtotal,
                'tax' => (float)$order->tax_amount,
                'discount' => (float)$order->discount_amount,
                'total' => (float)$order->total,
                'payment_mode' => $order->payment_mode,
                'status' => $order->status,
                'user' => $order->user ? $order->user->name : null,
            ]
        ]);
    }

    /**
     * Print receipt: returns a Blade view for printing in browser (or JSON if requested)
     * Existing route: POST /sales/orders/{id}/print - we accept both POST and GET for convenience.
     */
    public function printReceipt(Request $request, $orderId)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);

        // If the request expects JSON, return the order data
        if ($request->wantsJson() || $request->isJson()) {
            return $this->getOrderDetails($orderId);
        }

        // Otherwise return a Blade view printable receipt
        return view('sales.receipt', compact('order'));
    }

    /**
     * Dashboard stats quick endpoint
     */
    public function getDashboardStats()
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();

        $todaySales = Order::whereDate('created_at', $today)->sum('total');
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $yesterdaySales = Order::whereDate('created_at', $yesterday)->sum('total');
        $yesterdayOrders = Order::whereDate('created_at', $yesterday)->count();
        $monthlySales = Order::whereYear('created_at', $today->year)->whereMonth('created_at', $today->month)->sum('total');
        $monthlyOrders = Order::whereYear('created_at', $today->year)->whereMonth('created_at', $today->month)->count();

        $topProduct = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->whereBetween('orders.created_at', [$today->startOfDay(), $today->endOfDay()])
            ->selectRaw('products.name, SUM(order_items.qty) as qty')
            ->groupBy('products.name')
            ->orderByDesc('qty')
            ->limit(1)
            ->first();

        return response()->json([
            'success' => true,
            'stats' => [
                'today_sales' => (float)$todaySales,
                'today_orders' => (int)$todayOrders,
                'yesterday_sales' => (float)$yesterdaySales,
                'yesterday_orders' => (int)$yesterdayOrders,
                'monthly_sales' => (float)$monthlySales,
                'monthly_orders' => (int)$monthlyOrders,
                'top_product_today' => $topProduct ? $topProduct->name : null,
                'peak_hour_today' => null,
            ]
        ]);
    }

    /**
     * Realtime updates (simple)
     */
    public function getRealtimeUpdates()
    {
        $lastOrder = Order::latest()->first();
        $ordersLastHour = Order::where('created_at', '>=', now()->subHour())->count();
        $revenueLastHour = Order::where('created_at', '>=', now()->subHour())->sum('total');

        return response()->json([
            'success' => true,
            'updates' => [
                'last_order_time' => $lastOrder ? $lastOrder->created_at->toISOString() : null,
                'orders_last_hour' => (int)$ordersLastHour,
                'revenue_last_hour' => (float)$revenueLastHour,
            ]
        ]);
    }

    public function receiptView(Order $order)
    {
        $order->load('items'); // eager load items
        return view('sales.receipt', compact('order'));
    }

    public function summaryForRange($from = null, $to = null)
    {
        $from = $from ? Carbon::parse($from)->startOfDay() : Carbon::today()->startOfDay();
        $to = $to ? Carbon::parse($to)->endOfDay() : Carbon::today()->endOfDay();

        $totalRevenue = Order::whereBetween('created_at', [$from, $to])->sum('total');
        $totalOrders = Order::whereBetween('created_at', [$from, $to])->count();
        $avgOrder = $totalOrders ? $totalRevenue / $totalOrders : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order' => round($avgOrder, 2),
        ];
    }

    public function topProducts($from = null, $to = null, $limit = 10)
    {
        $from = $from ? Carbon::parse($from)->startOfDay() : Carbon::today()->startOfDay();
        $to = $to ? Carbon::parse($to)->endOfDay() : Carbon::today()->endOfDay();

        return OrderItem::select('name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(price * qty) as total_sales'))
            ->whereHas('order', function($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            })
            ->groupBy('name')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get();
    }

    

// returns simple summary for given date range (query params: from,to)
public function apiSummary(Request $request)
{
    $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::today()->startOfDay();
    $to = $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : Carbon::today()->endOfDay();

    $totalRevenue = \App\Models\Order::whereBetween('created_at', [$from, $to])->sum('total');
    $totalOrders = \App\Models\Order::whereBetween('created_at', [$from, $to])->count();
    $avgOrder = $totalOrders ? $totalRevenue / $totalOrders : 0;

    return response()->json([
        'success' => true,
        'summary' => [
            'total_revenue' => round($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'average_order' => round($avgOrder, 2),
        ]
    ]);
}

// returns top products for given date range (query params: from,to,limit)
public function apiTopProducts(Request $request)
{
    $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::today()->startOfDay();
    $to = $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : Carbon::today()->endOfDay();
    $limit = (int) $request->query('limit', 8);

    $products = OrderItem::select('name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(price * qty) as total_sales'))
        ->whereHas('order', function($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        })
        ->groupBy('name')
        ->orderByDesc('total_sales')
        ->limit($limit)
        ->get();

    return response()->json(['success' => true, 'products' => $products]);
}

}
