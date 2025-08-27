@extends('layouts.app')

@section('title', 'POS Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-cash-register text-success me-2"></i>
                        POS Dashboard
                    </h1>
                    <p class="text-muted mb-0">Point of Sale Management System</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>
                        New Sale
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Today's Sales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱15,420</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Transactions Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">42</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Terminal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">POS-01</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Sales -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('orders.take') }}" class="btn btn-success w-100 text-center py-3">
                                <i class="fas fa-shopping-cart fa-2x d-block mb-2"></i>
                                <small>New Sale</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('orders.pending') }}" class="btn btn-warning w-100 text-center py-3">
                                <i class="fas fa-clock fa-2x d-block mb-2"></i>
                                <small>Pending Orders</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <button class="btn btn-info w-100 text-center py-3">
                                <i class="fas fa-receipt fa-2x d-block mb-2"></i>
                                <small>Print Receipt</small>
                            </button>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('orders.history') }}" class="btn btn-secondary w-100 text-center py-3">
                                <i class="fas fa-history fa-2x d-block mb-2"></i>
                                <small>Sales History</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>
                        Recent Sales
                    </h6>
                    <a href="{{ route('orders.history') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-001</td>
                                    <td>Walk-in Customer</td>
                                    <td>3 items</td>
                                    <td>₱850.00</td>
                                    <td>2:30 PM</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD-002</td>
                                    <td>John Doe</td>
                                    <td>2 items</td>
                                    <td>₱620.00</td>
                                    <td>2:15 PM</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD-003</td>
                                    <td>Walk-in Customer</td>
                                    <td>1 item</td>
                                    <td>₱350.00</td>
                                    <td>1:45 PM</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD-004</td>
                                    <td>Jane Smith</td>
                                    <td>5 items</td>
                                    <td>₱1,200.00</td>
                                    <td>1:20 PM</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Today's Sales Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample chart for POS sales
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
        datasets: [{
            label: 'Sales Amount (₱)',
            data: [500, 800, 1200, 2000, 1800, 2500, 3200, 2800, 3500],
            borderColor: 'rgb(28, 200, 138)',
            backgroundColor: 'rgba(28, 200, 138, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value;
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
