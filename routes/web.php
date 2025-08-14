<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\UserManagementController;

// Authentication Routes
Route::get('/', [LoginController::class, 'showLoginForm']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
Route::middleware(['static.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

// Product/Inventory Management Routes
Route::prefix('inventory')->name('inventory.')->group(function () {
    // Main product routes
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Stock management routes for products
    Route::patch('/products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    
    // Product utility routes
    Route::get('/products-data/low-stock', [ProductController::class, 'getLowStock'])->name('products.lowStock');
    Route::get('/products-data/categories', [ProductController::class, 'getCategories'])->name('products.categories');
    Route::post('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulkUpdate');

    // Category routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Category utility routes
    Route::patch('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
    Route::get('/categories/{id}/products', [CategoryController::class, 'getProducts'])->name('categories.products');
    Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.updateOrder');
    Route::post('/categories/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::post('/categories/bulk-update', [CategoryController::class, 'bulkUpdate'])->name('categories.bulkUpdate');
    Route::get('/categories-data/stats', [CategoryController::class, 'getStats'])->name('categories.stats');

    // Stock Level Management Routes
    Route::get('/stock', [StockController::class, 'index'])->name('stock');
    Route::get('/stock/levels', [StockController::class, 'getLevels'])->name('stock.levels');
    Route::get('/stock/alerts', [StockController::class, 'getAlerts'])->name('stock.alerts');
    Route::post('/stock/{id}/update', [StockController::class, 'updateStock'])->name('stock.update');
    Route::post('/stock/bulk-update', [StockController::class, 'bulkUpdate'])->name('stock.bulkUpdate');
    Route::post('/stock/generate-report', [StockController::class, 'generateReport'])->name('stock.report');
    Route::post('/stock/{id}/thresholds', [StockController::class, 'setThresholds'])->name('stock.thresholds');
    Route::get('/stock/{id}/history', [StockController::class, 'getMovementHistory'])->name('stock.history');
    Route::post('/stock/alerts/{id}/dismiss', [StockController::class, 'dismissAlert'])->name('stock.dismissAlert');
    Route::get('/stock/notifications', [StockController::class, 'getNotifications'])->name('stock.notifications');
});

// Sales & Reports Routes
Route::prefix('sales')->name('sales.')->group(function () {
    // Main reports page
    Route::get('/report', [SalesReportController::class, 'index'])->name('report');
    
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