<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Merged and restored from backup. Protect routes with 'auth' middleware,
| and use 'role' middleware where necessary (admin/barista).
|
*/

/**
 * Root route:
 * If authenticated -> dashboard
 * If not -> redirect to login (Fortify/Jetstream handles login page)
 */
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

/**
 * Temporary / utility routes
 */
Route::get('/clear-session', function () {
    session()->flush();
    return redirect('/login')->with('message', 'Session cleared!');
});

Route::get('/test-login', function () {
    // useful for testing the legacy login view if necessary
    return view('auth.login');
});

/**
 * POS routes (barista + admin)
 */
Route::middleware(['auth', 'role:barista|admin'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [OrderController::class, 'take'])->name('take');
    Route::get('/pending', [OrderController::class, 'pending'])->name('pending');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

/**
 * All protected routes
 */
Route::middleware(['auth'])->group(function () {

    // Dashboard (both roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/sms', [DashboardController::class, 'smsIndex'])->name('dashboard.sms');
    Route::get('/dashboard/inventory', [DashboardController::class, 'inventoryIndex'])->name('dashboard.inventory');

    // Orders: available to both admin and barista (barista can create/process orders)
    Route::middleware('role:admin,barista')->prefix('orders')->name('orders.')->group(function () {
        Route::get('/pending', [OrderController::class, 'pending'])->name('pending');
        Route::get('/take', [OrderController::class, 'take'])->name('take');
        Route::get('/manage', [OrderController::class, 'manage'])->name('manage');
        Route::get('/history', [OrderController::class, 'history'])->name('history');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/complete', [OrderController::class, 'complete'])->name('complete');
    });

    // Inventory pages & admin management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Viewing allowed for admin + barista (pages)
        Route::middleware('role:admin,barista')->group(function () {
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
            Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        });

        // Admin-only product/category/stock management (pages + actions)
        Route::middleware('role:admin')->group(function () {
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

            // category resource (pages / admin actions)
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::post('/stocks/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
        });

        // Inventory JSON endpoints for products (AJAX)
        Route::prefix('api/products')->name('api.products.')->group(function () {
            Route::get('/', [ProductController::class, 'list'])->name('list');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle');
            Route::patch('/{product}/stock', [ProductController::class, 'updateStock'])->name('stock');
            Route::get('/_meta/categories', [ProductController::class, 'metaCategories'])->name('metaCategories');
        });
    });

    // JSON API endpoints (AJAX) for categories & stock (protected)
    Route::prefix('api')->name('api.')->group(function () {
        // Category API routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
        Route::get('/categories/{id}/products', [CategoryController::class, 'getProducts'])->name('categories.products');

        // Stock API routes
        Route::get('/stock/data', [StockController::class, 'getStockData'])->name('stock.data');
        Route::get('/stock/levels', [StockController::class, 'getLevels'])->name('stock.levels');
        Route::get('/stock/alerts', [StockController::class, 'getAlerts'])->name('stock.alerts');
        Route::post('/stock/update/{id}', [StockController::class, 'updateStock'])->name('stock.update');
        Route::post('/stock/thresholds/{id}', [StockController::class, 'setThresholds'])->name('stock.setThresholds');
        Route::get('/stock/history/{id}', [StockController::class, 'getMovementHistory'])->name('stock.getHistory');
        Route::post('/stock/alerts/{id}/dismiss', [StockController::class, 'dismissAlert'])->name('stock.dismissAlert');
        Route::post('/stock/report', [StockController::class, 'generateReport'])->name('stock.generateReport');
        Route::get('/stock/notifications', [StockController::class, 'getNotifications'])->name('stock.getNotifications');

        // Order API routes
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');

        // Product API routes are already registered above under inventory/api/products
    });

    // Sales & Reports routes (protected)
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/report', [SalesReportController::class, 'index'])->name('report');
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

        Route::get('/orders/{id}/details', [SalesReportController::class, 'getOrderDetails'])->name('orders.details');
        Route::post('/orders/{id}/print', [SalesReportController::class, 'printReceipt'])->name('orders.printReceipt');

        Route::get('/dashboard-stats', [SalesReportController::class, 'getDashboardStats'])->name('dashboardStats');
        Route::get('/realtime-updates', [SalesReportController::class, 'getRealtimeUpdates'])->name('realtimeUpdates');
        Route::get('/realtime', [SalesReportController::class, 'getRealtimeUpdates'])->name('realtime');
    });

    // User Management (admin-only)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // main user management page (views/actions inside controller)
        Route::get('/', fn() => view('admin.home'))->name('home');

        // user CRUD resource (if you prefer a resource controller)
        Route::resource('users', UserManagementController::class);

        // extra API-like user endpoints
        Route::get('/users/data', [UserManagementController::class, 'getUsers'])->name('users.data');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
        Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.resetPassword');
        Route::patch('/users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulkAction');
        Route::get('/users/stats/overview', [UserManagementController::class, 'getStats'])->name('users.stats');
        Route::get('/users/activities/recent', [UserManagementController::class, 'getRecentActivities'])->name('users.activities');
        Route::post('/users/export', [UserManagementController::class, 'export'])->name('users.export');
    });

    // HomeController or other generic pages (still protected)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});