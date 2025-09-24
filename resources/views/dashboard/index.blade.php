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
                  <p class="card-category">Pending Order</p>
                  <h4 class="card-title text-white">3</h4>
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
                  <h4 class="card-title text-white">₱ 1,620</h4>
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
                  <h4 class="card-title text-white">4</h4>
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
                  <h4 class="card-title text-white">₱ 14,010</h4>
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
                <a href="#" class="btn btn-primary btn-sm">View All Orders</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Items</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>ORD-001</td>
                    <td>Rintaro</td>
                    <td>Matcha</td>
                    <td>₱ 79</td>
                  </tr>
                  <tr>
                    <td>ORD-002</td>
                    <td>Subaru</td>
                    <td>Wintermelon</td>
                    <td>₱ 69</td>
                  </tr>
                </tbody>
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
                <a href="#" class="btn btn-danger btn-sm">View Stocks</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Prod ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>PROD-001</td>
                    <td>Soya</td>
                    <td>10</td>
                  </tr>
                  <tr>
                    <td>PROD-002</td>
                    <td>Matcha</td>
                    <td>7</td>
                  </tr>
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
                    2025
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">2024</a></li>
                    <li><a class="dropdown-item" href="#">2025</a></li>
                  </ul>
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


@push('scripts')
<script>
(function () {
  // guard: only run if the element exists
  var canvas = document.getElementById('monthlySalesChart');
  if (!canvas) return;

  function initChart() {
    try {
      var ctx3 = canvas.getContext('2d');
      if (typeof Chart === 'undefined') {
        console.warn('Chart.js not found. Monthly sales chart initialization skipped.');
        return;
      }

      var monthlySalesChart = new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
          datasets: [{
            label: 'Monthly Sales (₱)',
            data: [15420,18250,22100,19850,25400,28750,31200,29800,33500,28900,31800,35200],
            backgroundColor: 'rgba(23, 125, 255, 0.8)',
            borderColor: '#177dff',
            borderWidth: 1,
            borderRadius: 4,
            borderSkipped: false,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return '₱' + value.toLocaleString();
                }
              }
            }
          }
        }
      });
    } catch (err) {
      console.error('Error initializing Monthly Sales Chart:', err);
    }
  }

  // If Chart is already loaded, init immediately; otherwise dynamically load Chart.js then init
  if (typeof Chart !== 'undefined') {
    initChart();
  } else {
    // dynamic load (CDN). This is safe and non-blocking.
    var script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
    script.onload = initChart;
    script.onerror = function () {
      console.error('Failed to load Chart.js from CDN.');
    };
    document.head.appendChild(script);
  }
})();
</script>
@endpush

@endsection
