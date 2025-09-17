
@extends('layouts.app')

@section('title', 'Sales Reports - Librewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Sales Reports & Analytics</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Sales</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Reports</a>
                </li>
            </ul>
        </div>

        <!-- Report Filters -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">
                                <i class="fas fa-filter text-info me-2"></i>
                                Report Filters
                            </h4>
                            <button class="btn btn-primary btn-sm" id="generateReportBtn">
                                <i class="fas fa-chart-bar"></i> Generate Report
                            </button>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Report Type</label>
                                    <select class="form-control form-control-sm" id="reportType">
                                        <option value="daily">Daily Sales</option>
                                        <option value="weekly">Weekly Summary</option>
                                        <option value="monthly" selected>Monthly Report</option>
                                        <option value="yearly">Yearly Overview</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">From Date</label>
                                    <input type="date" class="form-control form-control-sm" id="fromDate" value="2024-01-01">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="toDate" value="2024-01-31">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Category</label>
                                    <select class="form-control form-control-sm" id="categoryFilter">
                                        <option value="all">All Categories</option>
                                        <option value="Coffee">Coffee</option>
                                        <option value="Pastry">Pastry</option>
                                        <option value="Food">Food</option>
                                        <option value="Beverage">Beverage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Payment Method</label>
                                    <select class="form-control form-control-sm" id="paymentFilter">
                                        <option value="all">All Methods</option>
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="digital">Digital Wallet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm" id="exportBtn">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                    <button class="btn btn-secondary btn-sm" id="refreshBtn">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Summary -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Revenue</p>
                                    <h4 class="card-title text-success" id="totalRevenue">$24,580.50</h4>
                                    <span class="text-success small" id="revenueGrowth">
                                        <i class="fas fa-arrow-up"></i> +12.5%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Orders</p>
                                    <h4 class="card-title text-primary" id="totalOrders">1,245</h4>
                                    <span class="text-success small" id="ordersGrowth">
                                        <i class="fas fa-arrow-up"></i> +8.3%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Average Order</p>
                                    <h4 class="card-title text-warning" id="averageOrder">$19.75</h4>
                                    <span class="text-success small" id="avgOrderGrowth">
                                        <i class="fas fa-arrow-up"></i> +3.2%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Customers</p>
                                    <h4 class="card-title text-info" id="totalCustomers">892</h4>
                                    <span class="text-success small" id="customersGrowth">
                                        <i class="fas fa-arrow-up"></i> +15.7%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Sales Trend Chart -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Sales Trend Analysis</h4>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="dailyTrendBtn">Daily</button>
                                <button type="button" class="btn btn-outline-primary" id="weeklyTrendBtn">Weekly</button>
                                <button type="button" class="btn btn-outline-primary" id="monthlyTrendBtn">Monthly</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px;">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Performance -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Category Performance</h4>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        <div class="category-legend mt-3" id="categoryLegend">
                            <!-- Legend will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="row mb-4">
            <!-- Top Products -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Top Selling Products</h4>
                            <span class="badge badge-success">This Month</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="top-products" id="topProductsList">
                            <!-- Top products will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Methods Distribution</h4>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 250px;">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                        <div class="payment-stats mt-3" id="paymentStats">
                            <!-- Payment stats will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Sales Table -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Detailed Sales Data</h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-info btn-sm" id="viewModeToggle">
                                    <i class="fas fa-list"></i> Table View
                                </button>
                                <button class="btn btn-outline-success btn-sm" id="exportTableBtn">
                                    <i class="fas fa-file-excel"></i> Export Table
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="salesTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Category</th>
                                        <th>Payment Method</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTableBody">
                                    <!-- Sales data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="pagination-info">
                                Showing <span id="showingFrom">1</span> to <span id="showingTo">10</span> of <span id="totalRecords">245</span> records
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination">
                                    <!-- Pagination will be loaded here -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Analytics -->
        <div class="row mb-4">
            <!-- Hourly Sales Pattern -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Peak Hours Analysis</h4>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 200px;">
                            <canvas id="hourlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Analytics -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Insights</h4>
                    </div>
                    <div class="card-body">
                        <div class="customer-insights" id="customerInsights">
                            <!-- Customer insights will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Options Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Export Format</label>
                    <select class="form-control" id="exportFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF Report</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Include</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeSummary" checked>
                        <label class="form-check-label" for="includeSummary">Summary Statistics</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeCharts" checked>
                        <label class="form-check-label" for="includeCharts">Charts & Graphs</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="includeDetails" checked>
                        <label class="form-check-label" for="includeDetails">Detailed Data</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Report To</label>
                    <input type="email" class="form-control" id="emailTo" placeholder="Optional: Enter email address">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmExport">Export Report</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-stats .numbers {
    position: relative;
}

