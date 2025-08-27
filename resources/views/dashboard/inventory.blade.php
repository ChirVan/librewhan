@extends('layouts.app')

@section('title', 'Inventory Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-boxes text-primary me-2"></i>
                        Inventory Dashboard
                    </h1>
                    <p class="text-muted mb-0">Stock and Product Management System</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('inventory.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Add Product
                    </a>
                    <a href="{{ route('inventory.categories.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-tags me-1"></i>
                        Add Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Products
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,247</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                In Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,089</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Low Stock Alert
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">23</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Out of Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">135</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Low Stock Items -->
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
                            <a href="{{ route('inventory.products.index') }}" class="btn btn-primary w-100 text-center py-3">
                                <i class="fas fa-box fa-2x d-block mb-2"></i>
                                <small>Manage Products</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('inventory.categories.index') }}" class="btn btn-info w-100 text-center py-3">
                                <i class="fas fa-tags fa-2x d-block mb-2"></i>
                                <small>Categories</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('inventory.stock.index') }}" class="btn btn-warning w-100 text-center py-3">
                                <i class="fas fa-warehouse fa-2x d-block mb-2"></i>
                                <small>Stock Control</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <button class="btn btn-success w-100 text-center py-3">
                                <i class="fas fa-file-export fa-2x d-block mb-2"></i>
                                <small>Export Data</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Low Stock Alert
                    </h6>
                    <a href="{{ route('inventory.stock.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Min Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Wireless Headphones</td>
                                    <td>Electronics</td>
                                    <td>3</td>
                                    <td>10</td>
                                    <td><span class="badge badge-warning">Low Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Coffee Beans Premium</td>
                                    <td>Food & Beverage</td>
                                    <td>5</td>
                                    <td>15</td>
                                    <td><span class="badge badge-warning">Low Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Smartphone Case</td>
                                    <td>Accessories</td>
                                    <td>0</td>
                                    <td>20</td>
                                    <td><span class="badge badge-danger">Out of Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gaming Mouse</td>
                                    <td>Electronics</td>
                                    <td>2</td>
                                    <td>8</td>
                                    <td><span class="badge badge-warning">Low Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement Chart -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Stock Movement Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>
                        Recent Activities
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Stock Added</h6>
                                    <small class="text-muted">50 units of Laptop Charger</small>
                                </div>
                            </div>
                            <small class="text-muted">2 min ago</small>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">New Product</h6>
                                    <small class="text-muted">Added Bluetooth Speaker</small>
                                </div>
                            </div>
                            <small class="text-muted">15 min ago</small>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-exclamation text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Low Stock Alert</h6>
                                    <small class="text-muted">Wireless Mouse below threshold</small>
                                </div>
                            </div>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Product Updated</h6>
                                    <small class="text-muted">Price changed for Coffee Beans</small>
                                </div>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample chart for inventory stock movement
const ctx = document.getElementById('stockChart').getContext('2d');
const stockChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Stock In',
            data: [120, 85, 150, 200, 95, 160, 110],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Stock Out',
            data: [80, 110, 95, 130, 120, 140, 90],
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection
