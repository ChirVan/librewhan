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
                                    <input type="date" class="form-control form-control-sm" id="fromDate" value="{{ now()->startOfMonth()->toDateString() }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">To Date</label>
                                    <input type="date" class="form-control form-control-sm" id="toDate" value="{{ now()->toDateString() }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Category</label>
                                    <select class="form-control form-control-sm" id="categoryFilter">
                                        <option value="all">All Categories</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Payment Mode</label>
                                    <select class="form-control form-control-sm" id="paymentFilter">
                                        <option value="all">All Modes</option>
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

        <!-- Key Metrics Summary (Dynamic) -->
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
                                    <h4 class="card-title text-success">₱{{ number_format($totalRevenue, 2) }}</h4>
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
                                    <h4 class="card-title text-primary">{{ $totalOrders }}</h4>
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
                                    <h4 class="card-title text-warning">₱{{ number_format($avgOrder, 2) }}</h4>
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
                                    <h4 class="card-title text-info">{{ $uniqueCustomers }}</h4>
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
                        <div class="category-legend mt-3" id="categoryLegend"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="row mb-4">
            <!-- Top Products (Dynamic) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Top Selling Products</h4>
                            <span class="badge badge-success">This Month</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="top-products">
                            @forelse ($topProducts as $product)
                                <div class="product-item">
                                    <div class="product-info">
                                        <div class="product-name">{{ $loop->iteration }}. {{ $product->name }}</div>
                                        <div class="product-category">{{ $product->category ?? '' }}</div>
                                    </div>
                                    <div class="product-stats">
                                        <div class="product-sales">₱{{ number_format($product->total_sales, 2) }}</div>
                                        <div class="product-quantity">{{ $product->total_qty }} sold</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">No sales data available.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods (Dynamic) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Payment Modes Distribution</h4>
                    </div>
                    <div class="card-body">
                        <div class="payment-stats mt-3">
                            @forelse ($paymentStats as $stat)
                                <div class="payment-stat">
                                    <div>
                                        <div class="payment-method">{{ $stat['method'] }}</div>
                                        <div class="payment-percentage">{{ $stat['percent'] }}%</div>
                                    </div>
                                    <div class="payment-amount">P{{ number_format($stat['amount'], 2) }}</div>
                                </div>
                            @empty
                                <div class="text-muted">No payment data available.</div>
                            @endforelse
                        </div>
                        <div class="mt-2">
                            <strong>Most Used:</strong> {{ $mostUsedPayment ?? 'N/A' }}
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
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTableBody"></tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="pagination-info">
                                Showing <span id="showingFrom">1</span> to <span id="showingTo">10</span> of <span id="totalRecords">0</span> records
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                            </nav>
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
/* keep your existing styles (unchanged) */
.card-stats .numbers { position: relative; }
/* ... other styles omitted for brevity; keep as before ... */
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // route endpoints (blade helpers)
    const routes = {
        summary: "{{ route('sales.report.summary') }}",
        trend: "{{ route('sales.report.trends') }}",
        categories: "{{ route('sales.report.categories') }}",
        topProducts: "{{ route('sales.report.topProducts') }}",
        paymentMethods: "{{ route('sales.report.paymentMethods') }}",
        hourly: "{{ route('sales.report.hourlyPattern') }}",
        salesData: "{{ route('sales.report.salesData') }}",
        generateReport: "{{ route('sales.report.generate') }}",
    };

    // default containers
    let charts = {};
    let currentPage = 1;
    let recordsPerPage = 10;

    // bootstrap DOM readiness
    init();

    async function init() {
        initializeCharts(); // create empty charts
        await loadAllData();
        setupEventListeners();
    }

    function setupEventListeners() {
        document.getElementById('reportType').addEventListener('change', updateDateRange);
        document.getElementById('generateReportBtn').addEventListener('click', generateReport);
        document.getElementById('exportBtn').addEventListener('click', () => new bootstrap.Modal(document.getElementById('exportModal')).show());
        document.getElementById('refreshBtn').addEventListener('click', refreshReport);

        document.getElementById('dailyTrendBtn').addEventListener('click', () => updateTrendChart('daily'));
        document.getElementById('weeklyTrendBtn').addEventListener('click', () => updateTrendChart('weekly'));
        document.getElementById('monthlyTrendBtn').addEventListener('click', () => updateTrendChart('monthly'));

        document.getElementById('confirmExport').addEventListener('click', exportReport);
        document.getElementById('exportTableBtn')?.addEventListener('click', exportTable);
        document.getElementById('viewModeToggle')?.addEventListener('click', toggleViewMode);
    }

    async function loadAllData() {
        try {
            const [summaryRes, trendRes, categoriesRes, topRes, paymentRes, hourlyRes, salesRes] = await Promise.allSettled([
                fetch(routes.summary).then(r => r.json()),
                fetch(routes.trend + '?period=monthly').then(r => r.json()),
                fetch(routes.categories).then(r => r.json()),
                fetch(routes.topProducts + '?limit=10').then(r => r.json()),
                fetch(routes.paymentMethods).then(r => r.json()),
                fetch(routes.hourly).then(r => r.json()),
                fetch(routes.salesData + `?page=${currentPage}&per_page=${recordsPerPage}`).then(r => r.json())
            ]);

            if (summaryRes.status === 'fulfilled' && summaryRes.value.success) {
                setSummary(summaryRes.value.summary ?? summaryRes.value);
            } else if (summaryRes.status === 'fulfilled') {
                setSummary(summaryRes.value);
            }

            if (trendRes.status === 'fulfilled') {
                updateTrendChartDataFromApi(trendRes.value.trend ?? trendRes.value);
            }

            if (categoriesRes.status === 'fulfilled') {
                setCategoryChart(categoriesRes.value.categories ?? (categoriesRes.value.data ?? []));
            }

            if (topRes.status === 'fulfilled') {
                setTopProducts(topRes.value.products ?? topRes.value.data ?? []);
            }

            if (paymentRes.status === 'fulfilled') {
                setPaymentMethods(paymentRes.value.payment_methods ?? paymentRes.value);
            }

            if (hourlyRes.status === 'fulfilled') {
                setHourlyChart(hourlyRes.value.hourly_pattern ?? hourlyRes.value);
            }

            if (salesRes.status === 'fulfilled') {
                setSalesTable(salesRes.value.data ?? salesRes.value);
            }
        } catch (err) {
            console.error('Error loading report data', err);
        }
    }

    function setSummary(summary) {
        // Accept both nested and root formats
        const s = summary || {};
        document.getElementById('totalRevenue').textContent = s.total_revenue ? `₱${Number(s.total_revenue).toLocaleString()}` : (s.totalRevenue ? `₱${Number(s.totalRevenue).toLocaleString()}` : '₱0.00');
        document.getElementById('totalOrders').textContent = s.total_orders ?? s.totalOrders ?? '0';
        document.getElementById('averageOrder').textContent = s.average_order ? `₱${Number(s.average_order).toFixed(2)}` : (s.averageOrder ? `₱${Number(s.averageOrder).toFixed(2)}` : '₱0.00');
        document.getElementById('totalCustomers').textContent = s.total_customers ?? s.totalCustomers ?? '0';
        // growth fields if present
        document.getElementById('revenueGrowth').innerHTML = s.growth?.revenue ? `<i class="fas fa-arrow-up"></i> +${s.growth.revenue}%` : (s.growth?.revenue ? `<i class="fas fa-arrow-up"></i> +${s.growth.revenue}%` : '—');
    }

    function updateTrendChartDataFromApi(trend) {
        if (!trend) return;
        const labels = trend.labels ?? (trend.labels || []);
        const data = trend.data ?? (trend.data || []);  
        charts.salesTrend.data.labels = labels;
        charts.salesTrend.data.datasets[0].data = data;
        charts.salesTrend.update();
    }

    function setCategoryChart(categories) {
        // categories expected as { labels:[], data:[], colors:[] } or as data array
        if (Array.isArray(categories)) {
            // convert
            const labels = categories.map(c => c.name ?? c.label);
            const data = categories.map(c => c.sales ?? c.value ?? 0);
            charts.category.data.labels = labels;
            charts.category.data.datasets[0].data = data;
        } else {
            charts.category.data.labels = categories.labels ?? [];
            charts.category.data.datasets[0].data = categories.data ?? [];
            charts.category.data.datasets[0].backgroundColor = categories.colors ?? charts.category.data.datasets[0].backgroundColor;
        }
        charts.category.update();
        // update legend
        const legendEl = document.getElementById('categoryLegend');
        legendEl.innerHTML = (categories.labels ?? []).map((label, i) => `
            <div class="category-legend-item">
                <div class="legend-color" style="background-color: ${categories.colors?.[i] ?? '#ccc'}"></div>
                <span class="legend-text">${label}</span>
                <span class="legend-value">${categories.data?.[i] ?? 0}%</span>
            </div>
        `).join('');
    }

    function setTopProducts(products) {
        const container = document.getElementById('topProductsList');
        container.innerHTML = (products || []).map((p, index) => `
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name">${index + 1}. ${p.name}</div>
                    <div class="product-category">${p.category ?? p.category_name ?? ''}</div>
                </div>
                <div class="product-stats">
                    <div class="product-sales">${(p.sales ?? p.total_sales ?? 0).toLocaleString()}</div>
                    <div class="product-quantity">${p.quantity ?? p.total_quantity ?? 0} sold</div>
                </div>
            </div>
        `).join('');
    }

    function setPaymentMethods(pm) {
        // Accept payment_methods or paymentMethods
        const data = pm.payment_methods ?? pm;
        const labels = data.labels ?? [];
        const values = data.data ?? [];
        const colors = data.colors ?? [];
        // update chart
        charts.payment.data.labels = labels;
        charts.payment.data.datasets[0].data = values;
        charts.payment.update();
        // update list / stats
        const container = document.getElementById('paymentStats');
        const total = (values || []).reduce((s, v) => s + (v || 0), 0) || 1;
        const totalRevenue = (document.getElementById('totalRevenue').textContent || '$0').replace(/[^0-9.-]+/g,"") * 1 || 0;
        container.innerHTML = (labels || []).map((label, idx) => {
            const percent = total ? ((values[idx] / total) * 100).toFixed(1) : '0.0';
            const amount = totalRevenue ? (totalRevenue * (values[idx] / 100)).toFixed(2) : (data.amounts?.[idx] ?? 0);
            return `<div class="payment-stat">
                <div>
                    <div class="payment-method">${label}</div>
                    <div class="payment-percentage">${percent}%</div>
                </div>
                <div class="payment-amount">$${Number(amount).toLocaleString()}</div>
            </div>`;
        }).join('');
    }

    function setHourlyChart(hourly) {
        const labels = hourly.labels ?? hourly.hourly_pattern?.labels ?? [];
        const data = hourly.data ?? hourly.orders ?? hourly.hourly_pattern?.orders ?? [];
        charts.hourly.data.labels = labels;
        charts.hourly.data.datasets[0].data = data;
        charts.hourly.update();
    }

    function setSalesTable(salesPayload) {
        const rows = Array.isArray(salesPayload) ? salesPayload : (salesPayload.data ?? salesPayload);
        const tbody = document.getElementById('salesTableBody');
        tbody.innerHTML = (rows || []).map(sale => {
            const payment = sale.payment_mode ?? sale.payment_method ?? sale.paymentMethod ?? 'Unknown';
            const items = (sale.items ?? sale.items_list ?? sale.items_text) || '';
            const date = sale.date ?? sale.created_at ?? '';
            return `<tr>
                <td>${new Date(date).toLocaleString()}</td>
                <td><strong>${sale.order_id ?? sale.order_number ?? ('ORD-' + (sale.id || ''))}</strong></td>
                <td>${sale.customer ?? sale.customer_name ?? ''}</td>
                <td><div style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${items}">${items}</div></td>
                <td>${sale.category ?? ''}</td>
                <td>${payment}</td>
                <td><strong>$${Number(sale.amount ?? sale.total ?? 0).toFixed(2)}</strong></td>
                <td><span class="badge status-badge status-${(sale.status ?? '').toLowerCase()}">${(sale.status ?? 'N/A').toUpperCase()}</span></td>
                <td>
                    <button class="btn btn-outline-primary btn-action" onclick="viewOrderDetails(${sale.id ?? 0})" title="View Details"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-outline-info btn-action" onclick="printReceipt(${sale.id ?? 0})" title="Print Receipt"><i class="fas fa-print"></i></button>
                </td>
            </tr>`;
        }).join('');
        // update pagination numbers if available
        const total = salesPayload.total ?? (rows.length || 0);
        document.getElementById('totalRecords').textContent = total;
        updatePagination(rows.length || total);
    }

    function updatePagination(totalRecords) {
        const totalPages = Math.max(1, Math.ceil(totalRecords / recordsPerPage));
        const showingFrom = (currentPage - 1) * recordsPerPage + 1;
        const showingTo = Math.min(currentPage * recordsPerPage, totalRecords);
        document.getElementById('showingFrom').textContent = showingFrom;
        document.getElementById('showingTo').textContent = showingTo;
    }

    // ---------- Chart initialization ----------
    function initializeCharts() {
        // Sales Trend
        const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
        charts.salesTrend = new Chart(salesTrendCtx, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Sales', data: [], borderWidth: 3, fill: true }] },
            options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
        });

        // Category
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        charts.category = new Chart(categoryCtx, {
            type: 'doughnut',
            data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },
            options: { responsive:true, maintainAspectRatio:false }
        });

        // Payment
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        charts.payment = new Chart(paymentCtx, {
            type: 'pie',
            data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },
            options: { responsive:true, maintainAspectRatio:false }
        });

        // Hourly
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        charts.hourly = new Chart(hourlyCtx, {
            type: 'bar',
            data: { labels: [], datasets: [{ label:'Orders', data: [] }] },
            options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
        });
    }

    // ---------- helpers / UI interactions ----------
    function updateTrendChart(period) {
        document.querySelectorAll('[id$="TrendBtn"]').forEach(btn => btn.classList.remove('active'));
        document.getElementById(period + 'TrendBtn').classList.add('active');

        // fetch new trend data for the chosen period
        fetch(routes.trend + `?period=${period}`)
            .then(r => r.json())
            .then(json => {
                const trend = json.trend ?? json;
                if (!trend) return;
                charts.salesTrend.data.labels = trend.labels ?? [];
                charts.salesTrend.data.datasets[0].data = trend.data ?? [];
                charts.salesTrend.update();
            }).catch(err => console.error(err));
    }

    function generateReport() {
        showLoadingState();
        // call server endpoint if you want server-generated heavy report
        fetch(routes.generateReport, { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')} })
            .then(r => r.json())
            .then(() => {
                hideLoadingState();
                loadAllData();
                alert('Report generated and refreshed.');
            }).catch(err => {
                hideLoadingState();
                console.error(err);
                alert('Failed to generate report.');
            });
    }

    function refreshReport() {
        document.getElementById('refreshBtn').querySelector('i').classList.add('fa-spin');
        setTimeout(async () => {
            await loadAllData();
            Object.values(charts).forEach(c => c.update && c.update());
            document.getElementById('refreshBtn').querySelector('i').classList.remove('fa-spin');
        }, 700);
    }

    function exportReport() { alert('Exporting (not implemented)'); const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal')); modal.hide(); }
    function exportTable() { alert('Export table not implemented'); }
    function toggleViewMode() {
        const button = document.getElementById('viewModeToggle');
        const icon = button.querySelector('i');
        if (icon.classList.contains('fa-list')) { icon.classList.replace('fa-list','fa-th'); button.innerHTML = '<i class="fas fa-th"></i> Card View'; } 
        else { icon.classList.replace('fa-th','fa-list'); button.innerHTML = '<i class="fas fa-list"></i> Table View'; }
    }

    function showLoadingState() {
        if (document.getElementById('reportLoading')) return;
        document.body.insertAdjacentHTML('beforeend', `<div id="reportLoading" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(255,255,255,0.8); z-index: 9999;">
            <div class="text-center">
                <div class="loading-spinner"></div>
                <h5>Generating Report...</h5>
            </div>
        </div>`);
    }
    function hideLoadingState() { document.getElementById('reportLoading')?.remove(); }

    // expose a couple helper functions for buttons
    window.changePage = function(page) { currentPage = page; loadAllData(); };
    window.viewOrderDetails = function(orderId) { if (!orderId) return alert('No order id'); fetch("{{ url('sales/orders') }}/" + orderId + "/details").then(r=>r.json()).then(d=>alert(JSON.stringify(d.order, null, 2))); };
    window.printReceipt = function(orderId) { fetch("{{ url('sales') }}/orders/" + orderId + "/print", { method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')} }).then(r=>r.json()).then(j=>alert(j.message)); };
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    async function fetchSummary(from, to) {
        const q = new URLSearchParams();
        if (from) q.set('from', from);
        if (to) q.set('to', to);
        const res = await fetch(`/sales/api/summary?${q.toString()}`, { headers: { 'Accept': 'application/json' }});
        if (!res.ok) return null;
        return await res.json();
    }

    async function fetchTopProducts(from, to, limit=5) {
        const q = new URLSearchParams();
        if (from) q.set('from', from);
        if (to) q.set('to', to);
        if (limit) q.set('limit', limit);
        const res = await fetch(`/sales/api/top-products?${q.toString()}`, { headers: { 'Accept': 'application/json' }});
        if (!res.ok) return null;
        return await res.json();
    }

    async function refreshReportData() {
        // use the date inputs on the page, if present
        const from = document.getElementById('fromDate')?.value || null;
        const to = document.getElementById('toDate')?.value || null;

        const sumResp = await fetchSummary(from, to);
        if (sumResp && sumResp.success) {
            document.getElementById('totalRevenue').textContent = `$${sumResp.summary.total_revenue.toLocaleString()}`;
            document.getElementById('totalOrders').textContent = sumResp.summary.total_orders.toLocaleString();
            document.getElementById('averageOrder').textContent = `$${sumResp.summary.average_order}`;
        }

        const topResp = await fetchTopProducts(from, to, 5);
        if (topResp && topResp.success) {
            const container = document.getElementById('topProductsList');
            container.innerHTML = topResp.products.map((p, i) => `
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">${i+1}. ${p.name}</div>
                        <div class="product-category">—</div>
                    </div>
                    <div class="product-stats">
                        <div class="product-sales">$${Number(p.total_sales).toFixed(2)}</div>
                        <div class="product-quantity">${p.total_qty} sold</div>
                    </div>
                </div>
            `).join('');
        }
    }

    // run on load
    refreshReportData();

    // bind to generate/refresh buttons if present
    document.getElementById('generateReportBtn')?.addEventListener('click', function () {
        refreshReportData();
    });
    document.getElementById('refreshBtn')?.addEventListener('click', function () {
        refreshReportData();
    });
});
</script>
@endpush
