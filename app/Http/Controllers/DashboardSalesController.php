<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardSalesController extends Controller
{
    /**
     * Return top and bottom sellers for dashboard.
     */
    
    public function index(Request $request)
    {
        $days = (int) $request->input('days', 30);
        $limit = (int) $request->input('limit', 5);
        $status = $request->input('status', 'completed');
        $metric = $request->input('metric', 'qty');

        Log::debug('DashboardSalesController@index called', compact('days','limit','status','metric'));

        $from = Carbon::now()->subDays($days)->startOfDay();

        // quick check: what statuses exist in orders table (log for debugging)
        try {
            $statuses = DB::table('orders')
                ->select('status', DB::raw('COUNT(*) as cnt'))
                ->groupBy('status')
                ->orderByDesc('cnt')
                ->get();
            Log::debug('Orders status counts', ['statuses' => $statuses]);
        } catch (\Exception $e) {
            Log::warning('Failed to query orders statuses: ' . $e->getMessage());
        }

        // Build aggregation query (GROUP BY p.id,p.name is REQUIRED)
        $agg = DB::table('products as p')
        ->leftJoin('order_items as oi', 'oi.product_id', '=', 'p.id')
        ->leftJoin('orders as o', function($join) use ($from) {
            $join->on('o.id', '=', 'oi.order_id')
                ->where('o.status', '=', 'completed')
                ->where('o.created_at', '>=', $from);
        })
        ->select(
            'p.id as product_id',
            'p.name',
            DB::raw('COALESCE(SUM(oi.qty),0) as total_qty'),
            DB::raw('COALESCE(SUM(oi.qty * oi.price),0) as total_revenue')
        )
        ->groupBy('p.id', 'p.name');

        // run and log results
        $top = (clone $agg)
            ->orderByDesc($metric === 'revenue' ? 'total_revenue' : 'total_qty')
            ->limit($limit)
            ->get();

        $bottom = (clone $agg)
            ->orderBy($metric === 'revenue' ? 'total_revenue' : 'total_qty', 'asc')
            ->limit($limit)
            ->get();

        Log::debug('DashboardSalesController results', [
            'top_count' => $top->count(),
            'bottom_count' => $bottom->count(),
            'top' => $top->toArray(),
            'bottom' => $bottom->toArray()
        ]);

        $data = [
            'top' => $top,
            'bottom' => $bottom,
            'meta' => ['days' => $days, 'limit' => $limit, 'metric' => $metric]
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        // if non-json, render view (keeps previous behavior)
        return view('dashboard.sales', $data);
    }

    public function stats(Request $request)
    {
        // timeframe parameters (optional)
        $days = (int) $request->input('days', 30);
        $limit = (int) $request->input('limit', 5);

        // compute some basic stats
        $today = Carbon::today();
        $weekFrom = Carbon::now()->subDays(7);
        $from = Carbon::now()->subDays($days);

        // pending orders count
        $pendingOrders = (int) DB::table('orders')->where('status', 'pending')->count();

        // today sales (sum total for completed orders today)
        $todaySales = (float) DB::table('orders')
            ->where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total');

        // weekly sales (last 7 days, completed)
        $weeklySales = (float) DB::table('orders')
            ->where('status', 'completed')
            ->where('created_at', '>=', $weekFrom)
            ->sum('total');

        // low stock count
        $lowStockCount = (int) DB::table('products')
            ->whereColumn('current_stock', '<=', 'low_stock_alert')
            ->count();

        // monthly series (12 months for current year)
        $yearStart = Carbon::now()->startOfYear()->toDateString();
        // Prefer aggregates table if present, otherwise fallback to orders-based grouping
        if (Schema::hasTable('sales_aggregates')) {
            $monthlyRows = DB::table('sales_aggregates')
                ->select(DB::raw('MONTH(date) as m'), DB::raw('SUM(revenue) as revenue'))
                ->where('date', '>=', $yearStart)
                ->groupBy('m')
                ->orderBy('m')
                ->get();
        } else {
            $monthlyRows = DB::table('orders')
                ->select(DB::raw('MONTH(created_at) as m'), DB::raw('SUM(total) as revenue'))
                ->where('status', 'completed')
                ->where('created_at', '>=', $yearStart)
                ->groupBy('m')
                ->orderBy('m')
                ->get();
        }

        $monthly_series = array_fill(0, 12, 0.0);
        foreach ($monthlyRows as $r) {
            $idx = max(0, (int)$r->m - 1);
            $monthly_series[$idx] = (float)$r->revenue;
        }

        // recent orders (latest 5 completed) with items
        $recentOrders = DB::table('orders')
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($o) {
                $o->items = DB::table('order_items')->where('order_id', $o->id)->get();
                return $o;
            });

        $payload = [
            'stats' => [
                'pending_orders' => $pendingOrders,
                'today_sales' => $todaySales,
                'weekly_sales' => $weeklySales,
                'low_stock_count' => $lowStockCount,
                'monthly_sales' => array_sum($monthly_series),
            ],
            'monthly_series' => $monthly_series,
            'recent_orders' => $recentOrders,
        ];

        return response()->json($payload);
    }

}
