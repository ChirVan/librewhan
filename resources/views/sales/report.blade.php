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

        <!-- Report Filters Removed -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-filter text-info me-2"></i>
                                Sales Report
                            </h4>
                            <div class="d-flex gap-2">
                                <!-- <button class="btn btn-success btn-sm" id="exportBtn">
                                    <i class="fas fa-download"></i> Export
                                </button> -->
                                <button class="btn btn-secondary btn-sm" id="refreshBtn" onclick="window.location.reload();">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                    <!-- Card body removed as requested -->
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
                                    <h4 id="totalRevenue" class="card-title text-success">₱{{ number_format($totalRevenue, 2) }}</h4>
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
                                    <h4 id="totalOrders" class="card-title text-primary">{{ $totalOrders }}</h4>
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
                                    <h4 id="averageOrder" class="card-title text-warning">₱{{ number_format($avgOrder, 2) }}</h4>
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
                                    <h4 id="totalCustomers" class="card-title text-info">{{ $uniqueCustomers }}</h4>
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
                            <!-- Blade fallback for server-side, JS will fill #topProductsList dynamically -->
                            <div id="topProductsList">
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
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Payment Mode</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
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
/* Status badge coloring for completed orders (case-insensitive) */
.status-badge[class*="complete"] {
    background: #28a745 !important;
    color: #fff !important;
    border: none !important;
    font-weight: bold;
}
</style>
@endpush


