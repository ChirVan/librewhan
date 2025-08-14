@extends('layouts.app')

@section('title', 'Order History - Kalibrewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Order History</h3>
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
                    <a href="#">Order History</a>
                </li>
            </ul>
        </div>

        <!-- Filters and Search -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Search Orders</label>
                                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Order ID, Customer name...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Date Range</label>
                                    <select class="form-control form-control-sm" id="dateFilter">
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="this-week" selected>This Week</option>
                                        <option value="last-week">Last Week</option>
                                        <option value="this-month">This Month</option>
                                        <option value="last-month">Last Month</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Order Type</label>
                                    <select class="form-control form-control-sm" id="typeFilter">
                                        <option value="all">All Types</option>
                                        <option value="dine-in">Dine In</option>
                                        <option value="takeaway">Takeaway</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Sort By</label>
                                    <select class="form-control form-control-sm" id="sortFilter">
                                        <option value="newest">Newest First</option>
                                        <option value="oldest">Oldest First</option>
                                        <option value="highest">Highest Amount</option>
                                        <option value="lowest">Lowest Amount</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2 mt-4">
                                    <button class="btn btn-primary btn-sm" id="applyFilters">
                                        <i class="fas fa-filter"></i> Apply Filters
                                    </button>
                                    <button class="btn btn-secondary btn-sm" id="resetFilters">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                    <button class="btn btn-success btn-sm" id="exportData">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Date Range (Hidden by default) -->
        <div class="row mb-3" id="customDateRange" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-2">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label class="form-label small">From Date</label>
                                <input type="date" class="form-control form-control-sm" id="fromDate">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">To Date</label>
                                <input type="date" class="form-control form-control-sm" id="toDate">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Order History</h4>
                            <span class="badge badge-primary" id="orderCount">Showing 25 of 156 orders</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="ordersTable">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date & Time</th>
                                        <th>Customer</th>
                                        <th>Type</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody">
                                    <!-- Orders will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <select class="form-control form-control-sm d-inline-block" id="perPage" style="width: auto;">
                                    <option value="10">10 per page</option>
                                    <option value="25" selected>25 per page</option>
                                    <option value="50">50 per page</option>
                                    <option value="100">100 per page</option>
                                </select>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination">
                                    <!-- Pagination will be generated here -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
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
                <button type="button" class="btn btn-primary" id="printReceipt">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 12px 8px;
}

.table td {
    padding: 10px 8px;
    font-size: 0.85rem;
    vertical-align: middle;
}

.order-type-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
}

.type-dine-in {
    background: #d4edda;
    color: #155724;
}

.type-takeaway {
    background: #fff3cd;
    color: #856404;
}

.type-delivery {
    background: #d1ecf1;
    color: #0c5460;
}

.payment-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
}

.payment-completed {
    background: #d4edda;
    color: #155724;
}

.payment-pending {
    background: #fff3cd;
    color: #856404;
}

.items-count {
    font-size: 0.75rem;
    color: #6c757d;
}

.order-total {
    font-weight: 600;
    color: #1572e8;
}

.btn-action {
    font-size: 0.75rem;
    padding: 4px 8px;
    margin: 0 2px;
}

