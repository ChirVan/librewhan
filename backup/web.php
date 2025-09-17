<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\LoginController; // necessary removal for jetstream auth to takeover 天使
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\HomeController; // Added import 天使
use App\Providers\FortifyServiceProvider; // Added import 天使


// v 天使 v
// Home redirect after login (creates a simple landing route)
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Admin-only area (use role:admin)
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', fn() => view('admin.home'))->name('home');
    // ...admin-only routes...
});

// POS (barista + admin)
Route::middleware(['auth','role:barista|admin'])->prefix('pos')->name('pos.')->group(function(){
    Route::get('/', [OrderController::class,'take'])->name('take');
    Route::get('/pending', [OrderController::class,'pending'])->name('pending');
    Route::post('/orders', [OrderController::class,'store'])->name('orders.store');
});
// ^ 天使 ^


// Authentication Routes
// Root route: redirect to main dashboard if authenticated
Route::get('/', function () {
    // if (session()->has('authenticated')) {
    if (Auth::check()) { // line by 天使
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Old Authentication Routes code, should be commented for jetstream authentication to takeover 天使
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Debug route to clear session (temporary)
Route::get('/clear-session', function() {
    session()->flush();
    return redirect('/login')->with('message', 'Session cleared!');
});

// Test route to verify routing works
Route::get('/test-login', function() {
    return view('auth.login');
});






// Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-based dashboard routes
    Route::get('/sms/dashboard', [DashboardController::class, 'smsIndex'])->name('sms.dashboard');
    Route::get('/inventory/dashboard', [DashboardController::class, 'inventoryIndex'])->name('inventory.dashboard');

    // Order Management Routes
    Route::prefix('orders')->name('orders.')->group(function () {
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

// Inventory main pages (protected via auth only)
Route::prefix('inventory')->name('inventory.')->group(function() {
    // Page views
    Route::get('/products', [ProductController::class,'page'])->name('products.index');
    Route::get('/products/create', [ProductController::class,'create'])->name('products.create');
    Route::get('/categories', [\App\Http\Controllers\CategoryController::class,'indexPage'])->name('categories');
    Route::get('/categories/create', [\App\Http\Controllers\CategoryController::class,'create'])->name('categories.create');
    Route::get('/stock', [StockController::class,'index'])->name('stock');
    Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
    Route::get('/stock/alerts', [StockController::class, 'alerts'])->name('stock.alerts');
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history');
        
    // Inventory product JSON endpoints
    Route::prefix('api/products')->name('api.products.')->group(function() {
        Route::get('/', [ProductController::class,'list'])->name('list');
        Route::post('/', [ProductController::class,'store'])->name('store');
        Route::get('/{product}', [ProductController::class,'show'])->name('show');
        Route::put('/{product}', [ProductController::class,'update'])->name('update');
        Route::delete('/{product}', [ProductController::class,'destroy'])->name('destroy');
        Route::patch('/{product}/toggle-status', [ProductController::class,'toggleStatus'])->name('toggle');
        Route::patch('/{product}/stock', [ProductController::class,'updateStock'])->name('stock');
        Route::get('/_meta/categories', [ProductController::class,'metaCategories'])->name('metaCategories');
    });
});

// JSON API endpoints (AJAX) for categories
Route::prefix('inventory')->name('inventory.categories.')->group(function() {
    Route::get('/categories/data', [\App\Http\Controllers\CategoryController::class,'index'])->name('data');
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class,'store'])->name('store');
    Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class,'show'])->name('show');
    Route::put('/categories/{category}', [\App\Http\Controllers\CategoryController::class,'update'])->name('update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class,'destroy'])->name('destroy');
    Route::patch('/categories/{category}/toggle', [\App\Http\Controllers\CategoryController::class,'toggle'])->name('toggle');
    Route::get('/categories-export', [\App\Http\Controllers\CategoryController::class,'export'])->name('export');
});

// Sales & Reports Routes
Route::prefix('sales')->name('sales.')->group(function () {
    // Main reports page
    Route::get('/report', [SalesReportController::class, 'index'])->name('report');
    
    // SMS Interface Route
    Route::get('/sms', [SalesReportController::class, 'smsIndex'])->name('sms.index');
    
    // Report data endpoints
    Route::get('/report/summary', [SalesReportController::class, 'getSummaryData'])->name('report.summary');
    Route::get('/report/trends', [SalesReportController::class, 'getSalesTrend'])->name('report.trends');
    Route::get('/report/categories', [SalesReportController::class, 'getCategoryPerformance'])->name('report.categories');
    Route::get('/report/top-products', [SalesReportController::class, 'getTopProducts'])->name('report.topProducts');
    Route::get('/report/payment-methods', [SalesReportController::class, 'getPaymentMethods'])->name('report.paymentMethods');
    Route::get('/report/hourly-pattern', [SalesReportController::class, 'getHourlyPattern'])->name('report.hourlyPattern');
    Route::get('/report/customer-insights', [SalesReportController::class, 'getCustomerInsights'])->name('report.customerInsights');
    Route::get('/report/sales-data', [SalesReportController::class, 'getSalesData'])->name('report.salesData');
    
    // Report generation and export
    Route::post('/report/generate', [SalesReportController::class, 'generateReport'])->name('report.generate');
    Route::post('/report/export', [SalesReportController::class, 'exportData'])->name('report.export');
    
    // Order details and actions
    Route::get('/orders/{id}/details', [SalesReportController::class, 'getOrderDetails'])->name('orders.details');
    Route::post('/orders/{id}/print-receipt', [SalesReportController::class, 'printReceipt'])->name('orders.printReceipt');
    
    // Dashboard stats
    Route::get('/dashboard-stats', [SalesReportController::class, 'getDashboardStats'])->name('dashboardStats');
    Route::get('/realtime-updates', [SalesReportController::class, 'getRealtimeUpdates'])->name('realtimeUpdates');
});

// User Management Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Main user management page
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    
    // User CRUD operations
    Route::get('/users/data', [UserManagementController::class, 'getUsers'])->name('users.data');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // User management actions
    Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.resetPassword');
    Route::patch('/users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulkAction');
    
    // User statistics and activities
    Route::get('/users/stats/overview', [UserManagementController::class, 'getStats'])->name('users.stats');
    Route::get('/users/activities/recent', [UserManagementController::class, 'getRecentActivities'])->name('users.activities');
    
    // Export functionality
    Route::post('/users/export', [UserManagementController::class, 'export'])->name('users.export');
});

