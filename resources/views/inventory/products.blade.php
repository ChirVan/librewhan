
@extends('layouts.app')

@section('title', 'Products Management - Kalibrewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Products Management</h3>
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
                    <a href="#">Products</a>
                </li>
            </ul>
        </div>

        <!-- Product Statistics -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Products</p>
                                    <h4 class="card-title" id="total-products">24</h4>
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
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Active Products</p>
                                    <h4 class="card-title" id="active-products">21</h4>
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
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Low Stock</p>
                                    <h4 class="card-title" id="low-stock">5</h4>
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
                                    <i class="fas fa-layer-group"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Categories</p>
                                    <h4 class="card-title" id="total-categories">4</h4>
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
                                    <label class="form-label small">Status</label>
                                    <select class="form-control form-control-sm" id="statusFilter">
                                        <option value="all">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="low-stock">Low Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Sort By</label>
                                    <select class="form-control form-control-sm" id="sortFilter">
                                        <option value="name">Name A-Z</option>
                                        <option value="name-desc">Name Z-A</option>
                                        <option value="price">Price Low-High</option>
                                        <option value="price-desc">Price High-Low</option>
                                        <option value="stock">Stock Low-High</option>
                                        <option value="stock-desc">Stock High-Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2 mt-4">
                                    <button class="btn btn-primary btn-sm" id="addProductBtn">
                                        <i class="fas fa-plus"></i> Add Product
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

        <!-- Products Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Products List</h4>
                            <span class="badge badge-primary" id="productCount">Showing 24 products</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Last Updated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <!-- Products will be loaded here -->
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

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="productName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SKU *</label>
                                <input type="text" class="form-control" id="productSKU" name="sku" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category *</label>
                                <select class="form-control" id="productCategory" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Coffee">Coffee</option>
                                    <option value="Pastry">Pastry</option>
                                    <option value="Food">Food</option>
                                    <option value="Beverage">Beverage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Stock Quantity *</label>
                                <input type="number" class="form-control" id="productStock" name="stock" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Low Stock Alert</label>
                                <input type="number" class="form-control" id="productLowStock" name="low_stock_alert" min="0" value="10">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" id="productStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Customizable</label>
                                <select class="form-control" id="productCustomizable" name="customizable">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Product:</strong> <span id="stockProductName"></span>
                </div>
                <div class="mb-3">
                    <strong>Current Stock:</strong> <span id="currentStock"></span>
                </div>
                <div class="form-group">
                    <label class="form-label">Action Type</label>
                    <select class="form-control" id="stockAction">
                        <option value="add">Add Stock</option>
                        <option value="remove">Remove Stock</option>
                        <option value="set">Set Stock</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="stockQuantity" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason</label>
                    <textarea class="form-control" id="stockReason" rows="2" placeholder="Optional reason for stock change"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateStockBtn">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <div class="alert alert-warning">
                    <strong>Product:</strong> <span id="deleteProductName"></span><br>
                    <strong>SKU:</strong> <span id="deleteProductSKU"></span><br>
                    <small>This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Product</button>
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

.product-image {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.category-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.category-coffee {
    background: #8B4513;
    color: white;
}

.category-pastry {
    background: #ffc107;
    color: #856404;
}

.category-food {
    background: #28a745;
    color: white;
}

.category-beverage {
    background: #17a2b8;
    color: white;
}

.status-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.stock-badge {
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.stock-normal {
    background: #d4edda;
    color: #155724;
}

.stock-low {
    background: #fff3cd;
    color: #856404;
}

.stock-out {
    background: #f8d7da;
    color: #721c24;
}

.product-price {
    font-weight: 600;
    color: #1572e8;
}

.btn-action {
    font-size: 0.75rem;
    padding: 4px 8px;
    margin: 0 2px;
}

.product-name {
    font-weight: 600;
}

.product-sku {
    font-size: 0.75rem;
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample products data - replace with actual API calls
    let allProducts = [
        {
            id: 1,
            name: 'Espresso',
            sku: 'ESP-001',
            category: 'Coffee',
            price: 3.50,
            stock: 45,
            low_stock_alert: 10,
            status: 'active',
            customizable: true,
            description: 'Rich and bold espresso shot',
            updated_at: '2024-01-15 09:00:00'
        },
        {
            id: 2,
            name: 'Cappuccino',
            sku: 'CAP-001',
            category: 'Coffee',
            price: 4.25,
            stock: 32,
            low_stock_alert: 10,
            status: 'active',
            customizable: true,
            description: 'Espresso with steamed milk foam',
            updated_at: '2024-01-15 08:45:00'
        },
        {
            id: 3,
            name: 'Latte',
            sku: 'LAT-001',
            category: 'Coffee',
            price: 4.75,
            stock: 28,
            low_stock_alert: 10,
            status: 'active',
            customizable: true,
            description: 'Espresso with steamed milk',
            updated_at: '2024-01-15 08:30:00'
        },
        {
            id: 4,
            name: 'Americano',
            sku: 'AME-001',
            category: 'Coffee',
            price: 3.25,
            stock: 8,
            low_stock_alert: 10,
            status: 'active',
            customizable: true,
            description: 'Espresso with hot water',
            updated_at: '2024-01-15 08:15:00'
        },
        {
            id: 5,
            name: 'Croissant',
            sku: 'CRO-001',
            category: 'Pastry',
            price: 2.95,
            stock: 15,
            low_stock_alert: 5,
            status: 'active',
            customizable: false,
            description: 'Buttery flaky pastry',
            updated_at: '2024-01-15 07:00:00'
        },
        {
            id: 6,
            name: 'Blueberry Muffin',
            sku: 'MUF-001',
            category: 'Pastry',
            price: 3.45,
            stock: 22,
            low_stock_alert: 5,
            status: 'active',
            customizable: false,
            description: 'Fresh blueberry muffin',
            updated_at: '2024-01-15 06:45:00'
        },
        {
            id: 7,
            name: 'Caesar Salad',
            sku: 'SAL-001',
            category: 'Food',
            price: 8.95,
            stock: 12,
            low_stock_alert: 5,
            status: 'active',
            customizable: false,
            description: 'Fresh romaine with caesar dressing',
            updated_at: '2024-01-15 06:30:00'
        },
        {
            id: 8,
            name: 'Club Sandwich',
            sku: 'SAN-001',
            category: 'Food',
            price: 9.75,
            stock: 0,
            low_stock_alert: 5,
            status: 'inactive',
            customizable: false,
            description: 'Triple-decker club sandwich',
            updated_at: '2024-01-15 06:15:00'
        }
    ];

    let filteredProducts = [...allProducts];
    let currentPage = 1;
    let productsPerPage = 25;
    let editingProductId = null;

    // Initialize page
    loadProducts();
    updateStatistics();
    setupEventListeners();

    function setupEventListeners() {
        // Search and filters
        document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);
        document.getElementById('perPage').addEventListener('change', function() {
            productsPerPage = parseInt(this.value);
            currentPage = 1;
            loadProducts();
        });

        // Action buttons
        document.getElementById('addProductBtn').addEventListener('click', showAddProductModal);
        document.getElementById('exportBtn').addEventListener('click', exportProducts);
        document.getElementById('refreshBtn').addEventListener('click', refreshProducts);
        document.getElementById('saveProductBtn').addEventListener('click', saveProduct);
        document.getElementById('updateStockBtn').addEventListener('click', updateStock);
        document.getElementById('confirmDeleteBtn').addEventListener('click', deleteProduct);
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const sortFilter = document.getElementById('sortFilter').value;

        // Filter products
        filteredProducts = allProducts.filter(product => {
            const matchesSearch = product.name.toLowerCase().includes(searchTerm) ||
                                product.sku.toLowerCase().includes(searchTerm);
            const matchesCategory = categoryFilter === 'all' || product.category === categoryFilter;
            const matchesStatus = statusFilter === 'all' || 
                                (statusFilter === 'low-stock' && product.stock <= product.low_stock_alert) ||
                                (statusFilter !== 'low-stock' && product.status === statusFilter);

            return matchesSearch && matchesCategory && matchesStatus;
        });

        // Sort products
        sortProducts(sortFilter);

        // Reset to first page
        currentPage = 1;
        loadProducts();
        updateStatistics();
    }

    function sortProducts(sortBy) {
        switch(sortBy) {
            case 'name':
                filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'name-desc':
                filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
                break;
            case 'price':
                filteredProducts.sort((a, b) => a.price - b.price);
                break;
            case 'price-desc':
                filteredProducts.sort((a, b) => b.price - a.price);
                break;
            case 'stock':
                filteredProducts.sort((a, b) => a.stock - b.stock);
                break;
            case 'stock-desc':
                filteredProducts.sort((a, b) => b.stock - a.stock);
                break;
        }
    }

    function loadProducts() {
        const tbody = document.getElementById('productsTableBody');
        const startIndex = (currentPage - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const productsToShow = filteredProducts.slice(startIndex, endIndex);

        if (productsToShow.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-box fa-2x text-muted mb-2"></i>
                        <br>
                        <span class="text-muted">No products found</span>
                    </td>
                </tr>
            `;
        } else {
            tbody.innerHTML = productsToShow.map(product => createProductRow(product)).join('');
        }

        updateProductCount();
        generatePagination();
    }

    function createProductRow(product) {
        const stockStatus = getStockStatus(product);
        const categoryIcon = getCategoryIcon(product.category);
        
        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="product-image me-3">
                            ${categoryIcon}
                        </div>
                        <div>
                            <div class="product-name">${product.name}</div>
                            <div class="product-sku">${product.sku}</div>
                        </div>
                    </div>
                </td>
                <td>${product.sku}</td>
                <td>
                    <span class="badge category-badge category-${product.category.toLowerCase()}">
                        ${product.category}
                    </span>
                </td>
                <td>
                    <span class="product-price">$${product.price.toFixed(2)}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="me-2">${product.stock}</span>
                        <span class="badge stock-badge stock-${stockStatus.class}">
                            ${stockStatus.text}
                        </span>
                    </div>
                </td>
                <td>
                    <span class="badge status-badge status-${product.status}">
                        ${product.status.toUpperCase()}
                    </span>
                </td>
                <td>
                    <div>${new Date(product.updated_at).toLocaleDateString()}</div>
                    <small class="text-muted">${new Date(product.updated_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-primary btn-action" onclick="editProduct(${product.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-info btn-action" onclick="updateStockModal(${product.id})" title="Update Stock">
                            <i class="fas fa-boxes"></i>
                        </button>
                        <button class="btn btn-outline-${product.status === 'active' ? 'warning' : 'success'} btn-action" onclick="toggleStatus(${product.id})" title="${product.status === 'active' ? 'Deactivate' : 'Activate'}">
                            <i class="fas fa-${product.status === 'active' ? 'pause' : 'play'}"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-action" onclick="deleteProductModal(${product.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function getStockStatus(product) {
        if (product.stock === 0) {
            return { class: 'out', text: 'Out of Stock' };
        } else if (product.stock <= product.low_stock_alert) {
            return { class: 'low', text: 'Low Stock' };
        } else {
            return { class: 'normal', text: 'In Stock' };
        }
    }

    function getCategoryIcon(category) {
        const icons = {
            'Coffee': '<i class="fas fa-coffee text-brown"></i>',
            'Pastry': '<i class="fas fa-cookie-bite text-warning"></i>',
            'Food': '<i class="fas fa-hamburger text-success"></i>',
            'Beverage': '<i class="fas fa-glass-water text-info"></i>'
        };
        return icons[category] || '<i class="fas fa-box"></i>';
    }

    function updateProductCount() {
        const total = filteredProducts.length;
        const startIndex = (currentPage - 1) * productsPerPage + 1;
        const endIndex = Math.min(currentPage * productsPerPage, total);
        
        document.getElementById('productCount').textContent = 
            total > 0 ? `Showing ${startIndex}-${endIndex} of ${total} products` : 'No products found';
    }

    function updateStatistics() {
        const totalProducts = allProducts.length;
        const activeProducts = allProducts.filter(p => p.status === 'active').length;
        const lowStockProducts = allProducts.filter(p => p.stock <= p.low_stock_alert).length;
        const categories = [...new Set(allProducts.map(p => p.category))].length;

        document.getElementById('total-products').textContent = totalProducts;
        document.getElementById('active-products').textContent = activeProducts;
        document.getElementById('low-stock').textContent = lowStockProducts;
        document.getElementById('total-categories').textContent = categories;
    }

    function generatePagination() {
        const pagination = document.getElementById('pagination');
        const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
        
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

    // Modal functions
    function showAddProductModal() {
        editingProductId = null;
        document.getElementById('productModalTitle').textContent = 'Add New Product';
        document.getElementById('productForm').reset();
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function saveProduct() {
        const form = document.getElementById('productForm');
        const formData = new FormData(form);
        
        // Basic validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const productData = {
            name: formData.get('name'),
            sku: formData.get('sku'),
            category: formData.get('category'),
            price: parseFloat(formData.get('price')),
            stock: parseInt(formData.get('stock')),
            low_stock_alert: parseInt(formData.get('low_stock_alert')),
            description: formData.get('description'),
            status: formData.get('status'),
            customizable: formData.get('customizable') === '1',
            updated_at: new Date().toISOString()
        };

        if (editingProductId) {
            // Update existing product
            const productIndex = allProducts.findIndex(p => p.id === editingProductId);
            if (productIndex !== -1) {
                allProducts[productIndex] = { ...allProducts[productIndex], ...productData };
                alert('Product updated successfully!');
            }
        } else {
            // Add new product
            const newId = Math.max(...allProducts.map(p => p.id)) + 1;
            allProducts.push({ id: newId, ...productData });
            alert('Product added successfully!');
        }

        // Refresh the display
        applyFilters();
        updateStatistics();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
        modal.hide();
    }

    function refreshProducts() {
        // Simulate refresh with loading animation
        const icon = document.getElementById('refreshBtn').querySelector('i');
        icon.classList.add('fa-spin');
        
        setTimeout(() => {
            loadProducts();
            updateStatistics();
            icon.classList.remove('fa-spin');
            alert('Products refreshed successfully!');
        }, 1000);
    }

    function exportProducts() {
        const csvData = convertToCSV(filteredProducts);
        downloadCSV(csvData, 'products.csv');
    }

    function convertToCSV(products) {
        const headers = ['ID', 'Name', 'SKU', 'Category', 'Price', 'Stock', 'Low Stock Alert', 'Status', 'Customizable', 'Description'];
        const rows = products.map(product => [
            product.id,
            product.name,
            product.sku,
            product.category,
            product.price,
            product.stock,
            product.low_stock_alert,
            product.status,
            product.customizable ? 'Yes' : 'No',
            product.description || ''
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

    // Global functions for button actions
    window.goToPage = function(page) {
        const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            loadProducts();
        }
    };

    window.editProduct = function(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        editingProductId = productId;
        document.getElementById('productModalTitle').textContent = 'Edit Product';
        
        // Populate form fields
        document.getElementById('productName').value = product.name;
        document.getElementById('productSKU').value = product.sku;
        document.getElementById('productCategory').value = product.category;
        document.getElementById('productPrice').value = product.price;
        document.getElementById('productStock').value = product.stock;
        document.getElementById('productLowStock').value = product.low_stock_alert;
        document.getElementById('productDescription').value = product.description || '';
        document.getElementById('productStatus').value = product.status;
        document.getElementById('productCustomizable').value = product.customizable ? '1' : '0';

        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    };

    window.updateStockModal = function(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        document.getElementById('stockProductName').textContent = product.name;
        document.getElementById('currentStock').textContent = product.stock;
        document.getElementById('stockAction').value = 'add';
        document.getElementById('stockQuantity').value = '';
        document.getElementById('stockReason').value = '';

        const modal = new bootstrap.Modal(document.getElementById('stockModal'));
        modal.show();

        // Store product ID for later use
        document.getElementById('updateStockBtn').dataset.productId = productId;
    };

    window.updateStock = function() {
        const productId = parseInt(document.getElementById('updateStockBtn').dataset.productId);
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        const action = document.getElementById('stockAction').value;
        const quantity = parseInt(document.getElementById('stockQuantity').value);
        const reason = document.getElementById('stockReason').value;

        if (!quantity || quantity < 0) {
            alert('Please enter a valid quantity');
            return;
        }

        let newStock = product.stock;
        switch(action) {
            case 'add':
                newStock += quantity;
                break;
            case 'remove':
                newStock = Math.max(0, newStock - quantity);
                break;
            case 'set':
                newStock = quantity;
                break;
        }

        product.stock = newStock;
        product.updated_at = new Date().toISOString();

        // Refresh display
        loadProducts();
        updateStatistics();

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('stockModal'));
        modal.hide();

        alert(`Stock updated successfully! New stock: ${newStock}`);
    };

    window.toggleStatus = function(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        product.status = product.status === 'active' ? 'inactive' : 'active';
        product.updated_at = new Date().toISOString();

        loadProducts();
        updateStatistics();

        alert(`Product ${product.status === 'active' ? 'activated' : 'deactivated'} successfully!`);
    };

    window.deleteProductModal = function(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        document.getElementById('deleteProductName').textContent = product.name;
        document.getElementById('deleteProductSKU').textContent = product.sku;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();

        // Store product ID for later use
        document.getElementById('confirmDeleteBtn').dataset.productId = productId;
    };

    window.deleteProduct = function() {
        const productId = parseInt(document.getElementById('confirmDeleteBtn').dataset.productId);
        
        // Remove product from array
        allProducts = allProducts.filter(p => p.id !== productId);
        
        // Refresh display
        applyFilters();
        updateStatistics();

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();

        alert('Product deleted successfully!');
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

<style>
.text-brown {
    color: #8B4513 !important;
}
</style>
@endsection