@extends('layouts.app')

@section('title', 'Pending Orders - Librewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Pending Orders</h3>
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
                    <a href="#">Orders</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Pending Orders</a>
                </li>
            </ul>
        </div>

        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stats card-round">
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
                                    <h4 class="card-title" id="pending-count">{{ $pendingCount ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">In Preparation</p>
                                    <h4 class="card-title" id="preparing-count">{{ $preparingCount ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Ready</p>
                                    <h4 class="card-title" id="ready-count">{{ $readyCount ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Value</p>
                                    <h4 class="card-title" id="total-value">₱{{ number_format($totalValue ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm filter-btn active" data-status="all">
                                    All Orders
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm filter-btn" data-status="pending">
                                    Pending
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm filter-btn" data-status="preparing">
                                    Preparing
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-status="ready">
                                    Ready
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <select class="form-control form-control-sm" id="orderTypeFilter" style="width: 120px;">
                                    <option value="all">All Types</option>
                                    <option value="dine-in">Dine In</option>
                                    <option value="takeaway">Takeaway</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                                <button class="btn btn-success btn-sm" id="refreshOrders">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Grid -->
        <div class="row" id="orders-container">
            @forelse($orders as $order)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card order-card status-{{ $order->status }}">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    <i class="fas fa-utensils text-success"></i>
                                    Order #{{ $order->order_number }}
                                </h6>
                                <small class="text-muted">{{ $order->customer_name }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                <div class="order-time">{{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="item-list">
                            @foreach($order->items as $item)
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1">
                                    <span class="fw-bold">{{ $item->qty }}x {{ $item->name }}</span>
                                </div>
                                <span class="text-muted">₱{{ number_format($item->price * $item->qty, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Total:</strong>
                                <strong class="text-primary">₱{{ number_format($order->total, 2) }}</strong>
                            </div>
                        </div>
                        <div class="order-footer mt-2">
                            <div class="order-actions">
                                @if($order->status === 'pending')
                                <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="btn btn-warning btn-action">
                                        <i class="fas fa-play"></i> Start Preparing
                                    </button>
                                </form>
                                @elseif($order->status === 'preparing')
                                <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="btn btn-success btn-action">
                                        <i class="fas fa-check"></i> Mark Ready
                                    </button>
                                </form>
                                @elseif($order->status === 'ready')
                                <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-primary btn-action">
                                        <i class="fas fa-check-circle"></i> Complete Order
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pending orders found</h5>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update this order status?</p>
                <div class="alert alert-info">
                    <strong>Order #<span id="updateOrderId"></span></strong><br>
                    Customer: <span id="updateCustomerName"></span><br>
                    Current Status: <span id="currentStatus"></span><br>
                    New Status: <span id="newStatus"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Confirm Update</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .status-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    .status-pending {
        background: #fff3cd !important;
        color: #856404 !important;
        border: 1px solid #ffeeba !important;
    }
    .status-preparing {
        background: #d1ecf1 !important;
        color: #0c5460 !important;
        border: 1px solid #bee5eb !important;
    }
    .status-ready {
        background: #d4edda !important;
        color: #155724 !important;
        border: 1px solid #c3e6cb !important;
    }
    .order-card {
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(21,114,232,0.08);
        border: none;
        margin-bottom: 18px;
        background: #fff;
        transition: box-shadow 0.2s;
    }
    .order-card.status-pending {
        background: #fffbe6;
        border: 1.5px solid #ffeeba;
    }
    .order-card.status-preparing {
        background: #e8f7fa;
        border: 1.5px solid #bee5eb;
    }
    .order-card.status-ready {
        background: #eafbe7;
        border: 1.5px solid #c3e6cb;
    }
    .order-header {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 6px;
        margin-bottom: 8px;
    }
    .order-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .btn-action {
        font-size: 0.85rem;
        padding: 4px 12px;
        border-radius: 6px;
        box-shadow: 0 1px 4px rgba(21,114,232,0.04);
        border: none;
        transition: background 0.2s, color 0.2s;
        min-width: 90px;
        height: 36px;
        line-height: 1.2;
    }
    .btn-action.btn-warning {
        background: #ffc107;
        color: #212529;
    }
    .btn-action.btn-success {
        background: #28a745;
        color: #fff;
    }
    .btn-action.btn-primary {
        background: #1572e8;
        color: #fff;
    }
    .btn-action.btn-outline-primary {
        background: #fff;
        color: #1572e8;
        border: 1.5px solid #1572e8;
    }
    .btn-action:hover {
        opacity: 0.9;
    }
    .order-time {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 2px;
    }
    .order-body {
        padding-bottom: 8px;
    }
    .item-list {
        max-height: 120px;
        overflow-y: auto;
        margin-bottom: 8px;
    }
    .item-customization {
        font-size: 0.75rem;
        color: #6c757d;
        font-style: italic;
    }
    .order-footer {
        border-top: 1px solid #f0f0f0;
        padding-top: 8px;
        margin-top: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CRITICAL FIX: Declare orders variable from Blade data
    let orders = @json($orders ?? []);
    
    let currentFilter = 'all';
    let currentTypeFilter = 'all';

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.onclick = function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-status');
            loadOrders();
        };
    });

    // Order type filter
    document.getElementById('orderTypeFilter').addEventListener('change', function() {
        currentTypeFilter = this.value;
        loadOrders();
    });

    // Refresh button
    document.getElementById('refreshOrders').addEventListener('click', function() {
        // Show refresh animation
        const icon = this.querySelector('i');
        icon.classList.add('fa-spin');
        
        // Reload page to get fresh data from server
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });

    // Initialize page
    loadOrders();
    updateStatistics();

    function loadOrders() {
        const container = document.getElementById('orders-container');
        
        // Filter orders based on current filters
        const filteredOrders = orders.filter(order => {
            let statusMatch = true;
            if (currentFilter !== 'all') {
                statusMatch = order.status === currentFilter;
            }
            
            const typeMatch = currentTypeFilter === 'all' || order.order_type === currentTypeFilter;
            return statusMatch && typeMatch;
        });

        if (filteredOrders.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No orders found</h5>
                        <p class="text-muted">No orders match your current filters.</p>
                    </div>
                </div>
            `;
            return;
        }

        container.innerHTML = filteredOrders.map(order => createOrderCard(order)).join('');
    }

    function createOrderCard(order) {
        const statusClass = `status-${order.status}`;
        const statusBadge = getStatusBadge(order.status);
        const orderTypeIcon = getOrderTypeIcon(order.order_type);
        const timeAgo = getTimeAgo(order.created_at);
        const total = parseFloat(order.total) || 0;
        
        return `
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card order-card ${statusClass}">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">${orderTypeIcon} Order #${order.order_number || order.id}</h6>
                                <small class="text-muted">${order.customer_name || 'Guest'}</small>
                            </div>
                            <div class="text-end">
                                ${statusBadge}
                                <div class="order-time">${timeAgo}</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="item-list">
                            ${(order.items || []).map(item => {
                                const qty = parseInt(item.quantity || item.qty) || 0;
                                const price = parseFloat(item.price) || 0;
                                return `<div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <span class="fw-bold">${qty}x ${item.name}</span>
                                        ${formatCustomizations(item.customizations)}
                                    </div>
                                    <span class="text-muted">₱${(price * qty).toFixed(2)}</span>
                                </div>`;
                            }).join('')}
                        </div>
                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Total:</strong>
                                <strong class="text-primary">₱${total.toFixed(2)}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="order-footer">
                        <div class="order-actions">
                            <button class="btn btn-outline-primary btn-action" onclick="viewOrderDetails('${order.id}')">
                                <i class="fas fa-eye"></i> Details
                            </button>
                            ${getStatusActions(order)}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function getStatusBadge(status) {
        if (status === 'pending') {
            return '<span class="badge status-pending" style="background:#fff3cd;color:#856404;border:1px solid #ffeeba;">Pending</span>';
        } else if (status === 'preparing') {
            return '<span class="badge status-preparing" style="background:#d1ecf1;color:#0c5460;border:1px solid #bee5eb;">Preparing</span>';
        } else if (status === 'ready') {
            return '<span class="badge status-ready" style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;">Ready</span>';
        }
        return '';
    }

    function getOrderTypeIcon(type) {
        const icons = {
            'dine-in': '<i class="fas fa-utensils text-success"></i>',
            'takeaway': '<i class="fas fa-shopping-bag text-warning"></i>',
            'delivery': '<i class="fas fa-motorcycle text-info"></i>'
        };
        return icons[type] || '<i class="fas fa-utensils text-success"></i>';
    }

    function getStatusActions(order) {
        switch (order.status) {
            case 'pending':
                return `<form method="POST" action="/orders/${order.id}/status" style="display:inline;">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="btn btn-warning btn-action">
                        <i class="fas fa-play"></i> Start Preparing
                    </button>
                </form>`;
            case 'preparing':
                return `<form method="POST" action="/orders/${order.id}/status" style="display:inline;">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="status" value="ready">
                    <button type="submit" class="btn btn-success btn-action">
                        <i class="fas fa-check"></i> Mark Ready
                    </button>
                </form>`;
            case 'ready':
                return `<form method="POST" action="/orders/${order.id}/status" style="display:inline;">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-primary btn-action">
                        <i class="fas fa-check-circle"></i> Complete Order
                    </button>
                </form>`;
            default:
                return '';
        }
    }

    function formatCustomizations(customizations) {
        if (!customizations || typeof customizations !== 'object' || Object.keys(customizations).length === 0) {
            return '';
        }

        const parts = [];
        if (customizations.size && customizations.size !== 'small') {
            parts.push(`Size: ${customizations.size.charAt(0).toUpperCase() + customizations.size.slice(1)}`);
        }
        if (customizations.sugar && customizations.sugar !== '100') {
            parts.push(`Sugar: ${customizations.sugar === 'no-sugar' ? 'No Sugar' : customizations.sugar + '%'}`);
        }
        if (customizations.milk && customizations.milk !== 'regular') {
            parts.push(`${customizations.milk.charAt(0).toUpperCase() + customizations.milk.slice(1)} Milk`);
        }
        if (customizations.toppings && Array.isArray(customizations.toppings) && customizations.toppings.length > 0) {
            parts.push(`Add: ${customizations.toppings.join(', ').replace(/-/g, ' ')}`);
        }

        return parts.length > 0 ? `<div class="item-customization">${parts.join(' • ')}</div>` : '';
    }

    function getTimeAgo(datetime) {
        const now = new Date();
        const orderTime = new Date(datetime);
        const diffMs = now - orderTime;
        const diffMins = Math.floor(diffMs / 60000);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins}m ago`;

        const diffHours = Math.floor(diffMins / 60);
        if (diffHours < 24) return `${diffHours}h ${diffMins % 60}m ago`;

        return orderTime.toLocaleDateString();
    }

    function updateStatistics() {
        const pending = orders.filter(o => o.status === 'pending').length;
        const preparing = orders.filter(o => o.status === 'preparing').length;
        const ready = orders.filter(o => o.status === 'ready').length;
        const totalValue = orders.reduce((sum, o) => sum + (parseFloat(o.total) || 0), 0);

        document.getElementById('pending-count').textContent = pending;
        document.getElementById('preparing-count').textContent = preparing;
        document.getElementById('ready-count').textContent = ready;
        document.getElementById('total-value').textContent = `₱${totalValue.toFixed(2)}`;
    }

    // Global functions for button actions
    window.viewOrderDetails = function(orderId) {
        const order = orders.find(o => o.id == orderId);
        if (!order) return;

        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Order Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Order ID:</strong></td><td>#${order.order_number || order.id}</td></tr>
                        <tr><td><strong>Customer:</strong></td><td>${order.customer_name || 'Guest'}</td></tr>
                        <tr><td><strong>Type:</strong></td><td>${(order.order_type || '').replace('-', ' ').toUpperCase()}</td></tr>
                        <tr><td><strong>Status:</strong></td><td>${getStatusBadge(order.status)}</td></tr>
                        <tr><td><strong>Order Time:</strong></td><td>${new Date(order.created_at).toLocaleString()}</td></tr>
                        <tr><td><strong>Total:</strong></td><td><strong>₱${parseFloat(order.total).toFixed(2)}</strong></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Order Items</h6>
                    <div class="list-group">
                        ${(order.items || []).map(item => {
                            const qty = parseInt(item.quantity || item.qty) || 0;
                            const price = parseFloat(item.price) || 0;
                            return `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span><strong>${qty}x ${item.name}</strong></span>
                                        <span>₱${(price * qty).toFixed(2)}</span>
                                    </div>
                                    ${formatCustomizations(item.customizations)}
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            </div>
        `;

        document.getElementById('orderDetailsContent').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
    };

    window.updateOrderStatus = function(orderId, newStatus) {
        const order = orders.find(o => o.id == orderId);
        if (!order) return;

        document.getElementById('updateOrderId').textContent = order.order_number || orderId;
        document.getElementById('updateCustomerName').textContent = order.customer_name || 'Guest';
        document.getElementById('currentStatus').textContent = order.status.toUpperCase();
        document.getElementById('newStatus').textContent = newStatus.toUpperCase();

        const modal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
        modal.show();

        document.getElementById('confirmStatusUpdate').onclick = function() {
            // Update order status locally
            order.status = newStatus;

            // Reload orders and update statistics
            loadOrders();
            updateStatistics();

            // Reliable event delegation for status update buttons
            function setupStatusButtonDelegation() {
                const container = document.getElementById('orders-container');
                if (!container._delegationSetup) {
                    container.addEventListener('click', function(e) {
                        const btn = e.target.closest('.btn-action[data-order-id][data-new-status]');
                        if (btn) {
                            const orderId = btn.getAttribute('data-order-id');
                            const newStatus = btn.getAttribute('data-new-status');
                            // Update status locally and re-render
                            const order = orders.find(o => o.id == orderId);
                            if (order) {
                                order.status = newStatus;
                                loadOrders();
                                updateStatistics();
                            }
                        }
                    });
                    container._delegationSetup = true;
                }
            }

            // Call after every loadOrders()
            const originalLoadOrders = loadOrders;
            loadOrders = function() {
                originalLoadOrders();
                setupStatusButtonDelegation();
            };

            // Close modal
            modal.hide();

            // Show success notification
            alert(`Order #${order.order_number || orderId} status updated successfully!`);
            
            // Optionally reload page to sync with server
            setTimeout(() => window.location.reload(), 1000);
        };
    };

    window.updateOrderStatusAjax = function(orderId, newStatus) {
        // Show loading indicator
        const btn = event.target.closest('button');
        if (btn) {
            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            btn.dataset.originalHtml = originalHTML;
        }
        fetch(`/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(async response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Response data:', data);
            return data;
        })
        .then(data => {
            const isSuccess = data.success === true || 
                             data.status === 'success' || 
                             data.message ||
                             response.ok;
            if (isSuccess) {
                const order = orders.find(o => o.id == orderId);
                if (order) {
                    order.status = newStatus;
                }
                showNotification('success', 'Order status updated successfully!');
                loadOrders();
                updateStatistics();
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to update order status');
            }
        })
        .catch((error) => {
            console.error('Order status update error:', error);
            showNotification('error', 'Error: ' + error.message);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.originalHtml || 'Update Status';
            }
        });
    };

    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    window.updateOrderStatusAjaxDebug = function(orderId, newStatus) {
        fetch(`/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(res => res.text())
        .then(text => {
            console.log('Raw response:', text);
            try {
                const data = JSON.parse(text);
                console.log('Parsed JSON:', data);
                console.log('Keys in response:', Object.keys(data));
            } catch (e) {
                console.error('Failed to parse JSON:', e);
            }
        })
        .catch(err => console.error('Fetch error:', err));
    };

    window.completeOrder = function(orderId) {
        if (confirm('Are you sure you want to complete this order? This will remove it from pending orders.')) {
            // Remove order from pending list
            orders = orders.filter(o => o.id != orderId);

            // Reload orders and update statistics
            loadOrders();
            updateStatistics();

            // Show success notification
            alert(`Order completed successfully!`);
            
            // Reload page to sync with server
            setTimeout(() => window.location.reload(), 1000);
        }
    };
});
</script>
@endpush