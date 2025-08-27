
@extends('layouts.app')

@section('title', 'Stock Levels - Librewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Stock Levels Management</h3>
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
                    <a href="#">Inventory</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Stock Levels</a>
                </li>
            </ul>
        </div>

        <!-- Stock Alerts Banner -->
        <div class="row mb-4" id="alertsBanner">
            <!-- Dynamic alerts will be loaded here -->
        </div>

        <!-- Stock Statistics -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Out of Stock</p>
                                    <h4 class="card-title" id="out-of-stock">3</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-battery-quarter"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Low Stock</p>
                                    <h4 class="card-title" id="low-stock">7</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-battery-full"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Normal Stock</p>
                                    <h4 class="card-title" id="normal-stock">18</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-archive"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Overstocked</p>
                                    <h4 class="card-title" id="overstocked">2</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Search Products</label>
                                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Product name, SKU...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Stock Status</label>
                                    <select class="form-control form-control-sm" id="stockFilter">
                                        <option value="all">All Items</option>
                                        <option value="out-of-stock">Out of Stock</option>
                                        <option value="low-stock">Low Stock</option>
                                        <option value="normal">Normal Stock</option>
                                        <option value="overstocked">Overstocked</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Category</label>
                                    <select class="form-control form-control-sm" id="categoryFilter">
                                        <option value="all">All Categories</option>
                                        <option value="Coffee">Coffee</option>
                                        <option value="Pastry">Pastry</option>
                                        <option value="Food">Food</option>
                                        <option value="Beverage">Beverage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Sort By</label>
                                    <select class="form-control form-control-sm" id="sortFilter">
                                        <option value="stock-asc">Stock (Low to High)</option>
                                        <option value="stock-desc">Stock (High to Low)</option>
                                        <option value="name">Name A-Z</option>
                                        <option value="category">Category</option>
                                        <option value="alert-level">Alert Level</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2 mt-4">
                                    <button class="btn btn-primary btn-sm" id="bulkRestockBtn">
                                        <i class="fas fa-boxes"></i> Bulk Restock
                                    </button>
                                    <button class="btn btn-success btn-sm" id="exportBtn">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                    <button class="btn btn-secondary btn-sm" id="refreshBtn">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Levels Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Stock Levels Overview</h4>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="tableViewBtn">
                                    <i class="fas fa-list"></i> Table
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="cardViewBtn">
                                    <i class="fas fa-th-large"></i> Cards
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Table View -->
                        <div id="tableView">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="stockTable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Current Stock</th>
                                            <th>Min Level</th>
                                            <th>Max Level</th>
                                            <th>Status</th>
                                            <th>Last Restocked</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stockTableBody">
                                        <!-- Stock items will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Card View -->
                        <div id="cardView" style="display: none;">
                            <div class="row" id="stockCardsContainer">
                                <!-- Stock cards will be loaded here -->
                            </div>
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

<!-- Quick Stock Update Modal -->
<div class="modal fade" id="quickStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Stock Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Product:</strong> <span id="quickStockProductName"></span>
                </div>
                <div class="mb-3">
                    <strong>Current Stock:</strong> <span id="quickCurrentStock"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">New Stock Level</label>
                    <input type="number" class="form-control" id="quickNewStock" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason</label>
                    <select class="form-control" id="quickStockReason">
                        <option value="restock">Restock</option>
                        <option value="adjustment">Inventory Adjustment</option>
                        <option value="damaged">Damaged Items</option>
                        <option value="sold">Manual Sale</option>
                        <option value="expired">Expired Items</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="quickStockNotes" rows="2" placeholder="Optional notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveQuickStockBtn">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Restock Modal -->
<div class="modal fade" id="bulkRestockModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Restock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Select products below to perform bulk restocking operations.
                </div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="bulkSelectAll"></th>
                                <th>Product</th>
                                <th>Current Stock</th>
                                <th>Recommended</th>
                                <th>New Stock</th>
                            </tr>
                        </thead>
                        <tbody id="bulkRestockBody">
                            <!-- Bulk restock items will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="restockLowStockBtn">Restock Low Stock Items</button>
                <button type="button" class="btn btn-primary" id="saveBulkRestockBtn">Update Selected</button>
            </div>
        </div>
    </div>
</div>

<!-- Stock Alert Settings Modal -->
<div class="modal fade" id="alertSettingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Alert Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="alertSettingsForm">
                    <div class="form-group">
                        <label class="form-label">Low Stock Threshold (%)</label>
                        <input type="number" class="form-control" id="lowStockThreshold" min="1" max="50" value="20">
                        <small class="form-text text-muted">Alert when stock falls below this percentage of max level</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Overstock Threshold (%)</label>
                        <input type="number" class="form-control" id="overstockThreshold" min="100" max="200" value="150">
                        <small class="form-text text-muted">Alert when stock exceeds this percentage of max level</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="emailAlerts" checked>
                        <label class="form-check-label" for="emailAlerts">
                            Send email alerts for stock issues
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                        <label class="form-check-label" for="pushNotifications">
                            Show push notifications
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveAlertSettingsBtn">Save Settings</button>
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

.stock-status {
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.stock-out {
    background: #f8d7da;
    color: #721c24;
}

.stock-low {
    background: #fff3cd;
    color: #856404;
}

.stock-normal {
    background: #d4edda;
    color: #155724;
}

.stock-overstocked {
    background: #d1ecf1;
    color: #0c5460;
}

.alert-banner {
    border-left: 4px solid;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
}

.alert-critical {
    border-color: #dc3545;
    background: #f8d7da;
    color: #721c24;
}

.alert-warning {
    border-color: #ffc107;
    background: #fff3cd;
    color: #856404;
}

.alert-info {
    border-color: #17a2b8;
    background: #d1ecf1;
    color: #0c5460;
}

.stock-card {
    border: 2px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.2s ease;
    margin-bottom: 20px;
}

.stock-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stock-card.critical {
    border-color: #dc3545;
    background: #fff5f5;
}

.stock-card.warning {
    border-color: #ffc107;
    background: #fffbf0;
}

.stock-card.normal {
    border-color: #28a745;
    background: #f8fff9;
}

.stock-card.info {
    border-color: #17a2b8;
    background: #f0f9ff;
}

.stock-level-bar {
    height: 8px;
    border-radius: 4px;
    background: #e9ecef;
    overflow: hidden;
    margin: 10px 0;
}

.stock-level-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.stock-level-fill.critical {
    background: #dc3545;
}

.stock-level-fill.warning {
    background: #ffc107;
}

.stock-level-fill.normal {
    background: #28a745;
}

.stock-level-fill.info {
    background: #17a2b8;
}

.btn-action {
    font-size: 0.75rem;
    padding: 4px 8px;
    margin: 0 2px;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
}

.product-sku {
    font-size: 0.75rem;
    color: #6c757d;
}

.stock-numbers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 10px 0;
}

.stock-current {
    font-size: 1.5rem;
    font-weight: 700;
}

.stock-range {
    font-size: 0.8rem;
    color: #6c757d;
}

.notification-dot {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #dc3545;
    border: 2px solid white;
}

.floating-alerts {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    max-width: 300px;
}

.floating-alert {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-bottom: 10px;
    padding: 15px;
    border-left: 4px solid;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample stock data - replace with actual API calls
    let allStockItems = [
        {
            id: 1,
            name: 'Espresso',
            sku: 'ESP-001',
            category: 'Coffee',
            current_stock: 45,
            min_level: 20,
            max_level: 100,
            last_restocked: '2024-01-10 14:30:00',
            cost_per_unit: 0.85,
            status: 'normal'
        },
        {
            id: 2,
            name: 'Cappuccino',
            sku: 'CAP-001',
            category: 'Coffee',
            current_stock: 32,
            min_level: 25,
            max_level: 80,
            last_restocked: '2024-01-12 09:15:00',
            cost_per_unit: 1.20,
            status: 'normal'
        },
        {
            id: 3,
            name: 'Americano',
            sku: 'AME-001',
            category: 'Coffee',
            current_stock: 8,
            min_level: 15,
            max_level: 60,
            last_restocked: '2024-01-08 16:45:00',
            cost_per_unit: 0.95,
            status: 'low'
        },
        {
            id: 4,
            name: 'Club Sandwich',
            sku: 'SAN-001',
            category: 'Food',
            current_stock: 0,
            min_level: 10,
            max_level: 50,
            last_restocked: '2024-01-05 11:20:00',
            cost_per_unit: 3.50,
            status: 'out'
        },
        {
            id: 5,
            name: 'Croissant',
            sku: 'CRO-001',
            category: 'Pastry',
            current_stock: 3,
            min_level: 8,
            max_level: 40,
            last_restocked: '2024-01-14 08:00:00',
            cost_per_unit: 1.25,
            status: 'low'
        },
        {
            id: 6,
            name: 'Green Tea',
            sku: 'TEA-001',
            category: 'Beverage',
            current_stock: 150,
            min_level: 30,
            max_level: 80,
            last_restocked: '2024-01-13 10:30:00',
            cost_per_unit: 0.45,
            status: 'overstocked'
        }
    ];

    let filteredItems = [...allStockItems];
    let currentPage = 1;
    let itemsPerPage = 25;
    let currentView = 'table';

    // Initialize page
    loadStockItems();
    updateStatistics();
    generateAlerts();
    setupEventListeners();
    startRealTimeUpdates();

    function setupEventListeners() {
        // Search and filters
        document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
        document.getElementById('stockFilter').addEventListener('change', applyFilters);
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);
        document.getElementById('perPage').addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            loadStockItems();
        });

        // View toggle
        document.getElementById('tableViewBtn').addEventListener('click', () => switchView('table'));
        document.getElementById('cardViewBtn').addEventListener('click', () => switchView('card'));

        // Action buttons
        document.getElementById('bulkRestockBtn').addEventListener('click', showBulkRestockModal);
        document.getElementById('exportBtn').addEventListener('click', exportStockData);
        document.getElementById('refreshBtn').addEventListener('click', refreshStockData);
        document.getElementById('saveQuickStockBtn').addEventListener('click', saveQuickStock);
        document.getElementById('saveBulkRestockBtn').addEventListener('click', saveBulkRestock);
        document.getElementById('restockLowStockBtn').addEventListener('click', restockLowStockItems);
        document.getElementById('saveAlertSettingsBtn').addEventListener('click', saveAlertSettings);

        // Select all checkboxes
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selectedItems"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const stockFilter = document.getElementById('stockFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;
        const sortFilter = document.getElementById('sortFilter').value;

        // Filter items
        filteredItems = allStockItems.filter(item => {
            const matchesSearch = item.name.toLowerCase().includes(searchTerm) ||
                                item.sku.toLowerCase().includes(searchTerm);
            const matchesStock = stockFilter === 'all' || getStockStatus(item) === stockFilter;
            const matchesCategory = categoryFilter === 'all' || item.category === categoryFilter;

            return matchesSearch && matchesStock && matchesCategory;
        });

        // Sort items
        sortItems(sortFilter);

        // Reset to first page
        currentPage = 1;
        loadStockItems();
        updateStatistics();
    }

    function sortItems(sortBy) {
        switch(sortBy) {
            case 'stock-asc':
                filteredItems.sort((a, b) => a.current_stock - b.current_stock);
                break;
            case 'stock-desc':
                filteredItems.sort((a, b) => b.current_stock - a.current_stock);
                break;
            case 'name':
                filteredItems.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'category':
                filteredItems.sort((a, b) => a.category.localeCompare(b.category));
                break;
            case 'alert-level':
                filteredItems.sort((a, b) => getAlertPriority(b) - getAlertPriority(a));
                break;
        }
    }

    function getAlertPriority(item) {
        const status = getStockStatus(item);
        const priorities = { 'out-of-stock': 4, 'low-stock': 3, 'overstocked': 2, 'normal': 1 };
        return priorities[status] || 0;
    }

    function switchView(view) {
        currentView = view;
        
        if (view === 'table') {
            document.getElementById('tableView').style.display = 'block';
            document.getElementById('cardView').style.display = 'none';
            document.getElementById('tableViewBtn').classList.add('active');
            document.getElementById('cardViewBtn').classList.remove('active');
        } else {
            document.getElementById('tableView').style.display = 'none';
            document.getElementById('cardView').style.display = 'block';
            document.getElementById('tableViewBtn').classList.remove('active');
            document.getElementById('cardViewBtn').classList.add('active');
        }
        
        loadStockItems();
    }

    function loadStockItems() {
        if (currentView === 'table') {
            loadStockTable();
        } else {
            loadStockCards();
        }
        generatePagination();
    }

    function loadStockTable() {
        const tbody = document.getElementById('stockTableBody');
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const itemsToShow = filteredItems.slice(startIndex, endIndex);

        if (itemsToShow.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="fas fa-boxes fa-2x text-muted mb-2"></i>
                        <br>
                        <span class="text-muted">No stock items found</span>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = itemsToShow.map(item => createStockRow(item)).join('');
        }
    }

    function loadStockCards() {
        const container = document.getElementById('stockCardsContainer');
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const itemsToShow = filteredItems.slice(startIndex, endIndex);

        if (itemsToShow.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No stock items found</h5>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = itemsToShow.map(item => createStockCard(item)).join('');
        }
    }

    function createStockRow(item) {
        const status = getStockStatus(item);
        const statusInfo = getStockStatusInfo(status);
        const stockPercentage = ((item.current_stock / item.max_level) * 100).toFixed(1);
        
        return `
            <tr class="${status === 'out-of-stock' ? 'table-danger' : status === 'low-stock' ? 'table-warning' : ''}">
                <td><input type="checkbox" name="selectedItems" value="${item.id}"></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            ${status === 'out-of-stock' || status === 'low-stock' ? '<div class="notification-dot"></div>' : ''}
                            <i class="fas fa-box text-${statusInfo.color}"></i>
                        </div>
                        <div>
                            <div class="product-name">${item.name}</div>
                            <div class="product-sku">${item.sku}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge category-badge category-${item.category.toLowerCase()}">${item.category}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2 fw-bold text-${statusInfo.color}">${item.current_stock}</span>
                        <div class="progress" style="width: 60px; height: 6px;">
                            <div class="progress-bar bg-${statusInfo.color}" style="width: ${Math.min(stockPercentage, 100)}%"></div>
                        </div>
                    </div>
                    <small class="text-muted">${stockPercentage}% of max</small>
                </td>
                <td><span class="text-muted">${item.min_level}</span></td>
                <td><span class="text-muted">${item.max_level}</span></td>
                <td>
                    <span class="badge stock-status stock-${status.replace('-', '')}">
                        ${statusInfo.text}
                    </span>
                </td>
                <td>
                    <div>${new Date(item.last_restocked).toLocaleDateString()}</div>
                    <small class="text-muted">${new Date(item.last_restocked).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-primary btn-action" onclick="quickStockUpdate(${item.id})" title="Quick Update">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-success btn-action" onclick="restockItem(${item.id})" title="Restock">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-outline-info btn-action" onclick="viewStockHistory(${item.id})" title="History">
                            <i class="fas fa-history"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function createStockCard(item) {
        const status = getStockStatus(item);
        const statusInfo = getStockStatusInfo(status);
        const stockPercentage = ((item.current_stock / item.max_level) * 100).toFixed(1);
        
        return `
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card stock-card ${statusInfo.cardClass}">
                    <div class="card-header text-center">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge category-badge category-${item.category.toLowerCase()}">${item.category}</span>
                            <span class="badge stock-status stock-${status.replace('-', '')}">${statusInfo.text}</span>
                        </div>
                        <h6 class="product-name mb-1">${item.name}</h6>
                        <small class="product-sku">${item.sku}</small>
                    </div>
                    <div class="card-body">
                        <div class="stock-numbers">
                            <div class="text-center">
                                <div class="stock-current text-${statusInfo.color}">${item.current_stock}</div>
                                <small class="text-muted">Current</small>
                            </div>
                            <div class="text-center">
                                <div class="stock-range">${item.min_level} - ${item.max_level}</div>
                                <small class="text-muted">Range</small>
                            </div>
                        </div>
                        
                        <div class="stock-level-bar">
                            <div class="stock-level-fill ${statusInfo.fillClass}" style="width: ${Math.min(stockPercentage, 100)}%"></div>
                        </div>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Last restocked: ${new Date(item.last_restocked).toLocaleDateString()}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-primary btn-action" onclick="quickStockUpdate(${item.id})">
                                <i class="fas fa-edit"></i> Update
                            </button>
                            <button class="btn btn-outline-success btn-action" onclick="restockItem(${item.id})">
                                <i class="fas fa-plus"></i> Restock
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function getStockStatus(item) {
        if (item.current_stock === 0) return 'out-of-stock';
        if (item.current_stock <= item.min_level) return 'low-stock';
        if (item.current_stock > (item.max_level * 1.2)) return 'overstocked';
        return 'normal';
    }

    function getStockStatusInfo(status) {
        const statusMap = {
            'out-of-stock': {
                text: 'Out of Stock',
                color: 'danger',
                cardClass: 'critical',
                fillClass: 'critical'
            },
            'low-stock': {
                text: 'Low Stock',
                color: 'warning',
                cardClass: 'warning',
                fillClass: 'warning'
            },
            'normal': {
                text: 'Normal',
                color: 'success',
                cardClass: 'normal',
                fillClass: 'normal'
            },
            'overstocked': {
                text: 'Overstocked',
                color: 'info',
                cardClass: 'info',
                fillClass: 'info'
            }
        };
        
        return statusMap[status] || statusMap['normal'];
    }

    function updateStatistics() {
        const outOfStock = allStockItems.filter(item => getStockStatus(item) === 'out-of-stock').length;
        const lowStock = allStockItems.filter(item => getStockStatus(item) === 'low-stock').length;
        const normalStock = allStockItems.filter(item => getStockStatus(item) === 'normal').length;
        const overstocked = allStockItems.filter(item => getStockStatus(item) === 'overstocked').length;

        document.getElementById('out-of-stock').textContent = outOfStock;
        document.getElementById('low-stock').textContent = lowStock;
        document.getElementById('normal-stock').textContent = normalStock;
        document.getElementById('overstocked').textContent = overstocked;
    }

    function generateAlerts() {
        const alertsContainer = document.getElementById('alertsBanner');
        const outOfStockItems = allStockItems.filter(item => getStockStatus(item) === 'out-of-stock');
        const lowStockItems = allStockItems.filter(item => getStockStatus(item) === 'low-stock');
        const overstockedItems = allStockItems.filter(item => getStockStatus(item) === 'overstocked');

        let alertsHTML = '';

        if (outOfStockItems.length > 0) {
            alertsHTML += `
                <div class="col-12">
                    <div class="alert-banner alert-critical">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Critical Alert:</strong> ${outOfStockItems.length} item(s) are out of stock
                                <div class="mt-1">
                                    ${outOfStockItems.slice(0, 3).map(item => item.name).join(', ')}
                                    ${outOfStockItems.length > 3 ? ` and ${outOfStockItems.length - 3} more` : ''}
                                </div>
                            </div>
                            <button class="btn btn-sm btn-danger" onclick="restockOutOfStockItems()">
                                <i class="fas fa-plus"></i> Restock Now
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        if (lowStockItems.length > 0) {
            alertsHTML += `
                <div class="col-12">
                    <div class="alert-banner alert-warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Low Stock Warning:</strong> ${lowStockItems.length} item(s) need restocking
                                <div class="mt-1">
                                    ${lowStockItems.slice(0, 3).map(item => `${item.name} (${item.current_stock} left)`).join(', ')}
                                </div>
                            </div>
                            <button class="btn btn-sm btn-warning" onclick="showBulkRestockModal()">
                                <i class="fas fa-boxes"></i> Bulk Restock
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        if (overstockedItems.length > 0) {
            alertsHTML += `
                <div class="col-12">
                    <div class="alert-banner alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Overstock Notice:</strong> ${overstockedItems.length} item(s) are overstocked
                                <div class="mt-1">
                                    ${overstockedItems.slice(0, 3).map(item => `${item.name} (${item.current_stock} units)`).join(', ')}
                                </div>
                            </div>
                            <button class="btn btn-sm btn-info" onclick="viewOverstockedItems()">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        alertsContainer.innerHTML = alertsHTML;
    }

    function generatePagination() {
        const pagination = document.getElementById('pagination');
        const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
        
        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let paginationHTML = `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage - 1})">Previous</a>
            </li>
        `;

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

        paginationHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="goToPage(${currentPage + 1})">Next</a>
            </li>
        `;

        pagination.innerHTML = paginationHTML;
    }

    function startRealTimeUpdates() {
        // Simulate real-time stock updates
        setInterval(() => {
            // Randomly update some stock levels (simulate sales)
            const randomItem = allStockItems[Math.floor(Math.random() * allStockItems.length)];
            if (randomItem.current_stock > 0 && Math.random() < 0.1) { // 10% chance
                randomItem.current_stock = Math.max(0, randomItem.current_stock - 1);
                
                // Show notification for critical changes
                const newStatus = getStockStatus(randomItem);
                if (newStatus === 'out-of-stock' || newStatus === 'low-stock') {
                    showFloatingAlert(randomItem, newStatus);
                }
                
                // Update displays
                if (filteredItems.includes(randomItem)) {
                    loadStockItems();
                    updateStatistics();
                    generateAlerts();
                }
            }
        }, 30000); // Check every 30 seconds
    }

    function showFloatingAlert(item, status) {
        const alertsContainer = document.querySelector('.floating-alerts') || createFloatingAlertsContainer();
        const alertColor = status === 'out-of-stock' ? 'danger' : 'warning';
        const alertText = status === 'out-of-stock' ? 'is now out of stock!' : 'is running low!';
        
        const alertElement = document.createElement('div');
        alertElement.className = `floating-alert border-${alertColor}`;
        alertElement.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-${alertColor} me-2"></i>
                <div>
                    <strong>${item.name}</strong> ${alertText}
                    <br>
                    <small>Current stock: ${item.current_stock}</small>
                </div>
                <button class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        alertsContainer.appendChild(alertElement);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertElement.parentElement) {
                alertElement.remove();
            }
        }, 5000);
    }

    function createFloatingAlertsContainer() {
        const container = document.createElement('div');
        container.className = 'floating-alerts';
        document.body.appendChild(container);
        return container;
    }

    // Modal and action functions
    function showBulkRestockModal() {
        const lowStockItems = allStockItems.filter(item => 
            getStockStatus(item) === 'low-stock' || getStockStatus(item) === 'out-of-stock'
        );
        
        const tbody = document.getElementById('bulkRestockBody');
        tbody.innerHTML = lowStockItems.map(item => {
            const recommendedStock = Math.ceil(item.max_level * 0.8); // 80% of max level
            return `
                <tr>
                    <td><input type="checkbox" name="bulkItems" value="${item.id}" checked></td>
                    <td>
                        <div class="fw-bold">${item.name}</div>
                        <small class="text-muted">${item.sku}</small>
                    </td>
                    <td>
                        <span class="text-${getStockStatusInfo(getStockStatus(item)).color}">
                            ${item.current_stock}
                        </span>
                    </td>
                    <td>${recommendedStock}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm" 
                               value="${recommendedStock}" min="0" max="${item.max_level * 2}"
                               data-item-id="${item.id}">
                    </td>
                </tr>
            `;
        }).join('');
        
        const modal = new bootstrap.Modal(document.getElementById('bulkRestockModal'));
        modal.show();
    }

    function refreshStockData() {
        const icon = document.getElementById('refreshBtn').querySelector('i');
        icon.classList.add('fa-spin');
        
        setTimeout(() => {
            loadStockItems();
            updateStatistics();
            generateAlerts();
            icon.classList.remove('fa-spin');
            showToast('Stock data refreshed successfully!', 'success');
        }, 1000);
    }

    function exportStockData() {
        const csvData = convertToCSV(filteredItems);
        downloadCSV(csvData, 'stock-levels.csv');
    }

    function convertToCSV(items) {
        const headers = ['ID', 'Name', 'SKU', 'Category', 'Current Stock', 'Min Level', 'Max Level', 'Status', 'Last Restocked', 'Cost Per Unit'];
        const rows = items.map(item => [
            item.id,
            item.name,
            item.sku,
            item.category,
            item.current_stock,
            item.min_level,
            item.max_level,
            getStockStatus(item),
            new Date(item.last_restocked).toLocaleDateString(),
            item.cost_per_unit.toFixed(2)
        ]);

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

    function showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }

    // Global functions
    window.goToPage = function(page) {
        const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            loadStockItems();
        }
    };

    window.quickStockUpdate = function(itemId) {
        const item = allStockItems.find(i => i.id === itemId);
        if (!item) return;

        document.getElementById('quickStockProductName').textContent = item.name;
        document.getElementById('quickCurrentStock').textContent = item.current_stock;
        document.getElementById('quickNewStock').value = item.current_stock;
        document.getElementById('quickStockReason').value = 'adjustment';
        document.getElementById('quickStockNotes').value = '';

        const modal = new bootstrap.Modal(document.getElementById('quickStockModal'));
        modal.show();

        document.getElementById('saveQuickStockBtn').dataset.itemId = itemId;
    };

    window.saveQuickStock = function() {
        const itemId = parseInt(document.getElementById('saveQuickStockBtn').dataset.itemId);
        const newStock = parseInt(document.getElementById('quickNewStock').value);
        const reason = document.getElementById('quickStockReason').value;
        const notes = document.getElementById('quickStockNotes').value;

        const item = allStockItems.find(i => i.id === itemId);
        if (item && newStock >= 0) {
            const oldStock = item.current_stock;
            item.current_stock = newStock;
            item.last_restocked = new Date().toISOString();

            // Log the change (in real app, send to backend)
            console.log(`Stock updated: ${item.name} from ${oldStock} to ${newStock}. Reason: ${reason}`);

            // Update displays
            loadStockItems();
            updateStatistics();
            generateAlerts();

            const modal = bootstrap.Modal.getInstance(document.getElementById('quickStockModal'));
            modal.hide();

            showToast(`${item.name} stock updated successfully!`, 'success');
        }
    };

    window.restockItem = function(itemId) {
        const item = allStockItems.find(i => i.id === itemId);
        if (!item) return;

        const recommendedStock = Math.ceil(item.max_level * 0.8);
        item.current_stock = recommendedStock;
        item.last_restocked = new Date().toISOString();

        loadStockItems();
        updateStatistics();
        generateAlerts();

        showToast(`${item.name} restocked to ${recommendedStock} units!`, 'success');
    };

    window.restockOutOfStockItems = function() {
        const outOfStockItems = allStockItems.filter(item => getStockStatus(item) === 'out-of-stock');
        
        outOfStockItems.forEach(item => {
            item.current_stock = Math.ceil(item.max_level * 0.8);
            item.last_restocked = new Date().toISOString();
        });

        loadStockItems();
        updateStatistics();
        generateAlerts();

        showToast(`${outOfStockItems.length} out-of-stock items restocked!`, 'success');
    };

    window.saveBulkRestock = function() {
        const checkedItems = document.querySelectorAll('input[name="bulkItems"]:checked');
        let updatedCount = 0;

        checkedItems.forEach(checkbox => {
            const itemId = parseInt(checkbox.value);
            const newStockInput = document.querySelector(`input[data-item-id="${itemId}"]`);
            const newStock = parseInt(newStockInput.value);
            
            const item = allStockItems.find(i => i.id === itemId);
            if (item && newStock >= 0) {
                item.current_stock = newStock;
                item.last_restocked = new Date().toISOString();
                updatedCount++;
            }
        });

        if (updatedCount > 0) {
            loadStockItems();
            updateStatistics();
            generateAlerts();

            const modal = bootstrap.Modal.getInstance(document.getElementById('bulkRestockModal'));
            modal.hide();

            showToast(`${updatedCount} items restocked successfully!`, 'success');
        }
    };

    window.restockLowStockItems = function() {
        const lowStockItems = allStockItems.filter(item => 
            getStockStatus(item) === 'low-stock' || getStockStatus(item) === 'out-of-stock'
        );
        
        lowStockItems.forEach(item => {
            item.current_stock = Math.ceil(item.max_level * 0.8);
            item.last_restocked = new Date().toISOString();
        });

        loadStockItems();
        updateStatistics();
        generateAlerts();

        const modal = bootstrap.Modal.getInstance(document.getElementById('bulkRestockModal'));
        modal.hide();

        showToast(`${lowStockItems.length} low stock items restocked!`, 'success');
    };

    window.viewStockHistory = function(itemId) {
        const item = allStockItems.find(i => i.id === itemId);
        if (!item) return;

        // In a real application, you would fetch stock history from backend
        showToast(`Viewing stock history for ${item.name} (feature coming soon)`, 'info');
    };

    window.viewOverstockedItems = function() {
        document.getElementById('stockFilter').value = 'overstocked';
        applyFilters();
        showToast('Filtered to show overstocked items', 'info');
    };

    window.saveAlertSettings = function() {
        const lowThreshold = document.getElementById('lowStockThreshold').value;
        const overstockThreshold = document.getElementById('overstockThreshold').value;
        const emailAlerts = document.getElementById('emailAlerts').checked;
        const pushNotifications = document.getElementById('pushNotifications').checked;

        // In a real application, save to backend
        console.log('Alert settings saved:', {
            lowThreshold,
            overstockThreshold,
            emailAlerts,
            pushNotifications
        });

        const modal = bootstrap.Modal.getInstance(document.getElementById('alertSettingsModal'));
        modal.hide();

        showToast('Alert settings saved successfully!', 'success');
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