.growth-indicator {
    position: absolute;
    right: 0;
    top: 0;
    font-size: 0.75rem;
}

.top-products {
    max-height: 350px;
    overflow-y: auto;
}

.product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.product-item:last-child {
    border-bottom: none;
}

.product-info {
    flex: 1;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
}

.product-category {
    font-size: 0.8rem;
    color: #6c757d;
}

.product-stats {
    text-align: right;
}

.product-sales {
    font-weight: 700;
    color: #1572e8;
}

.product-quantity {
    font-size: 0.8rem;
    color: #6c757d;
}

.category-legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    margin-right: 8px;
}

.legend-text {
    font-size: 0.85rem;
    color: #495057;
}

.legend-value {
    margin-left: auto;
    font-weight: 600;
    color: #1572e8;
}

.payment-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.payment-stat:last-child {
    border-bottom: none;
}

.payment-method {
    font-weight: 600;
}

.payment-amount {
    color: #1572e8;
    font-weight: 600;
}

.payment-percentage {
    font-size: 0.8rem;
    color: #6c757d;
}

.customer-insight-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #1572e8;
}

.insight-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #1572e8;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.insight-content {
    flex: 1;
}

.insight-title {
    font-weight: 600;
    margin-bottom: 2px;
}

.insight-description {
    font-size: 0.85rem;
    color: #6c757d;
}

.insight-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1572e8;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    font-size: 0.85rem;
    vertical-align: middle;
}

.status-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.btn-action {
    font-size: 0.75rem;
    padding: 4px 8px;
    margin: 0 2px;
}

