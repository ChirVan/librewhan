<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AggregateDailySales extends Command
{
    protected $signature = 'sales:aggregate:daily {date? : The date to aggregate (YYYY-MM-DD). Defaults to yesterday}';
    protected $description = 'Aggregate daily product sales (qty, revenue) into sales_aggregates table';

    public function handle()
    {
        $dateArg = $this->argument('date');
        $date = $dateArg ? Carbon::parse($dateArg)->startOfDay() : Carbon::yesterday()->startOfDay();
        $start = $date->copy()->toDateTimeString();
        $end = $date->copy()->endOfDay()->toDateTimeString();

        $this->info("Aggregating sales for date: {$date->toDateString()} (range: {$start} - {$end})");

        // Aggregate from order_items joined to completed orders only
        $rows = DB::table('order_items as oi')
            ->select('oi.product_id', DB::raw('SUM(oi.qty) as total_qty'), DB::raw('SUM(oi.qty * oi.price) as total_revenue'))
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where('o.status', 'completed')
            ->whereBetween('o.created_at', [$start, $end])
            ->groupBy('oi.product_id')
            ->get();

        // prepare upsert payload
        $now = now();
        $upserts = [];
        foreach ($rows as $r) {
            $upserts[] = [
                'product_id' => $r->product_id,
                'date' => $date->toDateString(),
                'quantity' => (int)$r->total_qty,
                'revenue' => (float)$r->total_revenue,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // If no rows, still ensure a step showing zero results is OK
        if (!empty($upserts)) {
            // use insert on duplicate (Laravel upsert)
            DB::table('sales_aggregates')->upsert(
                $upserts,
                ['product_id', 'date'],
                ['quantity', 'revenue', 'updated_at']
            );
            $this->info('Upserted ' . count($upserts) . ' product aggregates.');
        } else {
            $this->info('No sales rows for that date.');
        }

        return 0;
    }
}
