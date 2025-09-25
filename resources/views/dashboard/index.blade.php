@extends('layouts.app')

@section('title', 'Dashboard - Cafe Management')

@section('content')
<div class="container">
  <div class="page-inner">

    <div class="row">
      <!-- Pending Order Card -->
      <div class="col-6 col-md-3">
        <div class="card card-stats card-round bg-dark">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-warning bubble-shadow-small">
                  <i class="fas fa-clock"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Pending Orders</p>
                  <h4 class="card-title text-white" id="statPending">—</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Today Sales Card -->
      <div class="col-6 col-md-3">
        <div class="card card-stats card-round bg-dark">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                  <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-chart-line"></i>
                  </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">  
                <div class="numbers">
                  <p class="card-category">Today Sales</p>
                  <h4 class="card-title text-white" id="statTodaySales">—</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Low Stock Card -->
      <div class="col-6 col-md-3">
        <div class="card card-stats card-round bg-dark">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                  <div class="icon-big text-center icon-danger bubble-shadow-small">
                    <i class="fas fa-box-open"></i>
                  </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Low Stocks</p>
                  <h4 class="card-title text-white" id="statLowStocks">—</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Weekly Revenue Card -->
      <div class="col-6 col-md-3">
        <div class="card card-stats card-round bg-dark">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                  <div class="icon-big text-center icon-success bubble-shadow-small">
                    <i class="fas fa-coins"></i>
                  </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Weekly Sales</p>
                  <h4 class="card-title text-white" id="statWeeklySales">—</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Recent Orders</div>
              <div class="card-tools">
                <a href="{{ route('sales.report') }}" class="btn btn-primary btn-sm">View All Orders</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="recentOrdersTable">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Items</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody id="recentOrdersBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Low Stocks Products -->
      <div class="col-md-6">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Low Stocks Products</div>
              <div class="card-tools">
                <a href="{{ route('inventory.stocks.index') }}" class="btn btn-danger btn-sm">View Stocks</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="lowStockTable">
                <thead>
                  <tr>
                    <th>Prod ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                  </tr>
                </thead>
                <tbody id="lowStockBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Monthly Sales Chart Section -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Monthly Sales Overview</div>
              <div class="card-tools">
                <div class="dropdown">
                  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    {{ now()->year }}
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="min-height: 375px">
              <canvas id="monthlySalesChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const statsRoute = "{{ route('sales.dashboardStats') }}";
  const recentOrdersRoute = "{{ route('sales.report.salesData') }}"; // reusing salesData endpoint for listing

  function setText(id, val) { const el = document.getElementById(id); if (el) el.textContent = val; }

  async function loadStats() {
    try {
      const res = await fetch(statsRoute);
      const json = await res.json();
      const stats = json.stats ?? {};
      setText('statPending', stats.pending_orders ?? stats.pendingOrders ?? 0);
      setText('statTodaySales', stats.today_sales ? '₱ ' + Number(stats.today_sales).toLocaleString() : '₱ 0');
      setText('statLowStocks', stats.low_stock_count ?? stats.lowStockCount ?? 0);
      setText('statWeeklySales', stats.monthly_sales ? '₱ ' + Number(stats.monthly_sales).toLocaleString() : '₱ 0');

      // Fill recent orders (if API provides)
      loadRecentOrders();
      initMonthlyChart(stats.monthly_series ?? null);
    } catch (err) {
      console.error('Failed to load dashboard stats', err);
    }
  }

  async function loadRecentOrders() {
    try {
      const res = await fetch(recentOrdersRoute + '?per_page=5');
      const json = await res.json();
      const rows = json.data ?? json;
      const tbody = document.getElementById('recentOrdersBody');
      tbody.innerHTML = (rows || []).slice(0,5).map(r => `<tr>
        <td>${r.order_id ?? r.order_number ?? 'ORD-'+(r.id||'')}</td>
        <td>${r.customer ?? r.customer_name ?? ''}</td>
        <td>${(r.items || r.items_list || r.items_text) || ''}</td>
        <td>₱ ${Number(r.amount ?? r.total ?? 0).toLocaleString()}</td>
      </tr>`).join('');
    } catch (err) {
      console.warn('No recent orders or failed to fetch', err);
    }
  }

  async function loadLowStockProducts() {
    // quick attempt: fetch inventory.stock endpoint if exists (fallback to empty)
    try {
      const res = await fetch("{{ route('inventory.stocks.index') }}");
      // if HTML page returned, skip
      if (res.headers.get('content-type')?.includes('application/json')) {
        const json = await res.json();
        const rows = json.data ?? json;
        const body = document.getElementById('lowStockBody');
        body.innerHTML = (rows || []).slice(0,5).map(p => `<tr><td>${p.sku ?? p.id}</td><td>${p.name}</td><td>${p.current_stock}</td></tr>`).join('');
      } else {
        // skip when page returned
      }
    } catch (err) {
      // ignore
    }
  }

  function initMonthlyChart(series) {
  var canvas = document.getElementById('monthlySalesChart');
  if (!canvas) return;

  // destroy previous chart if present
  if (window.monthlySalesChart && typeof window.monthlySalesChart.destroy === 'function') {
    window.monthlySalesChart.destroy();
    window.monthlySalesChart = null;
  }

  function createChart(dataSeries) {
    try {
      var ctx = canvas.getContext('2d');
      if (typeof Chart === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
        script.onload = function () { createChart(dataSeries); };
        document.head.appendChild(script);
        return;
      }

      const data = dataSeries ?? [15420,18250,22100,19850,25400,28750,31200,29800,33500,28900,31800,35200];
      // Use a solid visible color (works on both light/dark backgrounds)
      const barColor = 'rgba(23,125,255,0.95)';
      const border = 'rgba(8,63,130,1)';

      window.monthlySalesChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
          datasets: [{
            label: 'Monthly Sales (₱)',
            data: data,
            backgroundColor: Array(12).fill(barColor),
            borderColor: Array(12).fill(border),
            borderWidth: 1,
            borderRadius: 6,
            borderSkipped: false,
            barThickness: 'flex'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) { return '₱' + value.toLocaleString(); }
              }
            }
          }
        }
      });
    } catch (err) {
      console.error('Chart init error', err);
    }
  }

  createChart(series);
}


  // run
  loadStats();
  loadLowStockProducts();

})();
</script>
@endpush
