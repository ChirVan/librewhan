@extends('layouts.app')
@section('title', 'Stock Levels')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Stock Levels</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                <li class="separator"><i class="fas fa-angle-right"></i></li>
                <li class="nav-item">Stock</li>
            </ul>
        </div>

        <div class="row mb-4" id="stock-stats-row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-box"></i></div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Total Products</p>
                            <h4 class="card-title mb-0">{{ $totalProducts }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Low Stock</p>
                            <h4 class="card-title mb-0">{{ $lowStockCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small"><i class="fas fa-times-circle"></i></div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Out of Stock</p>
                            <h4 class="card-title mb-0">{{ $outOfStockCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-layer-group"></i></div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Stock Value</p>
                            <h4 class="card-title mb-0">₱{{ number_format($totalStockValue, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Stock List</h4>
                    <div>
                        <a href="{{ route('inventory.stock.alerts') }}" class="btn btn-warning btn-sm">Stock Alerts</a>
                        <a href="{{ route('inventory.stock.history') }}" class="btn btn-outline-secondary btn-sm">Stock History</a>
                    </div>
                </div>
                <div class="card-body py-3">
                    <form method="GET" class="row g-3 align-items-end mb-3">
                        <div class="col-md-3">
                            <label class="form-label small">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name or SKU">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Category</label>
                            <select name="category_id" class="form-control form-control-sm">
                                <option value="all">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @if(request('category_id') == $cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="all">All</option>
                                <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                                <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Sort</label>
                            <select name="sort" class="form-control form-control-sm">
                                <option value="name" @if(request('sort')=='name') selected @endif>Name A-Z</option>
                                <option value="name-desc" @if(request('sort')=='name-desc') selected @endif>Name Z-A</option>
                                <option value="stock-desc" @if(request('sort')=='stock-desc') selected @endif>Stock High-Low</option>
                                <option value="stock" @if(request('sort')=='stock') selected @endif>Stock Low-High</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-primary btn-sm w-100" type="submit"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('inventory.stock') }}" class="btn btn-secondary btn-sm w-100"><i class="fas fa-sync"></i></a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Min Stock</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ $product->current_stock }}</td>
                                    <td>{{ $product->low_stock_alert }}</td>
                                    <td>₱{{ number_format($product->base_price, 2) }}</td>
                                    <td>
                                        @if($product->current_stock == 0)
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->current_stock <= $product->low_stock_alert)
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        @else
                                            <span class="badge bg-success">Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateStockModal{{ $product->id }}">Update</button>
                                        <!-- Update Modal -->
                                        <div class="modal fade" id="updateStockModal{{ $product->id }}" tabindex="-1" aria-labelledby="updateStockModalLabel{{ $product->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('inventory.stock.update', $product->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="updateStockModalLabel{{ $product->id }}">Update Stock for {{ $product->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="stock_quantity{{ $product->id }}" class="form-label">Quantity</label>
                                                                <input type="number" class="form-control" name="stock_quantity" id="stock_quantity{{ $product->id }}" min="0" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="adjustment_type{{ $product->id }}" class="form-label">Adjustment Type</label>
                                                                <select class="form-select" name="adjustment_type" id="adjustment_type{{ $product->id }}" required>
                                                                    <option value="add">Add</option>
                                                                    <option value="subtract">Subtract</option>
                                                                    <option value="set">Set Exact</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="reason{{ $product->id }}" class="form-label">Reason</label>
                                                                <input type="text" class="form-control" name="reason" id="reason{{ $product->id }}" maxlength="255">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update Stock</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No products found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{-- Custom pagination to avoid SVG chevrons --}}
                        @if ($products->hasPages())
                            <nav>
                                <ul class="pagination justify-content-center">
                                    {{-- Previous Page Link --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">&lt; Prev</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">&lt; Prev</a></li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($products->links()->elements[0] as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($products->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">Next &gt;</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Next &gt;</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
