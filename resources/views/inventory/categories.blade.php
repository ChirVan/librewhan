
@extends('layouts.app')

@section('title', 'Categories Management - Kalibrewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Categories Management</h3>
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
                    <a href="#">Categories</a>
                </li>
            </ul>
        </div>

        <!-- Filters and Actions -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Search Categories</label>
                                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Category name, description...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Status</label>
                                    <select class="form-control form-control-sm" id="statusFilter">
                                        <option value="all">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="form-label small">Sort By</label>
                                    <select class="form-control form-control-sm" id="sortFilter">
                                        <option value="name">Name A-Z</option>
                                        <option value="name-desc">Name Z-A</option>
                                        <option value="products">Most Products</option>
                                        <option value="created">Newest First</option>
                                        <option value="created-desc">Oldest First</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2 mt-4">
                                    <button class="btn btn-primary btn-sm" id="addCategoryBtn">
                                        <i class="fas fa-plus"></i> Add Category
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

        <!-- Categories Grid -->
        <div class="row" id="categoriesContainer">
            <!-- Categories will be loaded here -->
        </div>

        <!-- Categories Table View (Alternative) -->
        <div class="row" id="categoriesTableContainer" style="display: none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Categories List</h4>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="gridViewBtn">
                                    <i class="fas fa-th-large"></i> Grid
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="tableViewBtn">
                                    <i class="fas fa-list"></i> Table
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Products</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="categoriesTableBody">
                                    <!-- Table rows will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category Slug *</label>
                                <input type="text" class="form-control" id="categorySlug" name="slug" required readonly>
                                <small class="form-text text-muted">Auto-generated from name</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="categoryDescription" name="description" rows="3" placeholder="Brief description of this category"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Icon</label>
                                <select class="form-control" id="categoryIcon" name="icon">
                                    <option value="fas fa-coffee">☕ Coffee</option>
                                    <option value="fas fa-cookie-bite">🍪 Pastry</option>
                                    <option value="fas fa-hamburger">🍔 Food</option>
                                    <option value="fas fa-glass-water">🥤 Beverage</option>
                                    <option value="fas fa-utensils">🍽️ Utensils</option>
                                    <option value="fas fa-birthday-cake">🎂 Dessert</option>
                                    <option value="fas fa-seedling">🌱 Healthy</option>
                                    <option value="fas fa-fire">🔥 Spicy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Color Theme</label>
                                <select class="form-control" id="categoryColor" name="color">
                                    <option value="#8B4513">Brown (Coffee)</option>
                                    <option value="#ffc107">Yellow (Pastry)</option>
                                    <option value="#28a745">Green (Food)</option>
                                    <option value="#17a2b8">Blue (Beverage)</option>
                                    <option value="#dc3545">Red</option>
                                    <option value="#6f42c1">Purple</option>
                                    <option value="#fd7e14">Orange</option>
                                    <option value="#20c997">Teal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" id="categoryStatus" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="categoryOrder" name="display_order" min="0" value="0">
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="categoryFeatured" name="featured">
                                <label class="form-check-label" for="categoryFeatured">
                                    Featured Category
                                </label>
                                <small class="form-text text-muted d-block">Featured categories appear prominently in the menu</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
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
                <p>Are you sure you want to delete this category?</p>
                <div class="alert alert-warning">
                    <strong>Category:</strong> <span id="deleteCategoryName"></span><br>
                    <strong>Products:</strong> <span id="deleteCategoryProducts"></span> products will be affected<br>
                    <small>This action cannot be undone. Consider deactivating instead.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning me-2" id="deactivateCategoryBtn">Deactivate Instead</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Category</button>
            </div>
        </div>
    </div>
</div>

<!-- View Products Modal -->
<div class="modal fade" id="productsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productsModalTitle">Category Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="productsModalContent">
                <!-- Products list will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addProductToCategory">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.category-card {
    border: 2px solid #e3e6f0;
    border-radius: 12px;
    transition: all 0.2s ease;
    margin-bottom: 20px;
    overflow: hidden;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.category-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e3e6f0;
    text-align: center;
}

