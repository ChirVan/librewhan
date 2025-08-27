@extends('layouts.app')
@section('title', 'Stock Alerts')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-0">Stock Alerts</h3>
                <p class="text-muted mb-0">Products that are low or out of stock.</p>
            </div>
            <a href="{{ route('inventory.stock') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to Stock Levels</a>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark fw-bold">Low Stock</div>
                    <div class="card-body">
                        @if($lowStockProducts->isEmpty())
                            <div class="text-muted">No products are low on stock.</div>
                        @else
                            <ul class="list-group">
                                @foreach($lowStockProducts as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $product->name }} <span class="badge bg-secondary ms-2">Min: {{ $product->low_stock_alert }}</span></span>
                                        <span class="badge bg-warning text-dark">{{ $product->current_stock }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white fw-bold">Out of Stock</div>
                    <div class="card-body">
                        @if($outOfStockProducts->isEmpty())
                            <div class="text-muted">No products are out of stock.</div>
                        @else
                            <ul class="list-group">
                                @foreach($outOfStockProducts as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $product->name }}</span>
                                        <span class="badge bg-danger">0</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
