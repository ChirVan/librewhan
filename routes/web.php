<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\Admin\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Preserves original route names/URIs. Converted param {id} -> model-binding
| names like {order}, {product}, {category}, {user}, {stock} for cleaner
| controllers. Middleware format standardized to role:admin|barista.
|
*/

/*
|------------------------------
| Root & Utility (no closures)
|------------------------------
|
| Controller methods required in HomeController:
| - index() : redirects to dashboard or login (as original closure did)
| - clearSession() : flushes session and redirects to login
| - testLogin() : returns view('auth.login') for testing
*/
// Root - route to HomeController@index (avoids closure)
// Route::get('/', [HomeController::class, 'index'])->name('root');

// Utility / debugging routes - moved to HomeController methods to avoid closures
// Route::get('/clear-session', [HomeController::class, 'clearSession'])->name('util.clearSession');
// Route::get('/test-login', [HomeController::class, 'testLogin'])->name('util.testLogin');

/*
|------------------------------
| Jetstream Routes(automatically not here)
|------------------------------
| post /login // {{ route('login') }}
| user/profile // {{ route('profile.show') }}
| 
|------------------------------
*/
/* 
| Redirect to login when no route is specified
*/
Route::get('/', function () {
    return redirect()->route('login');
})->name('root');

// Receipt Page for SMS
Route::middleware(['auth','role:admin'])->get('/sales/receipt/{order}', [\App\Http\Controllers\SalesReportController::class, 'receiptView'])->name('sales.receipt');


