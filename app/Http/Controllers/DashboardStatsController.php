<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardStatsController extends Controller
{
    /**
     * Return JSON used by dashboard (weekly & monthly top/bottom sellers, totals, series, etc.).
     */
    public function stats(Request $request)
    {
        // Config
        $limit = 5;
        $now = Carbon::now();

        // Periods (rolling)
        $weekTo = $now->endOfDay();
        $weekFrom = (clone $now)->subDays(6)->startOfDay(); // last 7 days including today

        $monthTo = $now->endOfDay();
        $monthFrom = (clone $now)->subDays(29)->startOfDay(); // last 30 days including today

        // Cache key
        $cacheKey = "dashboard:sales:stats:week{$weekFrom->format('Ymd')}:month{$monthFrom->format('Ymd')}";

        $payload = Cache::remember($cacheKey, now()->addMinutes(3), function () use ($weekFrom, $weekTo, $monthFrom, $monthTo, $limit, $now) {
            // helper: aggregate per product for a date range (LEFT JOIN to include zero-sales)
            $aggregatePerProduct = function (Carbon $from, Carbon $to) {
                // subquery aggregates from order_items joined to completed orders in range
                $sub = DB::table('order_items as oi')
                    ->select(
                        'oi.product_id',
                        DB::raw('SUM(oi.qty) as total_qty'),
                        DB::raw('SUM(oi.qty * oi.price) as total_revenue')
                    )
                    ->join('orders as o', 'o.id', '=', 'oi.order_id')
                    ->where('o.status', 'completed')
                    ->whereBetween('o.created_at', [$from->toDateTimeString(), $to->toDateTimeString()])
                    ->groupBy('oi.product_id');

                // left join products to include zero-sales
                return DB::table('products as p')
                    ->leftJoinSub($sub, 's', 'p.id', '=', 's.product_id')
                    ->select(
                        'p.id as product_id',
                        'p.name',
                        DB::raw('COALESCE(s.total_qty,0) as total_qty'),
                        DB::raw('COALESCE(s.total_revenue,0) as total_revenue')
                    );
            };

            // WEEK aggregates
            $weekAgg = $aggregatePerProduct($weekFrom, $weekTo);
            $weekTop = (clone $weekAgg)->orderByDesc('total_qty')->limit($limit)->get();
            $weekBottom = (clone $weekAgg)->orderBy('total_qty', 'asc')->limit($limit)->get();

            // MONTH aggregates
            $monthAgg = $aggregatePerProduct($monthFrom, $monthTo);
            $monthTop = (clone $monthAgg)->orderByDesc('total_qty')->limit($limit)->get();
            $monthBottom = (clone $monthAgg)->orderBy('total_qty', 'asc')->limit($limit)->get();

            // Totals for period and previous period (for percent change)
            $sumForRange = function (Carbon $from, Carbon $to) {
                $row = DB::table('order_items as oi')
                    ->join('orders as o', 'o.id', '=', 'oi.order_id')
                    ->where('o.status', 'completed')
                    ->whereBetween('o.created_at', [$from->toDateTimeString(), $to->toDateTimeString()])
                    ->select(
                        DB::raw('COALESCE(SUM(oi.qty * oi.price),0) as revenue'),
                        DB::raw('COALESCE(SUM(oi.qty),0) as qty')
                    )->first();
                return (array) $row;
            };

            // current totals
            $weekTotal = $sumForRange($weekFrom, $weekTo);
            $monthTotal = $sumForRange($monthFrom, $monthTo);

            // previous periods for percent change
            $prevWeekFrom = (clone $weekFrom)->subDays(7);
            $prevWeekTo = (clone $weekTo)->subDays(7);
            $prevMonthFrom = (clone $monthFrom)->subDays(30);
            $prevMonthTo = (clone $monthTo)->subDays(30);

            $prevWeekTotal = $sumForRange($prevWeekFrom, $prevWeekTo);
            $prevMonthTotal = $sumForRange($prevMonthFrom, $prevMonthTo);

            // percent change helper
            $pctChange = function ($current, $previous) {
                $cur = floatval($current);
                $prev = floatval($previous);
                if ($prev == 0) {
                    return $cur == 0 ? 0 : 100; // from 0 -> positive -> show 100% (you can tweak)
                }
                return round((($cur - $prev) / $prev) * 100, 1);
            };

            // pending orders and today sales summary
            $pendingOrders = DB::table('orders')->where('status', 'pending')->count();
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();
            $todaySalesRow = DB::table('order_items as oi')
                ->join('orders as o', 'o.id', '=', 'oi.order_id')
                ->where('o.status', 'completed')
                ->whereBetween('o.created_at', [$todayStart->toDateTimeString(), $todayEnd->toDateTimeString()])
                ->select(DB::raw('COALESCE(SUM(oi.qty * oi.price),0) as revenue'))
                ->first();
            $todaySales = $todaySalesRow->revenue ?? 0;

            // low stock products count (use your existing logic)
            $lowStockCount = DB::table('products')->whereColumn('current_stock', '<=', 'low_stock_alert')->count();

            // monthly revenue series for last 12 months (map month number => revenue)
            $start12 = (clone $now)->subMonths(11)->startOfMonth();
            $monthlyRows = DB::table('order_items as oi')
                ->join('orders as o', 'o.id', '=', 'oi.order_id')
                ->where('o.status', 'completed')
                ->whereBetween('o.created_at', [$start12->toDateTimeString(), $now->toDateTimeString()])
                ->select(
                    DB::raw('YEAR(o.created_at) as year'),
                    DB::raw('MONTH(o.created_at) as month'),
                    DB::raw('COALESCE(SUM(oi.qty * oi.price),0) as revenue')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')->orderBy('month')
                ->get();

            // build an array of 12 monthly values (oldest -> newest)
            $monthlySeries = [];
            $months = [];
            for ($i = 11; $i >= 0; $i--) {
                $m = (clone $now)->subMonths($i);
                $months[] = $m->format('M Y');
                $monthlySeries[] = 0;
            }
            // map results into monthlySeries
            foreach ($monthlyRows as $row) {
                $label = Carbon::createFromDate($row->year, $row->month, 1)->format('M Y');
                $idx = array_search($label, $months);
                if ($idx !== false) $monthlySeries[$idx] = floatval($row->revenue);
            }

            return [
                'meta' => [
                    'generated_at' => now()->toDateTimeString(),
                    'week_from' => $weekFrom->toDateString(),
                    'week_to' => $weekTo->toDateString(),
                    'month_from' => $monthFrom->toDateString(),
                    'month_to' => $monthTo->toDateString(),
                ],
                'summary' => [
                    'pending_orders' => $pendingOrders,
                    'today_sales' => floatval($todaySales),
                    'low_stock_count' => $lowStockCount,
                    'week_total' => $weekTotal,
                    'month_total' => $monthTotal,
                    'week_pct_change_revenue' => $this->safePct($weekTotal['revenue'], $prevWeekTotal['revenue']),
                    'month_pct_change_revenue' => $this->safePct($monthTotal['revenue'], $prevMonthTotal['revenue']),
                ],
                'weekly' => [
                    'top_by_qty' => $weekTop,
                    'bottom_by_qty' => $weekBottom
                ],
                'monthly' => [
                    'top_by_qty' => $monthTop,
                    'bottom_by_qty' => $monthBottom
                ],
                'monthly_series' => $monthlySeries,
                'monthly_labels' => $months
            ];
        });

        return response()->json($payload);
    }

    // helper used inside closure to compute safe percent change
    private function safePct($current, $previous)
    {
        $cur = floatval($current);
        $prev = floatval($previous);
        if ($prev == 0) {
            if ($cur == 0) return 0;
            return 100;
        }
        return round((($cur - $prev) / $prev) * 100, 1);
    }
}
