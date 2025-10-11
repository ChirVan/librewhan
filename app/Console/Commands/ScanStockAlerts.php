<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Http\Controllers\StockController;

class ScanStockAlerts extends Command
{
    protected $signature = 'stock:scan-alerts';
    protected $description = 'Scan product stocks and generate alerts for low/out-of-stock';

    public function handle()
    {
        $controller = app(StockController::class);
        Product::chunk(200, function($products) use ($controller) {
            foreach ($products as $product) {
                $controller->checkAndNotifyProductStock($product);
            }
        });

        $this->info('Stock scan completed.');
    }
}
