@extends('layouts.app')

@section('title', 'Order History - Librewhan Cafe')

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
                                        <option value="today" selected>Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="this-week">This Week</option>
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
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-primary">#{{ $order->order_number }}</span>
                                                <div id="order-data-{{ $order->id }}" style="display:none;">
                                                    {!! json_encode([
                                                        'id' => $order->id,
                                                        'order_number' => $order->order_number,
                                                        'customer_name' => $order->customer_name,
                                                        'created_at' => $order->created_at->format('Y-m-d H:i'),
                                                        'order_type' => $order->order_type,
                                                        'total' => $order->total,
                                                        'items' => $order->items->map(function($item) {
                                                            return [
                                                                'name' => $item->name,
                                                                'qty' => $item->qty,
                                                                'price' => $item->price
                                                            ];
                                                        })->values(),
                                                    ]) !!}
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $order->created_at->format('Y-m-d') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>{{ $order->customer_name }}</td>
                                            <td>
                                                <span class="badge order-type-badge type-{{ $order->order_type }}">
                                                    {{ strtoupper(str_replace('-', ' ', $order->order_type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $order->items->count() }} items</span>
                                                <div class="items-count">
                                                    {{ $order->items->map(fn($item) => $item->qty . 'x ' . $item->name)->implode(', ') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="order-total">P{{ number_format($order->total, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge payment-badge payment-completed">COMPLETED</span>
                                                <div class="items-count">{{ $order->payment_method }}</div>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-action" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" data-order-id="{{ $order->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success btn-action" onclick="showReceiptModal({{ $order->id }})">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i><br>
                                                <span class="text-muted">No orders found</span>
                                            </td>
                                        </tr>
                                    @endforelse
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

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="receiptContent">
                <!-- Receipt details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printReceiptContent()">
                    <i class="fas fa-print"></i> Print
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
    // Export table as Excel
    document.getElementById('exportData').addEventListener('click', function() {
        const table = document.getElementById('ordersTable');
        if (!table) return alert('Orders table not found!');
        let rows = Array.from(table.querySelectorAll('thead tr, tbody tr'));
        let data = rows.map(row => Array.from(row.querySelectorAll('th,td')).map(cell => cell.innerText));
        // Convert to HTML table for Excel
        function toExcel(data) {
            let html = '<table><thead><tr>' + data[0].map(cell => `<th>${cell}</th>`).join('') + '</tr></thead><tbody>';
            for (let i = 1; i < data.length; i++) {
                html += '<tr>' + data[i].map(cell => `<td>${cell}</td>`).join('') + '</tr>';
            }
            html += '</tbody></table>';
            return html;
        }
        // Download helper
        function download(filename, content, mime) {
            const blob = new Blob([content], { type: mime });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => { document.body.removeChild(a); URL.revokeObjectURL(url); }, 100);
        }
        const excelHtml = `\uFEFF<html><head><meta charset='UTF-8'></head><body>${toExcel(data)}</body></html>`;
        download('order-history.xls', excelHtml, 'application/vnd.ms-excel');
    });
    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const typeFilter = document.getElementById('typeFilter');
    const sortFilter = document.getElementById('sortFilter');
    const applyBtn = document.getElementById('applyFilters');
    const resetBtn = document.getElementById('resetFilters');
    const ordersTableBody = document.getElementById('ordersTableBody');
    const perPageSelect = document.getElementById('perPage');
    let allRows = Array.from(ordersTableBody.querySelectorAll('tr')).map(row => row.cloneNode(true));

    function getOrderData(row) {
        // Extract order data from hidden div
        const dataDiv = row.querySelector('div[id^="order-data-"]');
        if (!dataDiv) return {};
        try { return JSON.parse(dataDiv.textContent); } catch { return {}; }
    }

    function filterRows() {
        let filtered = allRows.slice();
        // Search
        const searchVal = searchInput.value.trim().toLowerCase();
        if (searchVal) {
            filtered = filtered.filter(row => {
                const order = getOrderData(row);
                return (
                    (order.order_number && order.order_number.toString().toLowerCase().includes(searchVal)) ||
                    (order.customer_name && order.customer_name.toLowerCase().includes(searchVal))
                );
            });
        }
        // Type
        const typeVal = typeFilter.value;
        if (typeVal !== 'all') {
            filtered = filtered.filter(row => {
                const order = getOrderData(row);
                return order.order_type === typeVal;
            });
        }
        // Date (simple filter: today, yesterday, week, month)
        const dateVal = dateFilter.value;
        if (dateVal !== 'custom') {
            const now = new Date();
            filtered = filtered.filter(row => {
                const order = getOrderData(row);
                if (!order.created_at) return true;
                const orderDate = new Date(order.created_at);
                if (dateVal === 'today') {
                    return orderDate.toDateString() === now.toDateString();
                } else if (dateVal === 'yesterday') {
                    const yest = new Date(now); yest.setDate(now.getDate()-1);
                    return orderDate.toDateString() === yest.toDateString();
                } else if (dateVal === 'this-week') {
                    const weekStart = new Date(now); weekStart.setDate(now.getDate()-now.getDay());
                    return orderDate >= weekStart && orderDate <= now;
                } else if (dateVal === 'last-week') {
                    const weekStart = new Date(now); weekStart.setDate(now.getDate()-now.getDay()-7);
                    const weekEnd = new Date(weekStart); weekEnd.setDate(weekStart.getDate()+6);
                    return orderDate >= weekStart && orderDate <= weekEnd;
                } else if (dateVal === 'this-month') {
                    return orderDate.getMonth() === now.getMonth() && orderDate.getFullYear() === now.getFullYear();
                } else if (dateVal === 'last-month') {
                    const lastMonth = new Date(now); lastMonth.setMonth(now.getMonth()-1);
                    return orderDate.getMonth() === lastMonth.getMonth() && orderDate.getFullYear() === lastMonth.getFullYear();
                }
                return true;
            });
        } else {
            // Custom range
            const from = document.getElementById('fromDate').value;
            const to = document.getElementById('toDate').value;
            if (from && to) {
                const fromDate = new Date(from);
                const toDate = new Date(to);
                filtered = filtered.filter(row => {
                    const order = getOrderData(row);
                    if (!order.created_at) return true;
                    const orderDate = new Date(order.created_at);
                    return orderDate >= fromDate && orderDate <= toDate;
                });
            }
        }
        // Sort
        const sortVal = sortFilter.value;
        filtered.sort((a, b) => {
            const orderA = getOrderData(a);
            const orderB = getOrderData(b);
            if (sortVal === 'newest') {
                return new Date(orderB.created_at) - new Date(orderA.created_at);
            } else if (sortVal === 'oldest') {
                return new Date(orderA.created_at) - new Date(orderB.created_at);
            } else if (sortVal === 'highest') {
                return (orderB.total || 0) - (orderA.total || 0);
            } else if (sortVal === 'lowest') {
                return (orderA.total || 0) - (orderB.total || 0);
            }
            return 0;
        });
        // Pagination
        const perPage = parseInt(perPageSelect.value, 10) || 25;
        let page = 1;
        // TODO: Add page navigation if needed
        const paged = filtered.slice((page-1)*perPage, page*perPage);
        ordersTableBody.innerHTML = '';
        paged.forEach(row => ordersTableBody.appendChild(row.cloneNode(true)));
        // Update order count badge
        const orderCount = document.getElementById('orderCount');
        if (orderCount) orderCount.textContent = `Showing ${paged.length} of ${filtered.length} orders`;
    }

    applyBtn.addEventListener('click', filterRows);
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
    dateFilter.value = 'today';
        typeFilter.value = 'all';
        sortFilter.value = 'newest';
        perPageSelect.value = '25';
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        filterRows();
    });
    searchInput.addEventListener('keyup', function(e) { if (e.key === 'Enter') filterRows(); });
    dateFilter.addEventListener('change', function() {
        document.getElementById('customDateRange').style.display = (dateFilter.value === 'custom') ? '' : 'none';
    });
    perPageSelect.addEventListener('change', filterRows);
    // Initial filter
    filterRows();
});
function showReceiptModal(orderId) {
    // Find order data from Blade (rendered in table row)
    // We'll use a hidden div to store JSON for each order
    const orderDataDiv = document.getElementById('order-data-' + orderId);
    if (!orderDataDiv) return;
    const order = JSON.parse(orderDataDiv.textContent);

    // Build receipt HTML
    let html = `<div style=\"text-align:center;font-family:monospace;width:300px;margin:0 auto;\">
        <h3>LIBREWHAN CAFE</h3>
        <p>Receipt #${order.order_number}</p>
        <hr>
        <div style=\"text-align:left;\">
            <strong>Customer:</strong> ${order.customer_name || '-'}<br>
            <strong>Date:</strong> ${order.created_at}<br>
            <strong>Type:</strong> ${order.order_type.toUpperCase()}<br>
        </div>
        <hr>
        ${order.items.map(item => `
            <div style='display:flex;justify-content:space-between;'>
                <span>${item.qty}x ${item.name}</span>
                <span>P${(item.price * item.qty).toFixed(2)}</span>
            </div>
        `).join('')}
        <hr>
        <div style='display:flex;justify-content:space-between;font-weight:bold;'>
            <span>TOTAL:</span>
            <span>P${parseFloat(order.total).toFixed(2)}</span>
        </div>
        <hr>
        <p style='text-align:center;'>Thank you for your visit!</p>
    </div>`;
    document.getElementById('receiptContent').innerHTML = html;
    let modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    modal.show();
}

function printReceiptContent() {
    const content = document.getElementById('receiptContent').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`<html><head><title>Receipt</title></head><body>${content}</body></html>`);
    printWindow.document.close();
    printWindow.print();
}
</script>

<!-- Blade dynamic rendering: orders from controller -->
<!-- ...existing code... -->
<!-- ...existing code... -->
<!-- Only keep the top order history table and pagination -->
<!-- ...existing code... -->
@endsection