@push('scripts')
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

        // after charts are created and data loaded, default to monthly trend
        setTimeout(() => {
            try { updateTrendChart('monthly'); } catch (e) { console.warn('updateTrendChart failed', e); }
        }, 300);
    }

    function setupEventListeners() {
        document.getElementById('exportBtn').addEventListener('click', () => new bootstrap.Modal(document.getElementById('exportModal')).show());
        document.getElementById('refreshBtn').addEventListener('click', refreshReport);
        document.getElementById('confirmExport').addEventListener('click', exportReport);
        document.getElementById('exportTableBtn')?.addEventListener('click', exportTable);
        document.getElementById('viewModeToggle')?.addEventListener('click', toggleViewMode);
    }

    async function loadAllData() {
        try {
            // build date query params from inputs if present
            const from = document.getElementById('fromDate')?.value || null;
            const to = document.getElementById('toDate')?.value || null;
            const q = new URLSearchParams();
            if (from) q.set('from', from);
            if (to) q.set('to', to);

            // fetch in parallel using safeFetchJson
            const [
                summaryJson,
                trendJson,
                categoriesJson,
                topJson,
                paymentJson,
                hourlyJson,
                salesDataJson
            ] = await Promise.all([
                safeFetchJson(routes.summary + (q.toString() ? ('?' + q.toString()) : '')),
                safeFetchJson(routes.trend + '?period=monthly' + (q.toString() ? '&' + q.toString() : '')),
                safeFetchJson(routes.categories + (q.toString() ? ('?' + q.toString()) : '')),
                safeFetchJson(routes.topProducts + '?limit=10' + (q.toString() ? '&' + q.toString() : '')),
                safeFetchJson(routes.paymentMethods + (q.toString() ? ('?' + q.toString()) : '')),
                safeFetchJson(routes.hourly + (q.toString() ? ('?' + q.toString()) : '')),
                safeFetchJson(routes.salesData + `?page=${currentPage}&per_page=${recordsPerPage}` + (q.toString() ? '&' + q.toString() : ''))
            ]);

            if (summaryJson) {
                // some APIs wrap summary in { success:true, summary: {...} }
                const payload = summaryJson.summary ?? summaryJson;
                setSummary(payload);
            } else {
                console.warn('Summary API returned null or 404. Check route:', routes.summary);
            }

            if (trendJson) {
                updateTrendChartDataFromApi(trendJson.trend ?? trendJson);
            }

            if (categoriesJson) {
                setCategoryChart(categoriesJson.categories ?? (categoriesJson.data ?? categoriesJson));
            }

            if (topJson) {
                setTopProducts(topJson.products ?? topJson.data ?? topJson);
            }

            if (paymentJson) {
                setPaymentMethods(paymentJson.payment_methods ?? paymentJson);
            }

            if (hourlyJson) {
                setHourlyChart(hourlyJson.hourly_pattern ?? hourlyJson);
            }

            if (salesDataJson) {
                setSalesTable(salesDataJson.data ?? salesDataJson);
            }
        } catch (err) {
            console.error('Error loading report data', err);
        }
    }

    // ---------- setSummary ----------
    function setSummary(summary) {
        const s = summary || {};
        // helper to set text if element exists
        function safeSet(id, text) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = text;
        }

        // Accept multiple shapes
        const totalRevenue = s.total_revenue ?? s.totalRevenue ?? s.summary?.total_revenue ?? 0;
        const totalOrders = s.total_orders ?? s.totalOrders ?? s.summary?.total_orders ?? 0;
        const averageOrder = s.average_order ?? s.averageOrder ?? s.summary?.average_order ?? 0;
        const customers = s.total_customers ?? s.totalCustomers ?? s.summary?.total_customers ?? 0;
        const growth = s.growth?.revenue ?? s.summary?.growth?.revenue ?? null;

        safeSet('totalRevenue', totalRevenue ? `₱${Number(totalRevenue).toLocaleString()}` : '₱0.00');
        safeSet('totalOrders', totalOrders ?? '0');
        safeSet('averageOrder', averageOrder ? `₱${Number(averageOrder).toFixed(2)}` : '₱0.00');
        safeSet('totalCustomers', customers ?? '0');
        if (document.getElementById('revenueGrowth')) {
            document.getElementById('revenueGrowth').innerHTML = growth ? `<i class="fas fa-arrow-up"></i> +${growth}%` : '—';
        }
    }

    // Date range logic removed

    // improved loader with graceful handling
    async function safeFetchJson(url) {
        try {
            const res = await fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' }});
            if (!res.ok) {
                console.warn('API fetch not OK', url, res.status);
                return null;
            }
            // try parse json
            const json = await res.json().catch(e => { console.warn('Invalid JSON from', url); return null; });
            return json;
        } catch (err) {
            console.error('Fetch error', url, err);
            return null;
        }
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
        let labels = [], data = [], colors = [];
        if (Array.isArray(categories)) {
            labels = categories.map(c => c.name ?? c.label);
            data = categories.map(c => c.count ?? c.sales ?? c.value ?? 0);
            colors = categories.map(c => c.color ?? '#' + Math.floor(Math.random()*16777215).toString(16));
        } else {
            labels = categories.labels ?? [];
            data = categories.data ?? [];
            colors = categories.colors ?? [];
        }
        if (charts.category) {
            charts.category.data.labels = labels;
            charts.category.data.datasets[0].data = data;
            charts.category.data.datasets[0].backgroundColor = colors;
            charts.category.update();
        }
        // update legend
        const legendEl = document.getElementById('categoryLegend');
        legendEl.innerHTML = labels.map((label, i) => `
            <div class="category-legend-item d-flex align-items-center mb-1">
                <div class="legend-color me-2" style="width:16px;height:16px;border-radius:3px;background-color: ${colors[i] ?? '#ccc'}"></div>
                <span class="legend-text me-2">${label}</span>
                <span class="legend-value text-muted">${data[i] ?? 0} products</span>
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
        // Accept payment_methods or paymentMethods, handle missing data gracefully
        const data = pm?.payment_methods ?? pm ?? {};
        const labels = Array.isArray(data.labels) ? data.labels : [];
        const values = Array.isArray(data.data) ? data.data : [];
        const colors = Array.isArray(data.colors) ? data.colors : [];
        // update chart only if chart exists
        if (charts.payment) {
            charts.payment.data.labels = labels;
            charts.payment.data.datasets[0].data = values;
            charts.payment.update();
        }
        // update list / stats
        const container = document.getElementById('paymentStats');
        if (!container) return;
        const total = (values || []).reduce((s, v) => s + (v || 0), 0) || 1;
        const totalRevenue = (document.getElementById('totalRevenue')?.textContent || '$0').replace(/[^0-9.-]+/g,"") * 1 || 0;
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
        if (!hourly) return; // Prevent error if hourly data is undefined
        const labels = hourly.labels ?? hourly.hourly_pattern?.labels ?? [];
        const data = hourly.data ?? hourly.orders ?? hourly.hourly_pattern?.orders ?? [];
        if (charts.hourly) {
            charts.hourly.data.labels = labels;
            charts.hourly.data.datasets[0].data = data;
            charts.hourly.update();
        }
    }

    // Add global exportTable to prevent ReferenceError
    window.exportTable = function() {
        // Get table rows
        const table = document.getElementById('salesTable');
        if (!table) return alert('Sales table not found!');
        let rows = Array.from(table.querySelectorAll('tr'));
        let data = rows.map(row => Array.from(row.querySelectorAll('th,td')).map(cell => cell.innerText));

        // Get export format (default to Excel)
        let format = 'excel';
        const formatSelect = document.getElementById('exportFormat');
        if (formatSelect) format = formatSelect.value;

        // Convert data to CSV
        function toCSV(data) {
            return data.map(row => row.map(cell => '"' + cell.replace(/"/g, '""') + '"').join(',')).join('\r\n');
        }

        // Convert data to HTML table for Excel
        function toExcel(data) {
            let html = '<table><thead><tr>' + data[0].map(cell => `<th>${cell}</th>`).join('') + '</tr></thead><tbody>';
            for (let i = 1; i < data.length; i++) {
                html += '<tr>' + data[i].map(cell => `<td>${cell}</td>`).join('') + '</tr>';
            }
            html += '</tbody></table>';
            return html;
        }

        // Download file helper
        function download(filename, content, mime) {
            const blob = new Blob([content], { type: mime });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => { document.body.removeChild(a); URL.revokeObjectURL(url); }, 100);
        }

        if (format === 'csv') {
            download('sales-table.csv', toCSV(data), 'text/csv');
        } else if (format === 'pdf') {
            // Simple PDF export using window.print (for table only)
            const printWin = window.open('', '', 'width=900,height=600');
            printWin.document.write('<html><head><title>Sales Table PDF</title></head><body>' + toExcel(data) + '</body></html>');
            printWin.document.close();
            printWin.focus();
            printWin.print();
        } else {
            // Excel export (as HTML table)
            const excelHtml = `\uFEFF<html><head><meta charset='UTF-8'></head><body>${toExcel(data)}</body></html>`;
            download('sales-table.xls', excelHtml, 'application/vnd.ms-excel');
        }
    };
    window.toggleViewMode = function() { /* No-op for toggleViewMode */ };

    function setSalesTable(salesPayload) {
        const rows = Array.isArray(salesPayload) ? salesPayload : (salesPayload.data ?? salesPayload);
        const tbody = document.getElementById('salesTableBody');
        tbody.innerHTML = (rows || []).map(sale => {
            const payment = sale.payment_mode ?? sale.payment_method ?? sale.paymentMethod ?? 'Unknown';
            let itemsArr = Array.isArray(sale.items) ? sale.items : [];
            const itemsText = itemsArr.length
                ? itemsArr.map(it => `${it.name} (${it.qty}x ₱${Number(it.price).toFixed(2)})`).join(', ')
                : '';
            const date = sale.date ?? sale.created_at ?? '';
            // Inline style for green badge if status is complete/completed
            const statusText = (sale.status ?? '').toLowerCase();
            const isComplete = statusText.includes('complete');
            const statusStyle = isComplete ? 'background:#28a745;color:#fff;border:none;font-weight:bold;' : '';
            return `<tr>
                <td><strong>${sale.order_id ?? sale.order_number ?? ('ORD-' + (sale.id || ''))}</strong></td>
                <td>${sale.customer ?? sale.customer_name ?? ''}</td>
                <td><div style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${itemsText}">${itemsText}</div></td>
                <td>${payment}</td>
                <td><strong>₱${Number(sale.amount ?? sale.total ?? 0).toFixed(2)}</strong></td>
                <td><span class="badge status-badge status-${statusText}" style="${statusStyle}">${(sale.status ?? 'N/A').toUpperCase()}</span></td>
                <td>${new Date(date).toLocaleString()}</td>
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

    
    // ---------- Chart initialization (replace existing initializeCharts() and updateTrendChart()) ----------
    function initializeCharts() {
        // Helper to ensure Chart.js is loaded then create charts
        function ensureChartAndCreate(fn) {
            if (typeof Chart !== 'undefined') {
                try { fn(); } catch (e) { console.error('Chart create error', e); }
                return;
            }
            // load Chart.js (specific version used on Dashboard)
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
            s.onload = function () {
                try { fn(); } catch (e) { console.error('Chart create error', e); }
            };
            s.onerror = function (e) { console.error('Failed to load Chart.js', e); };
            document.head.appendChild(s);
        }

        // create salesTrend (line) chart
        ensureChartAndCreate(function () {
            const salesTrendCanvas = document.getElementById('salesTrendChart');
            if (!salesTrendCanvas) return;
            const ctx = salesTrendCanvas.getContext('2d');
            if (charts.salesTrend && typeof charts.salesTrend.destroy === 'function') {
                charts.salesTrend.destroy();
                charts.salesTrend = null;
            }
            charts.salesTrend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [], // will be filled by API
                    datasets: [{
                        label: 'Sales',
                        data: [],
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        backgroundColor: function(context) {
                            // subtle gradient if supported
                            try {
                                const c = context.chart.ctx;
                                const g = c.createLinearGradient(0, 0, 0, 200);
                                g.addColorStop(0, 'rgba(23,125,255,0.18)');
                                g.addColorStop(1, 'rgba(23,125,255,0.02)');
                                return g;
                            } catch (e) { return 'rgba(23,125,255,0.2)'; }
                        },
                        borderColor: 'rgba(23,125,255,0.95)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: true } },
                    scales: { y: { beginAtZero: true, ticks: { callback: v => '₱' + Number(v).toLocaleString() } } }
                }
            });

            // create other charts that rely on Chart.js (if elements exist)
            // Category (doughnut)
            const catEl = document.getElementById('categoryChart');
            if (catEl) {
                const ctx2 = catEl.getContext('2d');
                if (charts.category && typeof charts.category.destroy === 'function') charts.category.destroy();
                charts.category = new Chart(ctx2, {
                    type: 'doughnut',
                    data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },
                    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'right'}} }
                });
            }

            // Payment (pie) - optional, only if canvas present
            const payEl = document.getElementById('paymentMethodChart');
            if (payEl) {
                const ctx3 = payEl.getContext('2d');
                if (charts.payment && typeof charts.payment.destroy === 'function') charts.payment.destroy();
                charts.payment = new Chart(ctx3, {
                    type: 'pie',
                    data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },
                    options: { responsive:true, maintainAspectRatio:false }
                });
            }

            // Hourly (bar)
            const hourlyEl = document.getElementById('hourlyChart');
            if (hourlyEl) {
                const ctx4 = hourlyEl.getContext('2d');
                if (charts.hourly && typeof charts.hourly.destroy === 'function') charts.hourly.destroy();
                charts.hourly = new Chart(ctx4, {
                    type: 'bar',
                    data: { labels: [], datasets: [{ label: 'Orders', data: [] }] },
                    options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
                });
            }
        });
    }

    /**
     * updateTrendChart(period)
     * - period: 'daily' | 'weekly' | 'monthly'
     * This will fetch trend data and update/create the salesTrend chart.
     */
    function updateTrendChart(period) {
        // buttons toggle (safe select)
        try {
            document.querySelectorAll('[id$="TrendBtn"]').forEach(btn => btn.classList.remove('active'));
            const btnEl = document.getElementById(period + 'TrendBtn');
            if (btnEl) btnEl.classList.add('active');
        } catch (e) {}

        // fetch trend from server (existing route variable usage)
        fetch(routes.trend + `?period=${period}`, { credentials: 'same-origin', headers: { Accept: 'application/json' }})
            .then(r => {
                if (!r.ok) throw new Error('Trend API HTTP ' + r.status);
                return r.json();
            })
            .then(json => {
                // try multiple shapes: { trend: { labels:[], data:[] } } or direct { labels:[], data:[] }
                const trend = json.trend ?? json;
                const labels = trend.labels ?? (trend.x ?? []);
                const data = trend.data ?? (trend.values ?? trend.y ?? []);
                // ensure chart exists, else create
                if (!charts.salesTrend) {
                    initializeCharts();
                    // Wait a tick for chart to be created then update (safe fallback)
                    setTimeout(() => {
                        if (charts.salesTrend) {
                            charts.salesTrend.data.labels = labels || [];
                            charts.salesTrend.data.datasets[0].data = data || [];
                            charts.salesTrend.update();
                        }
                    }, 250);
                    return;
                }
                charts.salesTrend.data.labels = labels || [];
                charts.salesTrend.data.datasets[0].data = data || [];
                charts.salesTrend.update();
            })
            .catch(err => {
                console.error('updateTrendChart error', err);
            });
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

    async function exportReport() {
        const format = document.getElementById('exportFormat').value;
        const includeSummary = document.getElementById('includeSummary').checked;
        const includeCharts = document.getElementById('includeCharts').checked;
        const includeDetails = document.getElementById('includeDetails').checked;
        const emailTo = document.getElementById('emailTo').value;

        // Build request payload
        async function loadAllData() {
            try {
                // fetch in parallel using safeFetchJson (no date filters)
                const [
                    summaryJson,
                    trendJson,
                    categoriesJson,
                    topJson,
                    paymentJson,
                    hourlyJson,
                    salesDataJson
                ] = await Promise.all([
                    safeFetchJson(routes.summary),
                    safeFetchJson(routes.trend + '?period=monthly'),
                    safeFetchJson(routes.categories),
                    safeFetchJson(routes.topProducts + '?limit=10'),
                    safeFetchJson(routes.paymentMethods),
                    safeFetchJson(routes.hourly),
                    safeFetchJson(routes.salesData + `?page=${currentPage}&per_page=${recordsPerPage}`)
                ]);

                if (summaryJson) {
                    const payload = summaryJson.summary ?? summaryJson;
                    setSummary(payload);
                } else {
                    console.warn('Summary API returned null or 404. Check route:', routes.summary);
                }

                if (trendJson) {
                    updateTrendChartDataFromApi(trendJson.trend ?? trendJson);
                }

                if (categoriesJson) {
                    setCategoryChart(categoriesJson.categories ?? (categoriesJson.data ?? categoriesJson));
                }

                if (topJson) {
                    setTopProducts(topJson.products ?? topJson.data ?? topJson);
                }

                if (paymentJson) {
                    setPaymentMethods(paymentJson.payment_methods ?? paymentJson);
                }

                if (hourlyJson) {
                    setHourlyChart(hourlyJson.hourly_pattern ?? hourlyJson);
                }

                if (salesDataJson) {
                    setSalesTable(salesDataJson.data ?? salesDataJson);
                }
            } catch (err) {
                console.error('Error loading report data', err);
            }
        }
        fetch("{{ url('sales/orders') }}/" + orderId + "/details")
            .then(r => r.json())
            .then(d => alert(JSON.stringify(d.order, null, 2)));
    };
    window.printReceipt = function(orderId) { fetch("{{ url('sales') }}/orders/" + orderId + "/print", { method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')} }).then(r=>r.json()).then(j=>alert(j.message)); };
});
</script>
<!-- Removed legacy script block referencing /sales/api/summary and /sales/api/top-products endpoints -->
@endpush
