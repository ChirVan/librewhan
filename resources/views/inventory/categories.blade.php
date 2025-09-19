@extends('layouts.app')

@section('title', 'Categories Management - Librewhan Cafe')

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

        
        <!-- Category Statistics -->
        <div class="row mb-4" id="category-stats-row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-layer-group"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Total Categories</p>
                            <h4 class="card-title mb-0" id="total-categories">0</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Active</p>
                            <h4 class="card-title mb-0" id="active-categories">0</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Products</p>
                            <h4 class="card-title mb-0" id="total-products">0</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card card-stats card-round h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <p class="card-category mb-0 small text-uppercase">Top Category</p>
                            <h4 class="card-title mb-0" id="popular-category">—</h4>
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
                                    <option value="fas fa-coffee" selected>☕ Coffee</option>
                                    <option value="fas fa-leaf">🍃 Fruit Tea</option>
                                    <option value="fas fa-blender">🧊 Frappe</option>
                                    <option value="fas fa-glass-whiskey">🧋 Milk Tea</option>
                                    <option value="fas fa-utensils">🍽️ Snacks</option>
                                </select>
                                <small class="form-text text-muted">Choose one of the allowed category icons.</small>
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
.icon-emoji {
    font-size: 28px;
    line-height: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    let allCategories = [];
    let filteredCategories = [];
    let editingCategoryId = null;
    let currentView = 'grid';

    const iconEmoji = {
        'fas fa-coffee':'☕',
        'fas fa-leaf':'🍃',
        'fas fa-blender':'🧊',
        'fas fa-glass-whiskey':'🧋',
        'fas fa-utensils':'🍽️',
        'fas fa-mug-hot':'🧋', // legacy
        'fas fa-ice-cream':'🧊', // legacy
        'fas fa-cookie-bite':'🍽️' // legacy snacks
    };

    loadFromServer();
    setupEventListeners();

    function loadFromServer() {
        const params = new URLSearchParams({
            search: document.getElementById('searchInput').value,
            status: document.getElementById('statusFilter').value,
            sort: document.getElementById('sortFilter').value
        });
        fetch("/inventory/categories/data?" + params.toString(), {
            headers: { 'Accept':'application/json' }
        })
        .then(r=>r.json())
        .then(json=>{
            allCategories = json.data;
            filteredCategories = [...allCategories];
            loadCategories();
            updateStatistics();
        })
        .catch(()=> alert('Failed to load categories'));
    }

    function setupEventListeners() {
        document.getElementById('searchInput').addEventListener('input', debounce(()=>{loadFromServer();},300));
        document.getElementById('statusFilter').addEventListener('change', loadFromServer);
        document.getElementById('sortFilter').addEventListener('change', loadFromServer);

        document.getElementById('addCategoryBtn').addEventListener('click', showAddCategoryModal);
        document.getElementById('exportBtn').addEventListener('click', ()=> window.location = "{{ route('inventory.categories.export') }}");
        document.getElementById('refreshBtn').addEventListener('click', ()=> loadFromServer());
        document.getElementById('saveCategoryBtn').addEventListener('click', saveCategory);

        document.getElementById('gridViewBtn').addEventListener('click', ()=> switchView('grid'));
        document.getElementById('tableViewBtn').addEventListener('click', ()=> switchView('table'));

        document.getElementById('categoryName').addEventListener('input', function() {
            const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
            document.getElementById('categorySlug').value = slug;
        });
    // Icon preview removed per request; select remains functional
    }

    function switchView(v){
        currentView = v;
        if (v==='grid') {
            document.getElementById('categoriesContainer').style.display='flex';
            document.getElementById('categoriesTableContainer').style.display='none';
        } else {
            document.getElementById('categoriesContainer').style.display='none';
            document.getElementById('categoriesTableContainer').style.display='block';
        }
        loadCategories();
    }

    function loadCategories() {
        if (currentView==='grid') loadCategoriesGrid(); else loadCategoriesTable();
    }

    function loadCategoriesGrid() {
        const container = document.getElementById('categoriesContainer');
        if (!allCategories.length) {
            container.innerHTML = `<div class="col-12 text-center py-5 text-muted">No categories</div>`;
            return;
        }
        container.innerHTML = allCategories.map(c=>createCategoryCard(c)).join('');
    }

    function loadCategoriesTable() {
        const tbody = document.getElementById('categoriesTableBody');
        if (!allCategories.length) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">No categories</td></tr>`;
            return;
        }
        tbody.innerHTML = allCategories.map(c=>createCategoryRow(c)).join('');
    }

    function updateStatistics() {
    const tc = document.getElementById('total-categories');
    if (tc) tc.textContent = allCategories.length;
    const ac = document.getElementById('active-categories');
    if (ac) ac.textContent = allCategories.filter(c=>c.status==='active').length;
    const tp = document.getElementById('total-products');
    if (tp) tp.textContent = allCategories.reduce((s,c)=>s + (c.products_count||0),0);
    const popular = [...allCategories].sort((a,b)=>(b.products_count||0)-(a.products_count||0))[0];
    const pc = document.getElementById('popular-category');
    if (pc) pc.textContent = popular ? popular.name : 'N/A';
    }

    function showAddCategoryModal() {
        editingCategoryId = null;
        document.getElementById('categoryForm').reset();
        document.getElementById('categoryModalTitle').textContent = 'Add New Category';
        new bootstrap.Modal(document.getElementById('categoryModal')).show();
    }

    function saveCategory() {
        const form = document.getElementById('categoryForm');
        if (!form.reportValidity()) return;

        const fd = new FormData(form);
        const payload = Object.fromEntries(fd.entries());
        payload.featured = fd.get('featured') ? 1 : 0;

        const method = editingCategoryId ? 'PUT' : 'POST';
        const url = editingCategoryId
            ? "{{ url('/inventory/categories') }}/" + editingCategoryId
            : "{{ route('inventory.categories.store') }}";

        fetch(url, {
            method: method,
            headers: {
                'Accept':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type':'application/json'
            },
            body: JSON.stringify(payload)
        }).then(r=>r.json())
          .then(resp=>{
            if (resp.success) {
                loadFromServer();
                bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
                alert('Saved');
            } else {
                     if (resp.errors) {
                         const messages = Object.values(resp.errors).flat().join('\n');
                         alert(messages);
                     } else {
                         alert(resp.message || 'Error');
                     }
            }
          }).catch(()=> alert('Save failed'));
    }

    window.editCategory = function(id) {
        fetch("{{ url('/inventory/categories') }}/"+id, {headers:{'Accept':'application/json'}})
            .then(r=>r.json())
            .then(({category})=>{
                editingCategoryId = category.id;
                document.getElementById('categoryModalTitle').textContent = 'Edit Category';
                document.getElementById('categoryName').value = category.name;
                document.getElementById('categorySlug').value = category.slug;
                document.getElementById('categoryDescription').value = category.description ?? '';
                // Fallback map for previously stored icons not in new list
                const fallbackMap = {
                    'fas fa-ice-cream':'fas fa-blender',
                    'fas fa-mug-hot':'fas fa-glass-whiskey',
                    'fas fa-cookie-bite':'fas fa-utensils',
                    'fas fa-lemon':'fas fa-leaf'
                };
                const select = document.getElementById('categoryIcon');
                const incoming = category.icon || 'fas fa-coffee';
                const mapped = fallbackMap[incoming] || incoming;
                // If mapped value not found in options, default to coffee
                if (![...select.options].some(o=>o.value===mapped)) {
                    select.value = 'fas fa-coffee';
                } else {
                    select.value = mapped;
                }
                // Icon preview removed
                document.getElementById('categoryColor').value = category.color ?? '#8B4513';
                document.getElementById('categoryStatus').value = category.status;
                document.getElementById('categoryOrder').value = category.display_order;
                document.getElementById('categoryFeatured').checked = category.featured;
                new bootstrap.Modal(document.getElementById('categoryModal')).show();
            });
    };

    window.toggleCategoryStatus = function(id) {
        fetch("{{ url('/api/categories') }}/"+id+"/toggle-status", {
            method:'PATCH',
            headers:{
                'Accept':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(r=>r.json()).then(resp=>{
            if (resp.success) loadFromServer();
        });
    };

    window.deleteCategoryModal = function(id) {
        // Simple confirm route
        if (!confirm('Delete this category? (Must have no products)')) return;
        fetch("{{ url('/inventory/categories') }}/"+id, {
            method:'DELETE',
            headers:{
                'Accept':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(r=>r.json()).then(resp=>{
            if (resp.success) loadFromServer(); else alert(resp.message||'Delete failed');
        });
    };

    window.viewCategoryProducts = function(id) {
        const modalEl = document.getElementById('productsModal');
        const body = document.getElementById('productsModalContent');
        body.innerHTML = '<div class="py-4 text-center text-muted">Loading products...</div>';
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
        // Fetch category (for title) and its products
        Promise.all([
            fetch("{{ url('/inventory/categories') }}/"+id, {headers:{'Accept':'application/json'}})
                .then(r=>r.ok?r.json():Promise.reject()),
            fetch("{{ route('inventory.api.products.list') }}?category_id="+id, {headers:{'Accept':'application/json'}})
                .then(r=>r.ok?r.json():Promise.reject())
        ]).then(([catResp, prodResp])=>{
            const cat = catResp.category;
            document.getElementById('productsModalTitle').textContent = cat.name + ' Products';
            const products = prodResp.data || [];
            if (!products.length) {
                body.innerHTML = '<div class="py-5 text-center text-muted">No products in this category yet.</div>';
            } else {
                body.innerHTML = '<div class="list-group">' + products.map(p=>renderProductItem(p)).join('') + '</div>';
            }
            // Update Add Product button to pre-select category via query param
            const addBtn = document.getElementById('addProductToCategory');
            if (addBtn) {
                addBtn.onclick = ()=> window.location = "{{ route('inventory.products.index') }}?category_id="+id;
            }
        }).catch(()=>{
            body.innerHTML = '<div class="py-5 text-center text-danger">Failed to load products.</div>';
        });
    };

    function renderProductItem(p){
        return `
            <div class="product-item d-flex justify-content-between align-items-center list-group-item">
                <div>
                    <div class="product-name">${p.name} <small class="text-muted">(${p.sku||''})</small></div>
                    <div class="product-stock">Stock: ${p.current_stock ?? 0} | Status: <span class="badge bg-${p.status==='active'?'success':'secondary'}">${p.status}</span></div>
                </div>
                <div class="text-end">
                    <div class="product-price">₱${Number(p.base_price).toFixed(2)}</div>
                </div>
            </div>
        `;
    }

    function createCategoryCard(c) {
                const emj = iconEmoji[c.icon] || '☕';
                return `
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card category-card">
                        ${c.featured ? '<span class="featured-badge">Featured</span>' : ''}
                        <div class="category-header">
                            <div class="category-icon" style="background:${c.color||'#8B4513'}">
                                <span class="icon-emoji">${emj}</span>
                            </div>
                            <div class="category-name">${c.name}</div>
                            <p class="category-description">${c.description??''}</p>
                        </div>
                        <div class="category-body">
                            <div class="category-stats">
                                <div class="stat-item">
                                    <span class="stat-number">${c.products_count||0}</span>
                                    <span class="stat-label">Products</span>
                                </div>
                                <div class="stat-item">
                                    <span class="badge status-badge status-${c.status}">${c.status.toUpperCase()}</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">Created ${new Date(c.created_at).toLocaleDateString()}</small>
                            </div>
                        </div>
                        <div class="category-footer">
                            <div class="category-actions">
                                <button class="btn btn-outline-primary btn-action" onclick="viewCategoryProducts(${c.id})"><i class="fas fa-eye"></i> Products</button>
                                <button class="btn btn-outline-info btn-action" onclick="editCategory(${c.id})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-${c.status==='active'?'warning':'success'} btn-action" onclick="toggleCategoryStatus(${c.id})"><i class="fas fa-${c.status==='active'?'pause':'play'}"></i></button>
                                <button class="btn btn-outline-danger btn-action" onclick="deleteCategoryModal(${c.id})"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>`;
    }

    function createCategoryRow(c) {
                const emj = iconEmoji[c.icon] || '☕';
                return `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="category-icon-small" style="background:${c.color||'#8B4513'};font-size:16px;">${emj}</div>
                            <div>
                                <div class="fw-bold">${c.name}</div>
                                <small class="text-muted">${c.slug}</small>
                                ${c.featured?'<span class="badge bg-warning text-dark ms-2">Featured</span>':''}
                            </div>
                        </div>
                    </td>
                    <td><div style="max-width:200px;">${c.description??''}</div></td>
                    <td><span class="fw-bold text-primary">${c.products_count||0}</span></td>
                    <td><span class="badge status-badge status-${c.status}">${c.status.toUpperCase()}</span></td>
                    <td>${new Date(c.created_at).toLocaleDateString()}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-primary btn-action" onclick="viewCategoryProducts(${c.id})"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-outline-info btn-action" onclick="editCategory(${c.id})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-outline-${c.status==='active'?'warning':'success'} btn-action" onclick="toggleCategoryStatus(${c.id})"><i class="fas fa-${c.status==='active'?'pause':'play'}"></i></button>
                            <button class="btn btn-outline-danger btn-action" onclick="deleteCategoryModal(${c.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>`;
    }

    function debounce(fn, wait){
        let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a),wait); };
    }
});
</script>
@endsection