
TODO

Change products into images


ISSUE CHECKLIST

Stock Update in Stocks doesn't work but works in Products




OTHER INFORMATION

Trigger low stocks notifications:
Run this in tinker

$controller = app(\App\Http\Controllers\StockController::class);
\App\Models\Product::chunk(200, function($products) use($controller) {
    foreach ($products as $p) {
        $controller->checkAndNotifyProductStock($p);
    }
});