.table-responsive {
    border-radius: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample order history data - replace with actual API calls
    let allOrders = [
        {
            id: 'ORD-156',
            date: '2024-01-15 14:30:00',
            customer: 'John Doe',
            type: 'dine-in',
            items: [
                { name: 'Cappuccino', quantity: 2, price: 4.25 },
                { name: 'Croissant', quantity: 1, price: 2.95 }
            ],
            total: 11.45,
            payment_status: 'completed',
            payment_method: 'Card'
        },
        {
            id: 'ORD-155',
            date: '2024-01-15 14:15:00',
            customer: 'Jane Smith',
            type: 'takeaway',
            items: [
                { name: 'Latte', quantity: 1, price: 4.75 },
                { name: 'Blueberry Muffin', quantity: 2, price: 3.45 }
            ],
            total: 11.65,
            payment_status: 'completed',
            payment_method: 'Cash'
        },
        {
            id: 'ORD-154',
            date: '2024-01-15 13:45:00',
            customer: 'Mike Wilson',
            type: 'delivery',
            items: [
                { name: 'Americano', quantity: 3, price: 3.25 },
                { name: 'Caesar Salad', quantity: 1, price: 8.95 }
            ],
            total: 18.70,
            payment_status: 'completed',
            payment_method: 'Card'
        },
        {
            id: 'ORD-153',
            date: '2024-01-15 13:20:00',
            customer: 'Sarah Johnson',
            type: 'dine-in',
            items: [
                { name: 'Espresso', quantity: 1, price: 3.50 },
                { name: 'Club Sandwich', quantity: 1, price: 9.75 }
            ],
            total: 13.25,
            payment_status: 'completed',
            payment_method: 'Card'
        },
        {
            id: 'ORD-152',
            date: '2024-01-15 12:55:00',
            customer: 'David Brown',
            type: 'takeaway',
            items: [
                { name: 'Cappuccino', quantity: 1, price: 4.25 },
                { name: 'Croissant', quantity: 2, price: 2.95 }
            ],
            total: 10.15,
            payment_status: 'completed',
            payment_method: 'Cash'
        }
    ];

    let filteredOrders = [...allOrders];
    let currentPage = 1;
    let ordersPerPage = 25;

    // Initialize page
    loadOrders();
    updateStatistics();
    setupEventListeners();

    function setupEventListeners() {
        // Search input
        document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
        
        // Filter selectors
        document.getElementById('dateFilter').addEventListener('change', function() {
            if (this.value === 'custom') {
                document.getElementById('customDateRange').style.display = 'block';
            } else {
                document.getElementById('customDateRange').style.display = 'none';
                applyFilters();
            }
        });
        
        document.getElementById('typeFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);
        document.getElementById('perPage').addEventListener('change', function() {
            ordersPerPage = parseInt(this.value);
            currentPage = 1;
            loadOrders();
        });
        
        // Filter buttons
        document.getElementById('applyFilters').addEventListener('click', applyFilters);
        document.getElementById('resetFilters').addEventListener('click', resetFilters);
        document.getElementById('exportData').addEventListener('click', exportData);
        
        // Custom date range
        document.getElementById('fromDate').addEventListener('change', applyFilters);
        document.getElementById('toDate').addEventListener('change', applyFilters);
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const dateFilter = document.getElementById('dateFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const sortFilter = document.getElementById('sortFilter').value;

        // Filter by search term
        filteredOrders = allOrders.filter(order => {
            return order.id.toLowerCase().includes(searchTerm) ||
                   order.customer.toLowerCase().includes(searchTerm);
        });

        // Filter by date range
        if (dateFilter !== 'custom') {
            filteredOrders = filteredOrders.filter(order => isInDateRange(order.date, dateFilter));
        } else {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            if (fromDate && toDate) {
                filteredOrders = filteredOrders.filter(order => {
                    const orderDate = new Date(order.date).toISOString().split('T')[0];
                    return orderDate >= fromDate && orderDate <= toDate;
                });
            }
        }

        // Filter by order type
        if (typeFilter !== 'all') {
            filteredOrders = filteredOrders.filter(order => order.type === typeFilter);
        }

        // Sort orders
        sortOrders(sortFilter);

        // Reset to first page
        currentPage = 1;
        loadOrders();
        updateStatistics();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('dateFilter').value = 'this-week';
        document.getElementById('typeFilter').value = 'all';
        document.getElementById('sortFilter').value = 'newest';
        document.getElementById('customDateRange').style.display = 'none';
        
        filteredOrders = [...allOrders];
        currentPage = 1;
        loadOrders();
        updateStatistics();
    }

    function sortOrders(sortBy) {
        switch(sortBy) {
            case 'newest':
                filteredOrders.sort((a, b) => new Date(b.date) - new Date(a.date));
                break;
            case 'oldest':
                filteredOrders.sort((a, b) => new Date(a.date) - new Date(b.date));
                break;
            case 'highest':
                filteredOrders.sort((a, b) => b.total - a.total);
                break;
            case 'lowest':
                filteredOrders.sort((a, b) => a.total - b.total);
                break;
        }
    }

    function isInDateRange(orderDate, range) {
        const today = new Date();
        const orderDateTime = new Date(orderDate);
        
        switch(range) {
            case 'today':
                return orderDateTime.toDateString() === today.toDateString();
            case 'yesterday':
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                return orderDateTime.toDateString() === yesterday.toDateString();
            case 'this-week':
                const startOfWeek = new Date(today);
                startOfWeek.setDate(today.getDate() - today.getDay());
                return orderDateTime >= startOfWeek;
            case 'last-week':
                const startOfLastWeek = new Date(today);
                startOfLastWeek.setDate(today.getDate() - today.getDay() - 7);
                const endOfLastWeek = new Date(startOfLastWeek);
                endOfLastWeek.setDate(startOfLastWeek.getDate() + 6);
                return orderDateTime >= startOfLastWeek && orderDateTime <= endOfLastWeek;
            case 'this-month':
                return orderDateTime.getMonth() === today.getMonth() && 
                       orderDateTime.getFullYear() === today.getFullYear();
            case 'last-month':
                const lastMonth = new Date(today);
                lastMonth.setMonth(today.getMonth() - 1);
                return orderDateTime.getMonth() === lastMonth.getMonth() && 
                       orderDateTime.getFullYear() === lastMonth.getFullYear();
            default:
                return true;
        }
    }

    function loadOrders() {
        const tbody = document.getElementById('ordersTableBody');
        const startIndex = (currentPage - 1) * ordersPerPage;
        const endIndex = startIndex + ordersPerPage;
        const ordersToShow = filteredOrders.slice(startIndex, endIndex);

        if (ordersToShow.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <br>
                        <span class="text-muted">No orders found</span>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = ordersToShow.map(order => createOrderRow(order)).join('');
        }

        updateOrderCount();
        generatePagination();
    }

    function createOrderRow(order) {
        const orderDate = new Date(order.date);
        const formattedDate = orderDate.toLocaleDateString();
        const formattedTime = orderDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        return `
            <tr>
                <td>
                    <span class="fw-bold text-primary">#${order.id}</span>
                </td>
                <td>
                    <div>${formattedDate}</div>
                    <small class="text-muted">${formattedTime}</small>
                </td>
                <td>${order.customer}</td>
                <td>
                    <span class="badge order-type-badge type-${order.type}">
                        ${order.type.replace('-', ' ').toUpperCase()}
                    </span>
                </td>
                <td>
                    <span class="fw-bold">${order.items.length} items</span>
                    <div class="items-count">
                        ${order.items.map(item => `${item.quantity}x ${item.name}`).join(', ').substring(0, 30)}...
                    </div>
                </td>
                <td>
                    <span class="order-total">$${order.total.toFixed(2)}</span>
                </td>
                <td>
                    <span class="badge payment-badge payment-${order.payment_status}">
                        ${order.payment_status.toUpperCase()}
                    </span>
                    <div class="items-count">${order.payment_method}</div>
                </td>
                <td>
                    <button class="btn btn-outline-primary btn-action" onclick="viewOrderDetails('${order.id}')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-success btn-action" onclick="printReceipt('${order.id}')">
                        <i class="fas fa-print"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    function updateOrderCount() {
        const total = filteredOrders.length;
        const startIndex = (currentPage - 1) * ordersPerPage + 1;
        const endIndex = Math.min(currentPage * ordersPerPage, total);
        
        document.getElementById('orderCount').textContent = 
            `Showing ${startIndex}-${endIndex} of ${total} orders`;
    }

    function generatePagination() {
        const pagination = document.getElementById('pagination');
        const totalPages = Math.ceil(filteredOrders.length / ordersPerPage);
        
        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage - 1})">Previous</a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                paginationHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
                    </li>
                `;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Next button
        paginationHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage + 1})">Next</a>
            </li>
        `;

        pagination.innerHTML = paginationHTML;
    }

    function updateStatistics() {
        const totalOrders = filteredOrders.length;
        const totalRevenue = filteredOrders.reduce((sum, order) => sum + order.total, 0);
        const avgOrder = totalOrders > 0 ? totalRevenue / totalOrders : 0;
        const todayOrders = filteredOrders.filter(order => isInDateRange(order.date, 'today')).length;

        document.getElementById('total-orders').textContent = totalOrders;
        document.getElementById('total-revenue').textContent = `$${totalRevenue.toFixed(2)}`;
        document.getElementById('avg-order').textContent = `$${avgOrder.toFixed(2)}`;
        document.getElementById('today-orders').textContent = todayOrders;
    }

    function exportData() {
        const csvData = convertToCSV(filteredOrders);
        downloadCSV(csvData, 'order-history.csv');
    }

    function convertToCSV(orders) {
        const headers = ['Order ID', 'Date', 'Time', 'Customer', 'Type', 'Items', 'Total', 'Payment Status', 'Payment Method'];
        const rows = orders.map(order => {
            const orderDate = new Date(order.date);
            const items = order.items.map(item => `${item.quantity}x ${item.name}`).join('; ');
            return [
                order.id,
                orderDate.toLocaleDateString(),
                orderDate.toLocaleTimeString(),
                order.customer,
                order.type,
                items,
                order.total.toFixed(2),
                order.payment_status,
                order.payment_method
            ];
        });

        return [headers, ...rows].map(row => row.map(field => `"${field}"`).join(',')).join('\n');
    }

    function downloadCSV(csvData, filename) {
        const blob = new Blob([csvData], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Global functions
    window.goToPage = function(page) {
        const totalPages = Math.ceil(filteredOrders.length / ordersPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            loadOrders();
        }
    };

    window.viewOrderDetails = function(orderId) {
        const order = allOrders.find(o => o.id === orderId);
        if (!order) return;

        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Order Information</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Order ID:</strong></td><td>#${order.id}</td></tr>
                        <tr><td><strong>Customer:</strong></td><td>${order.customer}</td></tr>
                        <tr><td><strong>Type:</strong></td><td>${order.type.replace('-', ' ').toUpperCase()}</td></tr>
                        <tr><td><strong>Date:</strong></td><td>${new Date(order.date).toLocaleDateString()}</td></tr>
                        <tr><td><strong>Time:</strong></td><td>${new Date(order.date).toLocaleTimeString()}</td></tr>
                        <tr><td><strong>Payment:</strong></td><td>${order.payment_method} - ${order.payment_status.toUpperCase()}</td></tr>
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

    window.printReceipt = function(orderId) {
        const order = allOrders.find(o => o.id === orderId);
        if (!order) return;

        // Create receipt content
        const receiptContent = `
            <div style="text-align: center; font-family: monospace; width: 300px; margin: 0 auto;">
                <h3>KALIBREWHAN CAFE</h3>
                <p>Receipt #${order.id}</p>
                <hr>
                <div style="text-align: left;">
                    <strong>Customer:</strong> ${order.customer}<br>
                    <strong>Date:</strong> ${new Date(order.date).toLocaleString()}<br>
                    <strong>Type:</strong> ${order.type.toUpperCase()}<br>
                </div>
                <hr>
                ${order.items.map(item => `
                    <div style="display: flex; justify-content: space-between;">
                        <span>${item.quantity}x ${item.name}</span>
                        <span>$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `).join('')}
                <hr>
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <span>TOTAL:</span>
                    <span>$${order.total.toFixed(2)}</span>
                </div>
                <hr>
                <p style="text-align: center;">Thank you for your visit!</p>
            </div>
        `;

        // Open print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head><title>Receipt #${order.id}</title></head>
                <body>${receiptContent}</body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    };

    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
</script>
@endsection