.category-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 24px;
    color: white;
}

.category-name {
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 5px;
    color: #2c3e50;
}

.category-description {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0;
}

.category-body {
    padding: 20px;
}

.category-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-weight: 700;
    font-size: 1.5rem;
    color: #1572e8;
    display: block;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #e3e6f0;
}

.category-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.status-badge {
    font-size: 0.7rem;
    padding: 4px 8px;
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

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ffc107;
    color: #856404;
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.btn-action {
    font-size: 0.75rem;
    padding: 6px 10px;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    font-size: 0.85rem;
    vertical-align: middle;
}

.category-icon-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 14px;
    color: white;
}

.view-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.product-item {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.product-name {
    font-weight: 600;
}

.product-price {
    color: #1572e8;
    font-weight: 600;
}

.product-stock {
    font-size: 0.8rem;
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample categories data - replace with actual API calls
    let allCategories = [
        {
            id: 1,
            name: 'Coffee',
            slug: 'coffee',
            description: 'Premium coffee beverages including espresso, cappuccino, and specialty drinks',
            icon: 'fas fa-coffee',
            color: '#8B4513',
            status: 'active',
            display_order: 1,
            featured: true,
            products_count: 12,
            created_at: '2024-01-01 00:00:00',
            products: [
                { id: 1, name: 'Espresso', price: 3.50, stock: 45 },
                { id: 2, name: 'Cappuccino', price: 4.25, stock: 32 },
                { id: 3, name: 'Latte', price: 4.75, stock: 28 }
            ]
        },
        {
            id: 2,
            name: 'Pastry',
            slug: 'pastry',
            description: 'Fresh baked pastries, croissants, muffins, and sweet treats',
            icon: 'fas fa-cookie-bite',
            color: '#ffc107',
            status: 'active',
            display_order: 2,
            featured: true,
            products_count: 8,
            created_at: '2024-01-01 00:00:00',
            products: [
                { id: 5, name: 'Croissant', price: 2.95, stock: 15 },
                { id: 6, name: 'Blueberry Muffin', price: 3.45, stock: 22 }
            ]
        },
        {
            id: 3,
            name: 'Food',
            slug: 'food',
            description: 'Hearty meals, sandwiches, salads, and main dishes',
            icon: 'fas fa-hamburger',
            color: '#28a745',
            status: 'active',
            display_order: 3,
            featured: false,
            products_count: 6,
            created_at: '2024-01-01 00:00:00',
            products: [
                { id: 7, name: 'Caesar Salad', price: 8.95, stock: 12 },
                { id: 8, name: 'Club Sandwich', price: 9.75, stock: 0 }
            ]
        },
        {
            id: 4,
            name: 'Beverage',
            slug: 'beverage',
            description: 'Non-coffee drinks including teas, juices, and cold beverages',
            icon: 'fas fa-glass-water',
            color: '#17a2b8',
            status: 'active',
            display_order: 4,
            featured: false,
            products_count: 4,
            created_at: '2024-01-01 00:00:00',
            products: [
                { id: 9, name: 'Green Tea', price: 2.75, stock: 35 },
                { id: 10, name: 'Orange Juice', price: 3.25, stock: 18 }
            ]
        }
    ];

    let filteredCategories = [...allCategories];
    let editingCategoryId = null;
    let currentView = 'grid'; // 'grid' or 'table'

    // Initialize page
    loadCategories();
    updateStatistics();
    setupEventListeners();

    function setupEventListeners() {
        // Search and filters
        document.getElementById('searchInput').addEventListener('input', debounce(applyFilters, 300));
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);

        // View toggle
        document.getElementById('gridViewBtn').addEventListener('click', () => switchView('grid'));
        document.getElementById('tableViewBtn').addEventListener('click', () => switchView('table'));

        // Action buttons
        document.getElementById('addCategoryBtn').addEventListener('click', showAddCategoryModal);
        document.getElementById('exportBtn').addEventListener('click', exportCategories);
        document.getElementById('refreshBtn').addEventListener('click', refreshCategories);
        document.getElementById('saveCategoryBtn').addEventListener('click', saveCategory);
        document.getElementById('confirmDeleteBtn').addEventListener('click', deleteCategory);
        document.getElementById('deactivateCategoryBtn').addEventListener('click', deactivateCategory);

        // Category name to slug auto-generation
        document.getElementById('categoryName').addEventListener('input', function() {
            const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            document.getElementById('categorySlug').value = slug;
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const sortFilter = document.getElementById('sortFilter').value;

        // Filter categories
        filteredCategories = allCategories.filter(category => {
            const matchesSearch = category.name.toLowerCase().includes(searchTerm) ||
                                category.description.toLowerCase().includes(searchTerm);
            const matchesStatus = statusFilter === 'all' || category.status === statusFilter;

            return matchesSearch && matchesStatus;
        });

        // Sort categories
        sortCategories(sortFilter);

        loadCategories();
        updateStatistics();
    }

    function sortCategories(sortBy) {
        switch(sortBy) {
            case 'name':
                filteredCategories.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'name-desc':
                filteredCategories.sort((a, b) => b.name.localeCompare(a.name));
                break;
            case 'products':
                filteredCategories.sort((a, b) => b.products_count - a.products_count);
                break;
            case 'created':
                filteredCategories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                break;
            case 'created-desc':
                filteredCategories.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                break;
        }
    }

    function switchView(view) {
        currentView = view;
        
        if (view === 'grid') {
            document.getElementById('categoriesContainer').style.display = 'flex';
            document.getElementById('categoriesTableContainer').style.display = 'none';
            document.getElementById('gridViewBtn').classList.add('active');
            document.getElementById('tableViewBtn').classList.remove('active');
        } else {
            document.getElementById('categoriesContainer').style.display = 'none';
            document.getElementById('categoriesTableContainer').style.display = 'block';
            document.getElementById('gridViewBtn').classList.remove('active');
            document.getElementById('tableViewBtn').classList.add('active');
        }
        
        loadCategories();
    }

    function loadCategories() {
        if (currentView === 'grid') {
            loadCategoriesGrid();
        } else {
            loadCategoriesTable();
        }
    }

    function loadCategoriesGrid() {
        const container = document.getElementById('categoriesContainer');
        
        if (filteredCategories.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No categories found</h5>
                        <p class="text-muted">No categories match your current filters.</p>
                    </div>
                </div>
            `;
            return;
        }

        container.innerHTML = filteredCategories.map(category => createCategoryCard(category)).join('');
    }

    function loadCategoriesTable() {
        const tbody = document.getElementById('categoriesTableBody');
        
        if (filteredCategories.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-layer-group fa-2x text-muted mb-2"></i>
                        <br>
                        <span class="text-muted">No categories found</span>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = filteredCategories.map(category => createCategoryRow(category)).join('');
    }

    function createCategoryCard(category) {
        return `
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card category-card">
                    ${category.featured ? '<span class="featured-badge">Featured</span>' : ''}
                    
                    <div class="category-header">
                        <div class="category-icon" style="background-color: ${category.color}">
                            <i class="${category.icon}"></i>
                        </div>
                        <div class="category-name">${category.name}</div>
                        <p class="category-description">${category.description}</p>
                    </div>
                    
                    <div class="category-body">
                        <div class="category-stats">
                            <div class="stat-item">
                                <span class="stat-number">${category.products_count}</span>
                                <span class="stat-label">Products</span>
                            </div>
                            <div class="stat-item">
                                <span class="badge status-badge status-${category.status}">
                                    ${category.status.toUpperCase()}
                                </span>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                Created ${new Date(category.created_at).toLocaleDateString()}
                            </small>
                        </div>
                    </div>
                    
                    <div class="category-footer">
                        <div class="category-actions">
                            <button class="btn btn-outline-primary btn-action" onclick="viewCategoryProducts(${category.id})" title="View Products">
                                <i class="fas fa-eye"></i> Products
                            </button>
                            <button class="btn btn-outline-info btn-action" onclick="editCategory(${category.id})" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-${category.status === 'active' ? 'warning' : 'success'} btn-action" onclick="toggleCategoryStatus(${category.id})" title="${category.status === 'active' ? 'Deactivate' : 'Activate'}">
                                <i class="fas fa-${category.status === 'active' ? 'pause' : 'play'}"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-action" onclick="deleteCategoryModal(${category.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function createCategoryRow(category) {
        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="category-icon-small" style="background-color: ${category.color}">
                            <i class="${category.icon}"></i>
                        </div>
                        <div>
                            <div class="fw-bold">${category.name}</div>
                            <small class="text-muted">${category.slug}</small>
                            ${category.featured ? '<span class="badge bg-warning text-dark ms-2">Featured</span>' : ''}
                        </div>
                    </div>
                </td>
                <td>
                    <div style="max-width: 200px;">
                        ${category.description}
                    </div>
                </td>
                <td>
                    <span class="fw-bold text-primary">${category.products_count}</span>
                </td>
                <td>
                    <span class="badge status-badge status-${category.status}">
                        ${category.status.toUpperCase()}
                    </span>
                </td>
                <td>
                    <div>${new Date(category.created_at).toLocaleDateString()}</div>
                    <small class="text-muted">${new Date(category.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-primary btn-action" onclick="viewCategoryProducts(${category.id})" title="View Products">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-info btn-action" onclick="editCategory(${category.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-${category.status === 'active' ? 'warning' : 'success'} btn-action" onclick="toggleCategoryStatus(${category.id})" title="${category.status === 'active' ? 'Deactivate' : 'Activate'}">
                            <i class="fas fa-${category.status === 'active' ? 'pause' : 'play'}"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-action" onclick="deleteCategoryModal(${category.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function updateStatistics() {
        const totalCategories = allCategories.length;
        const activeCategories = allCategories.filter(c => c.status === 'active').length;
        const totalProducts = allCategories.reduce((sum, c) => sum + c.products_count, 0);
        const popularCategory = allCategories.sort((a, b) => b.products_count - a.products_count)[0]?.name || 'N/A';

        document.getElementById('total-categories').textContent = totalCategories;
        document.getElementById('active-categories').textContent = activeCategories;
        document.getElementById('total-products').textContent = totalProducts;
        document.getElementById('popular-category').textContent = popularCategory;
    }

    // Modal functions
    function showAddCategoryModal() {
        editingCategoryId = null;
        document.getElementById('categoryModalTitle').textContent = 'Add New Category';
        document.getElementById('categoryForm').reset();
        document.getElementById('categoryOrder').value = allCategories.length + 1;
        const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
        modal.show();
    }

    function saveCategory() {
        const form = document.getElementById('categoryForm');
        const formData = new FormData(form);
        
        // Basic validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const categoryData = {
            name: formData.get('name'),
            slug: formData.get('slug'),
            description: formData.get('description'),
            icon: formData.get('icon'),
            color: formData.get('color'),
            status: formData.get('status'),
            display_order: parseInt(formData.get('display_order')),
            featured: formData.has('featured'),
            products_count: 0,
            created_at: new Date().toISOString()
        };

        if (editingCategoryId) {
            // Update existing category
            const categoryIndex = allCategories.findIndex(c => c.id === editingCategoryId);
            if (categoryIndex !== -1) {
                allCategories[categoryIndex] = { ...allCategories[categoryIndex], ...categoryData };
                alert('Category updated successfully!');
            }
        } else {
            // Add new category
            const newId = Math.max(...allCategories.map(c => c.id)) + 1;
            allCategories.push({ id: newId, ...categoryData, products: [] });
            alert('Category added successfully!');
        }

        // Refresh the display
        applyFilters();
        updateStatistics();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
        modal.hide();
    }

    function refreshCategories() {
        // Simulate refresh with loading animation
        const icon = document.getElementById('refreshBtn').querySelector('i');
        icon.classList.add('fa-spin');
        
        setTimeout(() => {
            loadCategories();
            updateStatistics();
            icon.classList.remove('fa-spin');
            alert('Categories refreshed successfully!');
        }, 1000);
    }

    function exportCategories() {
        const csvData = convertToCSV(filteredCategories);
        downloadCSV(csvData, 'categories.csv');
    }

    function convertToCSV(categories) {
        const headers = ['ID', 'Name', 'Slug', 'Description', 'Status', 'Products Count', 'Featured', 'Display Order', 'Created'];
        const rows = categories.map(category => [
            category.id,
            category.name,
            category.slug,
            category.description || '',
            category.status,
            category.products_count,
            category.featured ? 'Yes' : 'No',
            category.display_order,
            new Date(category.created_at).toLocaleDateString()
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
    window.editCategory = function(categoryId) {
        const category = allCategories.find(c => c.id === categoryId);
        if (!category) return;

        editingCategoryId = categoryId;
        document.getElementById('categoryModalTitle').textContent = 'Edit Category';
        
        // Populate form fields
        document.getElementById('categoryName').value = category.name;
        document.getElementById('categorySlug').value = category.slug;
        document.getElementById('categoryDescription').value = category.description || '';
        document.getElementById('categoryIcon').value = category.icon;
        document.getElementById('categoryColor').value = category.color;
        document.getElementById('categoryStatus').value = category.status;
        document.getElementById('categoryOrder').value = category.display_order;
        document.getElementById('categoryFeatured').checked = category.featured;

        const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
        modal.show();
    };

    window.viewCategoryProducts = function(categoryId) {
        const category = allCategories.find(c => c.id === categoryId);
        if (!category) return;

        document.getElementById('productsModalTitle').textContent = `${category.name} Products`;
        
        const content = `
            <div class="mb-3">
                <h6>${category.products_count} products in this category</h6>
            </div>
            <div class="products-list">
                ${category.products.map(product => `
                    <div class="product-item">
                        <div>
                            <div class="product-name">${product.name}</div>
                            <div class="product-stock">Stock: ${product.stock}</div>
                        </div>
                        <div class="product-price">$${product.price.toFixed(2)}</div>
                    </div>
                `).join('')}
            </div>
        `;
        
        document.getElementById('productsModalContent').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('productsModal'));
        modal.show();
    };

    window.toggleCategoryStatus = function(categoryId) {
        const category = allCategories.find(c => c.id === categoryId);
        if (!category) return;

        category.status = category.status === 'active' ? 'inactive' : 'active';

        loadCategories();
        updateStatistics();

        alert(`Category ${category.status === 'active' ? 'activated' : 'deactivated'} successfully!`);
    };

    window.deleteCategoryModal = function(categoryId) {
        const category = allCategories.find(c => c.id === categoryId);
        if (!category) return;

        document.getElementById('deleteCategoryName').textContent = category.name;
        document.getElementById('deleteCategoryProducts').textContent = category.products_count;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();

        // Store category ID for later use
        document.getElementById('confirmDeleteBtn').dataset.categoryId = categoryId;
        document.getElementById('deactivateCategoryBtn').dataset.categoryId = categoryId;
    };

    window.deleteCategory = function() {
        const categoryId = parseInt(document.getElementById('confirmDeleteBtn').dataset.categoryId);
        
        // Remove category from array
        allCategories = allCategories.filter(c => c.id !== categoryId);
        
        // Refresh display
        applyFilters();
        updateStatistics();

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        modal.hide();

        alert('Category deleted successfully!');
    };

    window.deactivateCategory = function() {
        const categoryId = parseInt(document.getElementById('deactivateCategoryBtn').dataset.categoryId);
        const category = allCategories.find(c => c.id === categoryId);
        
        if (category) {
            category.status = 'inactive';
            
            // Refresh display
            loadCategories();
            updateStatistics();

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();

            alert('Category deactivated successfully!');
        }
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