/*
|------------------------------
| Protected routes (auth)
| All following routes require authentication
|------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/sms', [DashboardController::class, 'smsIndex'])->name('dashboard.sms');
    Route::get('/dashboard/inventory', [DashboardController::class, 'inventoryIndex'])->name('dashboard.inventory');

    // Order management routes (barista + admin)
    Route::middleware('role:admin|barista')->prefix('orders')->name('orders.')->group(function () {
        Route::post('/', [OrderController::class,'store'])->name('orders.store');
        Route::get('/pending', [OrderController::class, 'pending'])->name('pending'); // pending() does not exist yet
        Route::get('/take', [OrderController::class, 'take'])->name('take');
        Route::get('/manage', [OrderController::class, 'manage'])->name('manage'); // manage() does not exist yet
        Route::get('/history', [OrderController::class, 'history'])->name('history'); // history() does not exist yet
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');           // {order}
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');     // {order}
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus'); // {order}
        Route::post('/{id}/complete', [OrderController::class, 'complete'])->name('complete'); // {order}
    });


    // Inventory pages & admin management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // view pages for admin & barista
        Route::middleware('role:admin|barista')->group(function () {
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
            Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
            // ADD THIS LINE - Categories page route
            Route::get('/categories', [CategoryController::class, 'indexPage'])->name('categories.index');
            Route::get('/_meta/categories', [ProductController::class, 'metaCategories'])->name('metaCategories');

            Route::get('/stock', [StockController::class,'index'])->name('stock'); // RECOVERED FROM BACKUP
            Route::get('/stock/alerts', [StockController::class, 'alerts'])->name('stock.alerts'); // RECOVERED FROM BACKUP
            Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history'); // RECOVERED FROM BACKUP
            Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update'); // RECOVERED FROM BACKUP
        });
        
        Route::get('/categories/data', [CategoryController::class,'index'])->name('categories.data'); // RECOVERED FROM BACKUP, THIS IS CALLED BY JAVASCRIPT IN products.blade.php
        // Admin-only Products Management
        Route::middleware('role:admin')->group(function () {
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

            // REPLACE this line - Remove the resource route that was causing conflict
            // Route::resource('categories', CategoryController::class)->except(['show']);
            // WITH these specific routes for category management
            Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            Route::get('/categories/export', [CategoryController::class, 'export'])->name('categories.export');
            
            Route::post('/stocks/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
        });

        // Inventory JSON endpoints (AJAX)
        // TODO: This might cause problems by duplicating products
        Route::prefix('api/products')->name('api.products.')->group(function () {
            Route::get('/', [ProductController::class, 'list'])->name('list');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle');
            Route::patch('/{product}/stock', [ProductController::class, 'updateStock'])->name('stock');

            Route::get('/_meta/categories', [ProductController::class,'metaCategories'])->name('metaCategories'); // ANOTHER LOST ONE
        });
    });

    /*
    | Generic JSON API endpoints (kept in web.php for session-based auth)
    | Consider moving to api.php later for stateless APIs
    */
    Route::prefix('api')->name('api.')->group(function () {
        // categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show'); // {category}
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update'); // {category}
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // {category}
        Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus'); // {category}
        Route::get('/categories/{id}/products', [CategoryController::class, 'getProducts'])->name('categories.products'); // {category}

        // stock endpoints (use {stock} if Stock model exists)
        Route::get('/stock/data', [StockController::class, 'getStockData'])->name('stock.data');
        Route::get('/stock/levels', [StockController::class, 'getLevels'])->name('stock.levels');
        Route::get('/stock/alerts', [StockController::class, 'getAlerts'])->name('stock.alerts');
        Route::post('/stock/update/{id}', [StockController::class, 'updateStock'])->name('stock.update'); // model-bound recommended {stock}
        Route::post('/stock/thresholds/{id}', [StockController::class, 'setThresholds'])->name('stock.setThresholds'); // {stock}
        Route::get('/stock/history/{id}', [StockController::class, 'getMovementHistory'])->name('stock.getHistory'); // {stock}
        Route::post('/stock/alerts/{id}/dismiss', [StockController::class, 'dismissAlert'])->name('stock.dismissAlert'); // {stock}
        Route::post('/stock/report', [StockController::class, 'generateReport'])->name('stock.generateReport');
        Route::get('/stock/notifications', [StockController::class, 'getNotifications'])->name('stock.getNotifications');

        // order API
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show'); // {order} // show() does not exist yet

        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        // store() already exists above
        // update() already exists above
        // dsetroy() already exists above
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus'); // {order}
        Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete'); // {order}

        // Note: product API endpoints are under inventory/api/products above

        // Sales API endpoints
        Route::get('/sales/api/summary', [SalesReportController::class, 'apiSummary'])->name('sales.api.summary');
        Route::get('/sales/api/top-products', [SalesReportController::class, 'apiTopProducts'])->name('sales.api.topProducts');
    });


    // Sales & Reports routes (protected)
    // Route::prefix('sales')->name('sales.')->group(function () {
    Route::middleware(['auth','role:admin'])->prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('sms.index'); // when admin logged in, go to sales
        Route::get('/report', [SalesReportController::class, 'index'])->name(name: 'report');
        Route::get('/sms', [SalesReportController::class, 'smsIndex'])->name('sms.index');

        Route::get('/report/summary', [SalesReportController::class, 'getSummaryData'])->name('report.summary');
        Route::get('/report/trends', [SalesReportController::class, 'getSalesTrend'])->name('report.trends');
        Route::get('/report/categories', [SalesReportController::class, 'getCategoryPerformance'])->name('report.categories');
        Route::get('/report/top-products', [SalesReportController::class, 'getTopProducts'])->name('report.topProducts');
        Route::get('/report/payment-methods', [SalesReportController::class, 'getPaymentMethods'])->name('report.paymentMethods');
        Route::get('/report/hourly-pattern', [SalesReportController::class, 'getHourlyPattern'])->name('report.hourlyPattern');
        Route::get('/report/customer-insights', [SalesReportController::class, 'getCustomerInsights'])->name('report.customerInsights');
        Route::get('/report/sales-data', [SalesReportController::class, 'getSalesData'])->name('report.salesData');

        Route::post('/report/generate', [SalesReportController::class, 'generateReport'])->name('report.generate');
        Route::post('/report/export', [SalesReportController::class, 'exportData'])->name('report.export');

        Route::get('/orders/{id}/details', [SalesReportController::class, 'getOrderDetails'])->name('orders.details'); // {order}
        Route::post('/orders/{id}/print', [SalesReportController::class, 'printReceipt'])->name('orders.printReceipt'); // {order}

        Route::get('/dashboard-stats', [SalesReportController::class, 'getDashboardStats'])->name('dashboardStats');
        Route::get('/realtime-updates', [SalesReportController::class, 'getRealtimeUpdates'])->name('realtimeUpdates');
        Route::get('/realtime', [SalesReportController::class, 'getRealtimeUpdates'])->name('realtime');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        // User Management (admin-only)
        Route::resource('users', UserManagementController::class);
        // TODO: resource('users') already registers CRUD routes for users. The individual routes below are duplicates and can be removed later.
        // Admin User Management
        Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');

        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])->name('users.resetPassword');
        Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggleStatus');


    });


    // HomeController or other generic pages (still protected)
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
});