.report-loading {
    text-align: center;
    padding: 40px;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #1572e8;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data - replace with actual API calls
    let reportData = {
        summary: {
            totalRevenue: 24580.50,
            totalOrders: 1245,
            averageOrder: 19.75,
            totalCustomers: 892,
            growth: {
                revenue: 12.5,
                orders: 8.3,
                avgOrder: 3.2,
                customers: 15.7
            }
        },
        salesTrend: {
            labels: ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5', 'Jan 6', 'Jan 7'],
            data: [850, 920, 1100, 980, 1250, 1380, 1150]
        },
        categories: {
            labels: ['Coffee', 'Pastry', 'Food', 'Beverage'],
            data: [45, 25, 20, 10],
            colors: ['#8B4513', '#ffc107', '#28a745', '#17a2b8']
        },
        topProducts: [
            { name: 'Cappuccino', category: 'Coffee', sales: 4250.00, quantity: 185 },
            { name: 'Croissant', category: 'Pastry', sales: 2890.50, quantity: 142 },
            { name: 'Latte', category: 'Coffee', sales: 3650.75, quantity: 156 },
            { name: 'Caesar Salad', category: 'Food', sales: 1980.25, quantity: 89 },
            { name: 'Americano', category: 'Coffee', sales: 2340.00, quantity: 98 }
        ],
        paymentMethods: {
            labels: ['Cash', 'Card', 'Digital Wallet'],
            data: [40, 45, 15],
            colors: ['#28a745', '#1572e8', '#ffc107']
        },
        hourlyPattern: {
            labels: ['6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
            data: [120, 280, 450, 380, 320, 290, 520, 480, 360, 290, 250, 180]
        }
    };

    let charts = {};
    let currentPage = 1;
    let recordsPerPage = 10;

    // Initialize page
    loadReportData();
    setupEventListeners();
    initializeCharts();

    function setupEventListeners() {
        // Filter changes
        document.getElementById('reportType').addEventListener('change', updateDateRange);
        document.getElementById('generateReportBtn').addEventListener('click', generateReport);
        document.getElementById('exportBtn').addEventListener('click', showExportModal);
        document.getElementById('refreshBtn').addEventListener('click', refreshReport);

        // Chart view toggles
        document.getElementById('dailyTrendBtn').addEventListener('click', () => updateTrendChart('daily'));
        document.getElementById('weeklyTrendBtn').addEventListener('click', () => updateTrendChart('weekly'));
        document.getElementById('monthlyTrendBtn').addEventListener('click', () => updateTrendChart('monthly'));

        // Export functionality
        document.getElementById('confirmExport').addEventListener('click', exportReport);
        document.getElementById('exportTableBtn').addEventListener('click', exportTable);

        // View mode toggle
        document.getElementById('viewModeToggle').addEventListener('click', toggleViewMode);
    }

    function updateDateRange() {
        const reportType = document.getElementById('reportType').value;
        const today = new Date();
        let fromDate, toDate;

        switch(reportType) {
            case 'daily':
                fromDate = toDate = today.toISOString().split('T')[0];
                break;
            case 'weekly':
                fromDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                toDate = today.toISOString().split('T')[0];
                break;
            case 'monthly':
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                toDate = today.toISOString().split('T')[0];
                break;
            case 'yearly':
                fromDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                toDate = today.toISOString().split('T')[0];
                break;
        }

        if (fromDate && toDate) {
            document.getElementById('fromDate').value = fromDate;
            document.getElementById('toDate').value = toDate;
        }
    }

    function loadReportData() {
        // Update summary statistics
        document.getElementById('totalRevenue').textContent = `$${reportData.summary.totalRevenue.toLocaleString()}`;
        document.getElementById('totalOrders').textContent = reportData.summary.totalOrders.toLocaleString();
        document.getElementById('averageOrder').textContent = `$${reportData.summary.averageOrder}`;
        document.getElementById('totalCustomers').textContent = reportData.summary.totalCustomers.toLocaleString();

        // Update growth indicators
        document.getElementById('revenueGrowth').innerHTML = `<i class="fas fa-arrow-up"></i> +${reportData.summary.growth.revenue}%`;
        document.getElementById('ordersGrowth').innerHTML = `<i class="fas fa-arrow-up"></i> +${reportData.summary.growth.orders}%`;
        document.getElementById('avgOrderGrowth').innerHTML = `<i class="fas fa-arrow-up"></i> +${reportData.summary.growth.avgOrder}%`;
        document.getElementById('customersGrowth').innerHTML = `<i class="fas fa-arrow-up"></i> +${reportData.summary.growth.customers}%`;

        // Load top products
        loadTopProducts();
        
        // Load payment stats
        loadPaymentStats();
        
        // Load customer insights
        loadCustomerInsights();
        
        // Load sales table
        loadSalesTable();
    }

    function initializeCharts() {
        // Sales Trend Chart
        const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
        charts.salesTrend = new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: reportData.salesTrend.labels,
                datasets: [{
                    label: 'Daily Sales',
                    data: reportData.salesTrend.data,
                    borderColor: '#1572e8',
                    backgroundColor: 'rgba(21, 114, 232, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        charts.category = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: reportData.categories.labels,
                datasets: [{
                    data: reportData.categories.data,
                    backgroundColor: reportData.categories.colors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Update category legend
        updateCategoryLegend();

        // Payment Method Chart
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        charts.payment = new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: reportData.paymentMethods.labels,
                datasets: [{
                    data: reportData.paymentMethods.data,
                    backgroundColor: reportData.paymentMethods.colors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Hourly Pattern Chart
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        charts.hourly = new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: reportData.hourlyPattern.labels,
                datasets: [{
                    label: 'Orders',
                    data: reportData.hourlyPattern.data,
                    backgroundColor: 'rgba(21, 114, 232, 0.6)',
                    borderColor: '#1572e8',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function updateCategoryLegend() {
        const legend = document.getElementById('categoryLegend');
        legend.innerHTML = reportData.categories.labels.map((label, index) => `
            <div class="category-legend-item">
                <div class="legend-color" style="background-color: ${reportData.categories.colors[index]}"></div>
                <span class="legend-text">${label}</span>
                <span class="legend-value">${reportData.categories.data[index]}%</span>
            </div>
        `).join('');
    }

    function loadTopProducts() {
        const container = document.getElementById('topProductsList');
        container.innerHTML = reportData.topProducts.map((product, index) => `
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name">${index + 1}. ${product.name}</div>
                    <div class="product-category">${product.category}</div>
                </div>
                <div class="product-stats">
                    <div class="product-sales">$${product.sales.toLocaleString()}</div>
                    <div class="product-quantity">${product.quantity} sold</div>
                </div>
            </div>
        `).join('');
    }

    function loadPaymentStats() {
        const container = document.getElementById('paymentStats');
        const total = reportData.paymentMethods.data.reduce((sum, val) => sum + val, 0);
        
        container.innerHTML = reportData.paymentMethods.labels.map((method, index) => {
            const percentage = ((reportData.paymentMethods.data[index] / total) * 100).toFixed(1);
            const amount = (reportData.summary.totalRevenue * (reportData.paymentMethods.data[index] / 100)).toFixed(2);
            
            return `
                <div class="payment-stat">
                    <div>
                        <div class="payment-method">${method}</div>
                        <div class="payment-percentage">${percentage}%</div>
                    </div>
                    <div class="payment-amount">$${parseFloat(amount).toLocaleString()}</div>
                </div>
            `;
        }).join('');
    }

    function loadCustomerInsights() {
        const insights = [
            {
                icon: 'fas fa-crown',
                title: 'VIP Customers',
                description: 'Customers with 10+ orders',
                value: '127'
            },
            {
                icon: 'fas fa-user-plus',
                title: 'New Customers',
                description: 'First-time buyers this month',
                value: '89'
            },
            {
                icon: 'fas fa-redo',
                title: 'Return Rate',
                description: 'Customer retention percentage',
                value: '73%'
            },
            {
                icon: 'fas fa-star',
                title: 'Avg Rating',
                description: 'Customer satisfaction score',
                value: '4.8'
            }
        ];

        const container = document.getElementById('customerInsights');
        container.innerHTML = insights.map(insight => `
            <div class="customer-insight-item">
                <div class="insight-icon">
                    <i class="${insight.icon}"></i>
                </div>
                <div class="insight-content">
                    <div class="insight-title">${insight.title}</div>
                    <div class="insight-description">${insight.description}</div>
                </div>
                <div class="insight-value">${insight.value}</div>
            </div>
        `).join('');
    }

    function loadSalesTable() {
        // Sample sales data
        const salesData = [
            {
                id: 1,
                date: '2024-01-15',
                orderId: 'ORD-001',
                customer: 'John Doe',
                items: 'Cappuccino, Croissant',
                category: 'Coffee & Pastry',
                paymentMethod: 'Card',
                amount: 12.50,
                status: 'completed'
            },
            {
                id: 2,
                date: '2024-01-15',
                orderId: 'ORD-002',
                customer: 'Jane Smith',
                items: 'Latte, Caesar Salad',
                category: 'Coffee & Food',
                paymentMethod: 'Cash',
                amount: 18.75,
                status: 'completed'
            }
        ];

        const tbody = document.getElementById('salesTableBody');
        tbody.innerHTML = salesData.map(sale => `
            <tr>
                <td>${new Date(sale.date).toLocaleDateString()}</td>
                <td><strong>${sale.orderId}</strong></td>
                <td>${sale.customer}</td>
                <td>
                    <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${sale.items}">
                        ${sale.items}
                    </div>
                </td>
                <td>${sale.category}</td>
                <td>${sale.paymentMethod}</td>
                <td><strong>$${sale.amount.toFixed(2)}</strong></td>
                <td>
                    <span class="badge status-badge status-${sale.status}">
                        ${sale.status.toUpperCase()}
                    </span>
                </td>
                <td>
                    <button class="btn btn-outline-primary btn-action" onclick="viewOrderDetails(${sale.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-info btn-action" onclick="printReceipt(${sale.id})" title="Print Receipt">
                        <i class="fas fa-print"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        updatePagination(salesData.length);
    }

    function updatePagination(totalRecords) {
        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        const showingFrom = (currentPage - 1) * recordsPerPage + 1;
        const showingTo = Math.min(currentPage * recordsPerPage, totalRecords);

        document.getElementById('showingFrom').textContent = showingFrom;
        document.getElementById('showingTo').textContent = showingTo;
        document.getElementById('totalRecords').textContent = totalRecords;

        // Generate pagination buttons
        const pagination = document.getElementById('pagination');
        let paginationHTML = '';

        // Previous button
        if (currentPage > 1) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a></li>`;
        }

        // Page numbers
        for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
            paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>`;
        }

        // Next button
        if (currentPage < totalPages) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a></li>`;
        }

        pagination.innerHTML = paginationHTML;
    }

    function updateTrendChart(period) {
        // Update active button
        document.querySelectorAll('[id$="TrendBtn"]').forEach(btn => btn.classList.remove('active'));
        document.getElementById(period + 'TrendBtn').classList.add('active');

        // Update chart data based on period
        let newData, newLabels;
        
        switch(period) {
            case 'daily':
                newLabels = ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5', 'Jan 6', 'Jan 7'];
                newData = [850, 920, 1100, 980, 1250, 1380, 1150];
                break;
            case 'weekly':
                newLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                newData = [5800, 6200, 7100, 6500];
                break;
            case 'monthly':
                newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                newData = [24500, 26800, 29200, 27600, 31000, 28900];
                break;
        }

        charts.salesTrend.data.labels = newLabels;
        charts.salesTrend.data.datasets[0].data = newData;
        charts.salesTrend.update();
    }

    function generateReport() {
        // Show loading state
        showLoadingState();

        // Simulate API call
        setTimeout(() => {
            loadReportData();
            hideLoadingState();
            alert('Report generated successfully!');
        }, 2000);
    }

    function refreshReport() {
        const icon = document.getElementById('refreshBtn').querySelector('i');
        icon.classList.add('fa-spin');
        
        setTimeout(() => {
            loadReportData();
            Object.values(charts).forEach(chart => chart.update());
            icon.classList.remove('fa-spin');
        }, 1000);
    }

    function showExportModal() {
        const modal = new bootstrap.Modal(document.getElementById('exportModal'));
        modal.show();
    }

    function exportReport() {
        const format = document.getElementById('exportFormat').value;
        const includeSummary = document.getElementById('includeSummary').checked;
        const includeCharts = document.getElementById('includeCharts').checked;
        const includeDetails = document.getElementById('includeDetails').checked;
        const emailTo = document.getElementById('emailTo').value;

        // Simulate export process
        alert(`Exporting report in ${format.toUpperCase()} format...\nIncluding: ${[
            includeSummary ? 'Summary' : '',
            includeCharts ? 'Charts' : '',
            includeDetails ? 'Details' : ''
        ].filter(Boolean).join(', ')}`);

        const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
        modal.hide();
    }

    function exportTable() {
        // Simulate table export
        alert('Exporting table data to Excel...');
    }

    function toggleViewMode() {
        const button = document.getElementById('viewModeToggle');
        const icon = button.querySelector('i');
        
        if (icon.classList.contains('fa-list')) {
            icon.classList.remove('fa-list');
            icon.classList.add('fa-th');
            button.innerHTML = '<i class="fas fa-th"></i> Card View';
        } else {
            icon.classList.remove('fa-th');
            icon.classList.add('fa-list');
            button.innerHTML = '<i class="fas fa-list"></i> Table View';
        }
    }

    function showLoadingState() {
        document.body.insertAdjacentHTML('beforeend', `
            <div id="reportLoading" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.8); z-index: 9999;">
                <div class="text-center">
                    <div class="loading-spinner"></div>
                    <h5>Generating Report...</h5>
                    <p class="text-muted">Please wait while we compile your data</p>
                </div>
            </div>
        `);
    }

    function hideLoadingState() {
        const loading = document.getElementById('reportLoading');
        if (loading) loading.remove();
    }

    // Global functions
    window.changePage = function(page) {
        currentPage = page;
        loadSalesTable();
    };

    window.viewOrderDetails = function(orderId) {
        alert(`Viewing details for order ID: ${orderId}`);
    };

    window.printReceipt = function(orderId) {
        alert(`Printing receipt for order ID: ${orderId}`);
    };
});
</script>
@endpush
