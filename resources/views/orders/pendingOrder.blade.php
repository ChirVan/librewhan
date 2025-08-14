@extends('layouts.app')

@section('title', 'Pending Orders - Kalibrewhan Cafe')

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
                                    <h4 class="card-title" id="pending-count">8</h4>
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
                                    <h4 class="card-title" id="preparing-count">5</h4>
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
                                    <h4 class="card-title" id="ready-count">3</h4>
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
                                    <h4 class="card-title" id="total-value">$284.50</h4>
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
            <!-- Orders will be loaded here -->
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

<style>
.order-card {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 20px;
}

.order-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.order-card.status-pending {
    border-left: 5px solid #ffc107;
}

.order-card.status-preparing {
    border-left: 5px solid #17a2b8;
}

.order-card.status-ready {
    border-left: 5px solid #28a745;
}

.order-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
    padding: 12px 15px;
}

.order-body {
    padding: 15px;
}

.order-footer {
    background: #f8f9fa;
    border-top: 1px solid #e3e6f0;
    padding: 10px 15px;
}

.status-badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-preparing {
    background: #d1ecf1;
    color: #0c5460;
}

.status-ready {
    background: #d4edda;
    color: #155724;
}

.order-time {
    font-size: 0.8rem;
    color: #6c757d;
}

.item-list {
    max-height: 120px;
    overflow-y: auto;
}

.item-customization {
    font-size: 0.75rem;
    color: #6c757d;
    font-style: italic;
}

.filter-btn.active {
    background-color: #1572e8;
    border-color: #1572e8;
    color: white;
}

