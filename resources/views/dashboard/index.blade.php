@extends('layouts.app')

@section('title', 'Dashboard -' .config('app.name'))

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
                  <h4 class="card-title text-white">
                    {{ isset($lowStockProducts) ? count($lowStockProducts) : '—' }}
                  </h4>
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
                <tbody>
                  @forelse($lowStockProducts ?? [] as $product)
                    <tr>
                      <td>{{ $product->id }}</td>
                      <td>{{ $product->name }}</td>
                      <td>{{ $product->current_stock }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="3" class="text-center text-muted">No low stock products</td></tr>
                  @endforelse
                </tbody>
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

    @include('dashboard.sales')

  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const statsRoute = "/api/dashboard/stats"; // explicit API path

  /********** Chart helper (defined first, safe to call later) **********/
  function initMonthlyChart(series) {
    const canvas = document.getElementById('monthlySalesChart');
    if (!canvas) return;
    if (window.monthlySalesChart && typeof window.monthlySalesChart.destroy === 'function') {
      window.monthlySalesChart.destroy();
      window.monthlySalesChart = null;
    }

    function createChart(dataSeries) {
      try {
        const ctx = canvas.getContext('2d');
        if (typeof Chart === 'undefined') {
          const script = document.createElement('script');
          script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
          script.onload = function () { createChart(dataSeries); };
          document.head.appendChild(script);
          return;
        }

        const data = Array.isArray(dataSeries) ? dataSeries : Array.from({length:12}, () => 0);
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
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            scales: {
              y: {
                beginAtZero: true,
                ticks: { callback: function(value) { return '₱' + value.toLocaleString(); } }
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

  /********** Utility **********/
  function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  }

  /********** Fetch and render **********/
  (async function loadStatsAndRender() {
    try {
      const res = await fetch(statsRoute, { credentials: 'same-origin', headers: { Accept: 'application/json' }});
      console.log('Dashboard stats fetch', res.status, res.statusText, res.headers.get('content-type'));
      if (!res.ok) {
        const txt = await res.text().catch(()=>res.statusText);
        console.error('Dashboard stats HTTP error', res.status, txt);
        return;
      }
      const json = await res.json().catch(async e => { const t = await res.text(); throw new Error('Invalid JSON: ' + t); });
      console.log('dashboard.stats payload', json);

      const stats = json.stats ?? {};
      setText('statPending', stats.pending_orders ?? 0);
      setText('statTodaySales', stats.today_sales ? '₱ ' + Number(stats.today_sales).toLocaleString() : '₱ 0');
      // safe set for low-stock element which may be server-side rendered
      if (document.getElementById('statLowStocks')) setText('statLowStocks', stats.low_stock_count ?? 0);
      setText('statWeeklySales', stats.weekly_sales ? '₱ ' + Number(stats.weekly_sales).toLocaleString() : '₱ 0');

      // Recent orders (tbody id = recentOrdersBody)
      const rows = json.recent_orders ?? [];
      const tbody = document.getElementById('recentOrdersBody');
      if (tbody) {
        tbody.innerHTML = (rows || []).map(r => {
          const items = (r.items || []).map(it => it.name ?? '').join(', ');
          return `<tr>
            <td>${r.order_number ?? ('ORD-'+(r.id||''))}</td>
            <td>${r.customer_name ?? r.customer ?? ''}</td>
            <td>${items}</td>
            <td>₱ ${Number(r.total ?? r.amount ?? 0).toLocaleString()}</td>
          </tr>`;
        }).join('') || '<tr><td colspan="4" class="text-center text-muted">No recent orders</td></tr>';
      }

      // Monthly chart
      initMonthlyChart(json.monthly_series ?? null);

      // optionally render top/bottom sellers if present
      if (json.top_sellers && Array.isArray(json.top_sellers)) {
        // implement UI render here (or console.log for now)
        console.log('Top sellers:', json.top_sellers);
      }
      if (json.bottom_sellers && Array.isArray(json.bottom_sellers)) {
        console.log('Bottom sellers:', json.bottom_sellers);
      }

    } catch (err) {
      console.error('Failed loading dashboard stats', err);
    }
  })();

})();
</script>
@endpush