// API Routes for AJAX calls
Route::prefix('api')->name('api.')->group(function () {
    // Product API routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    
    // Order API routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');

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

    // Sales Reports API routes
    Route::get('/sales/summary', [SalesReportController::class, 'getSummaryData'])->name('sales.summary');
    Route::get('/sales/trends', [SalesReportController::class, 'getSalesTrend'])->name('sales.trends');
    Route::get('/sales/categories', [SalesReportController::class, 'getCategoryPerformance'])->name('sales.categories');
    Route::get('/sales/top-products', [SalesReportController::class, 'getTopProducts'])->name('sales.topProducts');
    Route::get('/sales/payment-methods', [SalesReportController::class, 'getPaymentMethods'])->name('sales.paymentMethods');
    Route::get('/sales/hourly-pattern', [SalesReportController::class, 'getHourlyPattern'])->name('sales.hourlyPattern');
    Route::get('/sales/customer-insights', [SalesReportController::class, 'getCustomerInsights'])->name('sales.customerInsights');
    Route::get('/sales/sales-data', [SalesReportController::class, 'getSalesData'])->name('sales.salesData');
    Route::post('/sales/generate-report', [SalesReportController::class, 'generateReport'])->name('sales.generateReport');
    Route::post('/sales/export', [SalesReportController::class, 'exportData'])->name('sales.export');
    Route::get('/sales/orders/{id}/details', [SalesReportController::class, 'getOrderDetails'])->name('sales.orderDetails');
    Route::post('/sales/orders/{id}/print', [SalesReportController::class, 'printReceipt'])->name('sales.printReceipt');
    Route::get('/sales/dashboard-stats', [SalesReportController::class, 'getDashboardStats'])->name('sales.dashboardStats');
    Route::get('/sales/realtime', [SalesReportController::class, 'getRealtimeUpdates'])->name('sales.realtime');

    // User Management API routes
    Route::get('/users', [UserManagementController::class, 'getUsers'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.resetPassword');
    Route::patch('/users/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulkAction');
    Route::get('/users/stats', [UserManagementController::class, 'getStats'])->name('users.getStats');
    Route::get('/users/activities', [UserManagementController::class, 'getRecentActivities'])->name('users.getActivities');
    Route::post('/users/export', [UserManagementController::class, 'export'])->name('users.doExport');
    });
});