.order-actions {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-action {
    font-size: 0.75rem;
    padding: 4px 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample orders data - replace with actual API calls
    let orders = [
        {
            id: 'ORD-001',
            customer_name: 'John Doe',
            order_type: 'dine-in',
            status: 'pending',
            created_at: '2024-01-15 10:30:00',
            total: 15.75,
            items: [
                {
                    name: 'Cappuccino',
                    quantity: 2,
                    price: 4.25,
                    customizations: {
                        size: 'large',
                        sugar: '50',
                        milk: 'almond',
                        toppings: ['extra-shot']
                    }
                },
                {
                    name: 'Croissant',
                    quantity: 1,
                    price: 2.95,
                    customizations: {}
                }
            ]
        },
        {
            id: 'ORD-002',
            customer_name: 'Jane Smith',
            order_type: 'takeaway',
            status: 'preparing',
            created_at: '2024-01-15 10:25:00',
            total: 12.50,
            items: [
                {
                    name: 'Latte',
                    quantity: 1,
                    price: 4.75,
                    customizations: {
                        size: 'medium',
                        sugar: '100',
                        milk: 'oat',
                        toppings: ['whipped-cream', 'vanilla-syrup']
                    }
                },
                {
                    name: 'Blueberry Muffin',
                    quantity: 1,
                    price: 3.45,
                    customizations: {}
                }
            ]
        },
        {
            id: 'ORD-003',
            customer_name: 'Mike Wilson',
            order_type: 'delivery',
            status: 'ready',
            created_at: '2024-01-15 10:20:00',
            total: 18.95,
            items: [
                {
                    name: 'Americano',
                    quantity: 2,
                    price: 3.25,
                    customizations: {
                        size: 'large',
                        sugar: 'no-sugar',
                        milk: 'regular',
                        toppings: []
                    }
                },
                {
                    name: 'Caesar Salad',
                    quantity: 1,
                    price: 8.95,
                    customizations: {}
                }
            ]
        }
    ];

    let currentFilter = 'all';
    let currentTypeFilter = 'all';

    // Initialize page
    loadOrders();
    updateStatistics();

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.status;
            loadOrders();
        });
    });

    // Order type filter
    document.getElementById('orderTypeFilter').addEventListener('change', function() {
        currentTypeFilter = this.value;
        loadOrders();
    });

    // Refresh button
    document.getElementById('refreshOrders').addEventListener('click', function() {
        loadOrders();
        updateStatistics();
        
        // Show refresh animation
        const icon = this.querySelector('i');
        icon.classList.add('fa-spin');
        setTimeout(() => icon.classList.remove('fa-spin'), 1000);
    });

    function loadOrders() {
        const container = document.getElementById('orders-container');
        const filteredOrders = orders.filter(order => {
            const statusMatch = currentFilter === 'all' || order.status === currentFilter;
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
        
        return `
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card order-card ${statusClass}">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">${orderTypeIcon} Order #${order.id}</h6>
                                <small class="text-muted">${order.customer_name}</small>
                            </div>
                            <div class="text-end">
                                ${statusBadge}
                                <div class="order-time">${timeAgo}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-body">
                        <div class="item-list">
                            ${order.items.map(item => `
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <span class="fw-bold">${item.quantity}x ${item.name}</span>
                                        ${formatCustomizations(item.customizations)}
                                    </div>
                                    <span class="text-muted">$${(item.price * item.quantity).toFixed(2)}</span>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Total:</strong>
                                <strong class="text-primary">$${order.total.toFixed(2)}</strong>
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
        const badges = {
            pending: '<span class="badge status-pending">Pending</span>',
            preparing: '<span class="badge status-preparing">Preparing</span>',
            ready: '<span class="badge status-ready">Ready</span>'
        };
        return badges[status] || '';
    }

    function getOrderTypeIcon(type) {
        const icons = {
            'dine-in': '<i class="fas fa-utensils text-success"></i>',
            'takeaway': '<i class="fas fa-shopping-bag text-warning"></i>',
            'delivery': '<i class="fas fa-motorcycle text-info"></i>'
        };
        return icons[type] || '<i class="fas fa-question"></i>';
    }

    function getStatusActions(order) {
        switch(order.status) {
            case 'pending':
                return `<button class="btn btn-warning btn-action" onclick="updateOrderStatus('${order.id}', 'preparing')">
                    <i class="fas fa-play"></i> Start Preparing
                </button>`;
            case 'preparing':
                return `<button class="btn btn-success btn-action" onclick="updateOrderStatus('${order.id}', 'ready')">
                    <i class="fas fa-check"></i> Mark Ready
                </button>`;
            case 'ready':
                return `<button class="btn btn-primary btn-action" onclick="completeOrder('${order.id}')">
                    <i class="fas fa-check-circle"></i> Complete Order
                </button>`;
            default:
                return '';
        }
    }

    function formatCustomizations(customizations) {
        if (!customizations || Object.keys(customizations).length === 0) return '';
        
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
        if (customizations.toppings && customizations.toppings.length > 0) {
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
        const totalValue = orders.reduce((sum, o) => sum + o.total, 0);

        document.getElementById('pending-count').textContent = pending;
        document.getElementById('preparing-count').textContent = preparing;
        document.getElementById('ready-count').textContent = ready;
        document.getElementById('total-value').textContent = `$${totalValue.toFixed(2)}`;
    }

    // Global functions for button actions
    window.viewOrderDetails = function(orderId) {
        const order = orders.find(o => o.id === orderId);
        if (!order) return;

        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Order Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Order ID:</strong></td><td>#${order.id}</td></tr>
                        <tr><td><strong>Customer:</strong></td><td>${order.customer_name}</td></tr>
                        <tr><td><strong>Type:</strong></td><td>${order.order_type.replace('-', ' ').toUpperCase()}</td></tr>
                        <tr><td><strong>Status:</strong></td><td>${getStatusBadge(order.status)}</td></tr>
                        <tr><td><strong>Order Time:</strong></td><td>${new Date(order.created_at).toLocaleString()}</td></tr>
                        <tr><td><strong>Total:</strong></td><td><strong>$${order.total.toFixed(2)}</strong></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Order Items</h6>
                    <div class="list-group">
                        ${order.items.map(item => `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span><strong>${item.quantity}x ${item.name}</strong></span>
                                    <span>$${(item.price * item.quantity).toFixed(2)}</span>
                                </div>
                                ${formatCustomizations(item.customizations)}
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('orderDetailsContent').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
    };

    window.updateOrderStatus = function(orderId, newStatus) {
        const order = orders.find(o => o.id === orderId);
        if (!order) return;

        document.getElementById('updateOrderId').textContent = orderId;
        document.getElementById('updateCustomerName').textContent = order.customer_name;
        document.getElementById('currentStatus').textContent = order.status.toUpperCase();
        document.getElementById('newStatus').textContent = newStatus.toUpperCase();

        const modal = new bootstrap.Modal(document.getElementById('statusUpdateModal'));
        modal.show();

        document.getElementById('confirmStatusUpdate').onclick = function() {
            // Update order status
            order.status = newStatus;
            
            // Reload orders and update statistics
            loadOrders();
            updateStatistics();
            
            // Close modal
            modal.hide();
            
            // Show success notification
            alert(`Order #${orderId} status updated successfully!`);
        };
    };

    window.completeOrder = function(orderId) {
        if (confirm('Are you sure you want to complete this order? This will remove it from pending orders.')) {
            // Remove order from pending list
            orders = orders.filter(o => o.id !== orderId);
            
            // Reload orders and update statistics
            loadOrders();
            updateStatistics();
            
            // Show success notification
            alert(`Order #${orderId} completed successfully!`);
        }
    };
});
</script>
@endsection