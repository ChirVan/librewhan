@extends('layouts.app')

@section('title', 'Take New Order - Librewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- Page Directory -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Order Management</h3>
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
                    <a href="#">Take New Order</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <!-- Products Section -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Menu Items</h4>
                            <div class="btn-group" role="group" id="category-filters">
                                <!-- Category filter buttons will be rendered here -->
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row" id="products-grid">
                            <!-- Products will be rendered here by JS -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Current Orderss</h4>
                    </div>
                    <div class="card-body">
                        <!-- Customer Info -->
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Enter customer name">
                        </div>
                        
                        <!-- Order Type -->
                        <div class="mb-3">
                            <label class="form-label">Order Type</label>
                            <select class="form-control" id="orderType">
                                <option value="dine-in">Dine In</option>
                                <option value="takeaway">Takeout</option>
                            </select>
                        </div>

                        <!-- Cart Items -->
                        <div class="cart-items mb-3">
                            <div id="cart-empty" class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <p>No items in cart</p>
                            </div>
                            <div id="cart-items" style="display: none;">
                                <!-- Cart items will be dynamically added here -->
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="payment-section mb-3" id="payment-section" style="display: none;">
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-3">Payment Details</h6>
                                
                                <!-- Payment Mode -->
                                <div class="mb-3">
                                    <label class="form-label">Mode of Payment</label>
                                    <select class="form-control" id="paymentMode" onchange="toggleCashPayment()">
                                        <option value="cash">Cash</option>
                                        <option value="gcash">GCash</option>
                                        <option value="maya">Maya</option>
                                    </select>
                                </div>
                                
                                <!-- Customer Payment Amount (only for cash) -->
                                <div class="mb-3" id="cashPaymentSection">
                                    <label class="form-label">Customer Payment Amount</label>
                                    <input type="number" class="form-control" id="customerPayment" 
                                           placeholder="Enter amount received" step="0.01" min="0" 
                                           oninput="calculateChange()">
                                </div>
                                
                                <!-- Change Display -->
                                <div id="changeDisplay" class="change-display" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded border">
                                        <span class="fw-bold">Change:</span>
                                        <span id="changeAmount" class="fw-bold">₱0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="order-summary" id="order-summary" style="display: none;">
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 fw-bold fs-5">
                                    <span>Total:</span>
                                    <span id="total">₱0.00</span>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success btn-lg" id="process-order">
                                        <i class="fas fa-check"></i> Process Order
                                    </button>
                                    <button class="btn btn-danger" id="clear-cart">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Customization Modal -->
<div class="modal fade" id="customizationModal" tabindex="-1" aria-labelledby="customizationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fw-bold" id="customizationModalLabel">Customize Order</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- Product Info Header -->
                <div class="d-flex align-items-center mb-3 bg-light p-2 rounded">
                    <i id="productIcon" class="fas fa-coffee fa-2x text-brown me-3"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-0" id="selectedProduct">Product Name</h6>
                        <small class="text-muted" id="basePriceDisplay">Base Price: <span id="productPrice">₱0.00</span></small>
                    </div>
                    <h5 class="text-primary mb-0" id="currentTotal">₱0.00</h5>
                </div>

                <div class="row g-2">
                    <!-- Size Options -->
                    <div class="col-12" id="sizeOptions">
                        <label class="form-label fw-bold mb-1 small">Size</label>
                        <div class="btn-group w-100 btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="size" id="size-small" value="small" checked>
                            <label class="btn btn-outline-primary py-1" for="size-small">S<br><small>₱49</small></label>
                            
                            <input type="radio" class="btn-check" name="size" id="size-medium" value="medium">
                            <label class="btn btn-outline-primary py-1" for="size-medium">M<br><small>₱59</small></label>
                            
                            <input type="radio" class="btn-check" name="size" id="size-large" value="large">
                            <label class="btn btn-outline-primary py-1" for="size-large">L<br><small>₱69</small></label>
                        </div>
                    </div>

                    <!-- Sugar & Ice Level -->
                    <div class="col-6" id="sugarOptions">
                        <label class="form-label fw-bold mb-1 small">Sugar</label>
                        <select class="form-control form-control-sm" name="sugar">
                            <option value="no-sugar">No Sugar</option>
                            <option value="25">25%</option>
                            <option value="50">50%</option>
                            <option value="75">75%</option>
                            <option value="100" selected>Normal</option>
                        </select>
                    </div>

                    <div class="col-6" id="iceOptions">
                        <label class="form-label fw-bold mb-1 small">Ice</label>
                        <select class="form-control form-control-sm" name="ice">
                            <option value="no-ice">No Ice</option>
                            <option value="less-ice">Less</option>
                            <option value="normal-ice" selected>Normal</option>
                            <option value="extra-ice">Extra</option>
                        </select>
                    </div>

                    <!-- Milk Type -->
                    <div class="col-12" id="milkOptions">
                        <label class="form-label fw-bold mb-1 small">Milk Type</label>
                        <select class="form-control form-control-sm" name="milk">
                            <option value="regular" selected>Regular Milk</option>
                            <option value="almond">Almond (+₱15.00)</option>
                            <option value="oat">Oat (+₱15.00)</option>
                            <option value="soy">Soy (+₱10.00)</option>
                            <option value="coconut">Coconut (+₱10.00)</option>
                            <option value="skim">Skim Milk</option>
                        </select>
                    </div>

                    <!-- Toppings -->
                    <div class="col-12" id="toppingsOptions">
                        <label class="form-label fw-bold mb-1 small">Add-ons</label>
                        <div class="row g-1">
                            <div class="col-6">
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="extra-shot" id="extra-shot">
                                    <label class="form-check-label small" for="extra-shot">Extra Shot (+₱15.00)</label>
                                </div>
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="whipped-cream" id="whipped-cream">
                                    <label class="form-check-label small" for="whipped-cream">Whipped Cream (+₱10.00)</label>
                                </div>
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="vanilla-syrup" id="vanilla-syrup">
                                    <label class="form-check-label small" for="vanilla-syrup">Vanilla Syrup (+₱10.00)</label>
                                </div>
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="caramel-syrup" id="caramel-syrup">
                                    <label class="form-check-label small" for="caramel-syrup">Caramel Syrup (+₱10.00)</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="cinnamon" id="cinnamon">
                                    <label class="form-check-label small" for="cinnamon">Cinnamon (+₱5.00)</label>
                                </div>
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="pearls" id="pearls">
                                    <label class="form-check-label small" for="pearls">Tapioca Pearls (+₱20.00)</label>
                                </div>
                                <div class="form-check form-check-sm">
                                    <input class="form-check-input" type="checkbox" name="toppings" value="jelly" id="jelly">
                                    <label class="form-check-label small" for="jelly">Coconut Jelly (+₱15.00)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="col-12">
                        <label class="form-label fw-bold mb-1 small">Notes</label>
                        <textarea class="form-control form-control-sm" name="instructions" rows="2" placeholder="Special requests..." style="resize: none;"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="confirmCustomization">
                    <i class="fas fa-plus"></i> Add to Cart
                </button>
            </div>
            
        </div>
    </div>
</div>

<!-- Custom CSS for POS -->
<style>
.product-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e3e6f0;
    min-height: 110px; /* Reduced from 140px */
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cart-item {
    border-bottom: 1px solid #eee;
    padding: 8px 0;
}

.cart-item:last-child {
    border-bottom: none;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-btn {
    width: 25px;
    height: 25px;
    border: 1px solid #ddd;
    background: #f8f9fa;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    font-weight: bold;
}

.quantity-btn:hover {
    background: #e9ecef;
}

.category-filter.active {
    background-color: #1572e8;
    border-color: #1572e8;
    color: white;
}

.text-brown {
    color: #8B4513 !important;
}

.payment-section {
    background: #f8f9fa;
    border-radius: 8px;
}

.change-display {
    margin-top: 10px;
}

.change-positive {
    color: #28a745 !important;
}

.change-negative {
    color: #dc3545 !important;
}

/* Compact card styling for 6 per row */
.product-card .card-img-top {
    height: 45px !important; /* Reduced from 60px */
}

.product-card .card-body {
    min-height: 45px; /* Reduced */
    padding: 8px !important; /* Reduced padding */
}

.product-card .card-title {
    font-weight: 600;
    font-size: 0.75rem !important; /* Smaller font */
    line-height: 1.1;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 4px !important; /* Reduced margin */
}

.product-card .card-footer {
    padding: 4px 6px !important; /* Reduced padding */
}

.product-card .card-footer .btn {
    font-size: 0.65rem !important; /* Smaller button text */
    padding: 0.15rem 0.25rem !important; /* Smaller button padding */
}

/* Price styling */
.product-card .card-body h6 {
    font-size: 0.8rem !important;
    margin-bottom: 0 !important;
}

/* Icon sizing */
.product-card .fa-lg {
    font-size: 1.2rem !important; /* Smaller icons */
}

/* Cart item styling */
.cart-item h6 {
    font-size: 0.9rem;
}

.cart-item .quantity {
    font-size: 0.9rem;
    font-weight: bold;
    min-width: 20px;
    text-align: center;
}

/* Compact modal styling */
.modal-dialog {
    max-width: 500px;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    border-top: 1px solid #dee2e6;
}

.form-check-sm .form-check-input {
    margin-top: 0.1rem;
}

.form-check-sm .form-check-label {
    font-size: 0.8rem;
    line-height: 1.3;
}

.btn-group-sm > .btn {
    font-size: 0.75rem;
}

.customization-details {
    font-size: 0.75rem;
    color: #666;
    font-style: italic;
}

.btn-check:checked + .btn-outline-primary {
    background-color: #1572e8;
    border-color: #1572e8;
    color: white;
}

/* Responsive grid adjustments */
@media (min-width: 1200px) {
    .col-xl-2 {
        flex: 0 0 auto;
        width: 16.666667%; /* 6 items per row on XL screens */
    }
}
</style>

<!-- POS JavaScript (same as before) -->
<script>
// POS dynamic fetch and render

const productsApiUrl = "{{ url('/inventory/api/products') }}";
// Use a fallback or static endpoint for categories if route is missing
const categoriesApiUrl = "/inventory/categories/data";

let allProducts = [];
let allCategories = [];

// Make openCustomizationModal globally accessible
window.openCustomizationModal = function(product) {
    // Debug: log the category value
    console.log('Product category:', product.category);
    currentProduct = product;
    document.getElementById('selectedProduct').textContent = product.name;
    const basePriceDisplay = document.getElementById('basePriceDisplay');
    const iconElement = document.getElementById('productIcon');
    // Always reset all modal sections to hidden first
    basePriceDisplay.style.display = 'none';
    document.getElementById('sizeOptions').style.display = 'none';
    document.getElementById('sugarOptions').style.display = 'none';
    document.getElementById('iceOptions').style.display = 'none';
    document.getElementById('milkOptions').style.display = 'none';
    document.getElementById('toppingsOptions').style.display = 'none';


    // Normalize category for comparison
    const cat = (product.category || '').toLowerCase();

    // Set icon
    if (cat.includes('coffee')) {
        iconElement.className = 'fas fa-coffee fa-2x text-brown me-3';
    } else if (cat === 'pastry') {
        iconElement.className = 'fas fa-cookie-bite fa-2x text-warning me-3';
    } else if (cat === 'food') {
        iconElement.className = 'fas fa-hamburger fa-2x text-success me-3';
    } else if (cat === 'snacks') {
        iconElement.className = 'fas fa-utensils fa-2x text-muted me-3';
    } else {
        iconElement.className = 'fas fa-utensils fa-2x text-muted me-3';
    }

    // Show/hide modal sections based on category
        if (cat.includes('hot coffee')) {
        // Only show 16oz and 22oz sizes, and only Creamer as add-on
        basePriceDisplay.style.display = 'none';
        document.getElementById('sizeOptions').style.display = 'block';
        document.getElementById('sugarOptions').style.display = 'block';
        document.getElementById('iceOptions').style.display = 'block';
    document.getElementById('milkOptions').style.display = 'none';
        document.getElementById('toppingsOptions').style.display = 'block';

        // Replace size options
        document.getElementById('sizeOptions').innerHTML = `
            <label class="form-label fw-bold mb-1 small">Size</label>
            <div class="btn-group w-100 btn-group-sm" role="group">
                <input type="radio" class="btn-check" name="size" id="size-16oz" value="16oz" checked>
                <label class="btn btn-outline-primary py-1" for="size-16oz">16oz</label>
                <input type="radio" class="btn-check" name="size" id="size-22oz" value="22oz">
                <label class="btn btn-outline-primary py-1" for="size-22oz">22oz</label>
            </div>
            <div class="row mt-1 px-1">
                <div class="col-6 text-center">
                    <small class="text-muted">₱60.00</small>
                </div>
                <div class="col-6 text-center">
                    <small class="text-muted">₱75.00</small>
                </div>
            </div>
        `;
        // Replace toppings with only Creamer
        document.getElementById('toppingsOptions').innerHTML = `
            <label class="form-label fw-bold mb-1 small">Add-ons</label>
            <div class="form-check form-check-sm">
                <input class="form-check-input" type="checkbox" name="toppings" value="creamer" id="creamer">
                <label class="form-check-label small" for="creamer">Creamer (+₱10.00)</label>
            </div>
        `;
    } else if (cat === 'snacks') {
        // Only show price and notes
        basePriceDisplay.style.display = 'block';
        let priceNum = Number(product.price);
        if (isNaN(priceNum)) priceNum = 0;
        document.getElementById('productPrice').textContent = `₱${priceNum.toFixed(2)}`;
        // Focus notes field after modal opens
        setTimeout(() => {
            const notes = document.querySelector('textarea[name="instructions"]');
            if (notes) notes.focus();
        }, 400);
    } else if (cat.includes('coffee') || cat.includes('fruit tea') || cat.includes('frappe') || cat.includes('hot coffee')) {
        basePriceDisplay.style.display = 'none';
        document.getElementById('sizeOptions').style.display = 'block';
        document.getElementById('sugarOptions').style.display = 'block';
        document.getElementById('iceOptions').style.display = 'block';
        document.getElementById('milkOptions').style.display = 'block';
        document.getElementById('toppingsOptions').style.display = 'block';
        // Restore default size and toppings if previously replaced
        if (!cat.includes('hot coffee')) {
            document.getElementById('sizeOptions').innerHTML = `
                <label class="form-label fw-bold mb-1 small">Size</label>
                <div class="btn-group w-100 btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="size" id="size-small" value="small" checked>
                    <label class="btn btn-outline-primary py-1" for="size-small">S<br><small>₱49</small></label>
                    <input type="radio" class="btn-check" name="size" id="size-medium" value="medium">
                    <label class="btn btn-outline-primary py-1" for="size-medium">M<br><small>₱59</small></label>
                    <input type="radio" class="btn-check" name="size" id="size-large" value="large">
                    <label class="btn btn-outline-primary py-1" for="size-large">L<br><small>₱69</small></label>
                </div>
            `;
            document.getElementById('toppingsOptions').innerHTML = `
                <label class="form-label fw-bold mb-1 small">Add-ons</label>
                <div class="row g-1">
                    <div class="col-6">
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="extra-shot" id="extra-shot">
                            <label class="form-check-label small" for="extra-shot">Extra Shot (+₱15.00)</label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="whipped-cream" id="whipped-cream">
                            <label class="form-check-label small" for="whipped-cream">Whipped Cream (+₱10.00)</label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="vanilla-syrup" id="vanilla-syrup">
                            <label class="form-check-label small" for="vanilla-syrup">Vanilla Syrup (+₱10.00)</label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="caramel-syrup" id="caramel-syrup">
                            <label class="form-check-label small" for="caramel-syrup">Caramel Syrup (+₱10.00)</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="cinnamon" id="cinnamon">
                            <label class="form-check-label small" for="cinnamon">Cinnamon (+₱5.00)</label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="pearls" id="pearls">
                            <label class="form-check-label small" for="pearls">Tapioca Pearls (+₱20.00)</label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="checkbox" name="toppings" value="jelly" id="jelly">
                            <label class="form-check-label small" for="jelly">Coconut Jelly (+₱15.00)</label>
                        </div>
                    </div>
                </div>
            `;
        }
    } else {
        // For all other categories (e.g. Pastry, Food), show price and notes
        basePriceDisplay.style.display = 'block';
        let priceNum = Number(product.price);
        if (isNaN(priceNum)) priceNum = 0;
        document.getElementById('productPrice').textContent = `₱${priceNum.toFixed(2)}`;
    }

    window.resetCustomizationForm();
    window.updateCustomizationTotal();
    const modal = new bootstrap.Modal(document.getElementById('customizationModal'));
    modal.show();
};

document.addEventListener('DOMContentLoaded', function() {
    // Fetch categories and products, then render
    Promise.all([
        fetch(categoriesApiUrl, {headers: {Accept: 'application/json'}}).then(r => r.json()),
        fetch(productsApiUrl, {headers: {Accept: 'application/json'}}).then(r => r.json())
    ]).then(([catRes, prodRes]) => {
        allCategories = catRes.data.filter(c => c.status === 'active').map(c => c.name);
        allProducts = prodRes.data;
        renderCategoryFilters();
        renderProductsGrid('All'); // Default to Coffee
        bindCategoryFilterEvents();
    });

    // ...existing code for cart, customization, payment, etc...
});

function renderCategoryFilters() {
    const container = document.getElementById('category-filters');
    let html = `<button type="button" class="btn btn-outline-primary btn-sm category-filter" data-category="All">All</button>`;
    allCategories.forEach((cat, idx) => {
        html += `<button type="button" class="btn btn-outline-primary btn-sm category-filter${cat==='Coffee'?' active':''}" data-category="${cat}">${cat}</button>`;
    });
    container.innerHTML = html;
}

function renderProductsGrid(category = 'All') {
    const grid = document.getElementById('products-grid');
    let filtered = (category === 'All') ? allProducts : allProducts.filter(p => p.category && p.category.name === category);
    if (!filtered.length) {
        grid.innerHTML = '<div class="text-center text-muted py-4">No products found.</div>';
        return;
    }
    grid.innerHTML = filtered.map(product => {
        const cat = product.category ? product.category.name : '';
        let icon = '<i class="fas fa-utensils fa-lg text-muted"></i>';
        if (cat === 'Coffee') icon = '<i class="fas fa-coffee fa-lg text-brown"></i>';
        else if (cat === 'Pastry') icon = '<i class="fas fa-cookie-bite fa-lg text-warning"></i>';
        else if (cat === 'Food') icon = '<i class="fas fa-hamburger fa-lg text-success"></i>';
        return `<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-2 product-item" data-category="${cat}">
            <div class="card product-card h-100" data-product='${JSON.stringify({
                id: product.id,
                name: product.name,
                price: product.base_price,
                category: cat
            })}'>
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light">${icon}</div>
                <div class="card-body text-center">
                    <h6 class="card-title">${product.name}</h6>
                    ${cat !== 'Coffee' ? `<h6 class="text-primary">₱${Number(product.base_price).toFixed(2)}</h6>` : '<small class="text-muted">Select size for price</small>'}
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm w-100 add-to-cart" data-product-id="${product.id}"><i class="fas fa-plus"></i> Add</button>
                </div>
            </div>
        </div>`;
    }).join('');

    // Bind click event to open customization modal on card click (not just add button)
    grid.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Prevent double trigger if add button is clicked
            if (e.target.closest('.add-to-cart')) return;
            const product = JSON.parse(this.dataset.product);
            openCustomizationModal(product);
        });
    });

    // Bind add-to-cart button as well (for explicit add button click)
    grid.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const productCard = this.closest('.product-card');
            const product = JSON.parse(productCard.dataset.product);
            openCustomizationModal(product);
        });
    });
}

function bindCategoryFilterEvents() {
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderProductsGrid(this.dataset.category);
        });
    });
}

function bindProductAddEvents() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const productCard = this.closest('.product-card');
            const product = JSON.parse(productCard.dataset.product);
            openCustomizationModal(product);
        });
    });
}

// ...existing code for cart, customization, payment, etc...

// Make resetCustomizationForm globally accessible
window.resetCustomizationForm = function() {
    // Reset size radio (support both classic and Hot Coffee)
    let sizeSmall = document.getElementById('size-small');
    let size16oz = document.getElementById('size-16oz');
    if (sizeSmall) {
        sizeSmall.checked = true;
    } else if (size16oz) {
        size16oz.checked = true;
    }
    // Reset dropdowns
    let sugarSel = document.querySelector('select[name="sugar"]');
    if (sugarSel) sugarSel.value = '100';
    let iceSel = document.querySelector('select[name="ice"]');
    if (iceSel) iceSel.value = 'normal-ice';
    let milkSel = document.querySelector('select[name="milk"]');
    if (milkSel) milkSel.value = 'regular';
    // Reset checkboxes
    document.querySelectorAll('input[name="toppings"]').forEach(cb => cb.checked = false);
    // Reset textarea
    let notes = document.querySelector('textarea[name="instructions"]');
    if (notes) notes.value = '';
};

// Make updateCustomizationTotal globally accessible
window.updateCustomizationTotal = function() {
    let total;
    // Handle coffee items differently - use size as base price
    if (currentProduct.category && currentProduct.category.toLowerCase() === 'hot coffee') {
        const selectedSize = document.querySelector('input[name="size"]:checked');
        if (selectedSize) {
            if (selectedSize.value === '16oz') total = 60.00;
            else if (selectedSize.value === '22oz') total = 75.00;
            else total = 60.00;
        } else {
            total = 60.00;
        }
    } else if (currentProduct.category === 'Coffee') {
        const selectedSize = document.querySelector('input[name="size"]:checked');
        if (selectedSize) {
            if (selectedSize.value === 'small') total = 49.00;
            else if (selectedSize.value === 'medium') total = 59.00;
            else if (selectedSize.value === 'large') total = 69.00;
            else total = 49.00; // default to small
        } else {
            total = 49.00; // default to small
        }
    } else {
        // Non-coffee items use the original price
        total = Number(currentProduct.price) || 0;
    }
    // Add milk cost (only for coffee)
    if (currentProduct.category === 'Coffee') {
        const selectedMilk = document.querySelector('select[name="milk"]').value;
        if (selectedMilk === 'almond' || selectedMilk === 'oat') total += 15.00;
        if (selectedMilk === 'soy' || selectedMilk === 'coconut') total += 10.00;
    }
    // Add toppings cost (only for coffee)
    if (currentProduct.category === 'Coffee') {
        document.querySelectorAll('input[name="toppings"]:checked').forEach(topping => {
            const value = topping.value;
            if (value === 'extra-shot') total += 15.00;
            if (value === 'whipped-cream' || value === 'vanilla-syrup' || value === 'caramel-syrup') total += 10.00;
            if (value === 'cinnamon') total += 5.00;
            if (value === 'pearls') total += 20.00;
            if (value === 'jelly') total += 15.00;
        });
    }
    document.getElementById('currentTotal').textContent = `₱${total.toFixed(2)}`;
};
</script>
<script>
// --- CART FUNCTIONALITY ---
let cart = [];

function renderCart() {
    const cartEmpty = document.getElementById('cart-empty');
    const cartItems = document.getElementById('cart-items');
    const paymentSection = document.getElementById('payment-section');
    const orderSummary = document.getElementById('order-summary');
    if (!cart.length) {
        cartEmpty.style.display = '';
        cartItems.style.display = 'none';
        cartItems.innerHTML = '';
        if (paymentSection) paymentSection.style.display = 'none';
        if (orderSummary) orderSummary.style.display = 'none';
        return;
    }
    cartEmpty.style.display = 'none';
    cartItems.style.display = '';
    cartItems.innerHTML = cart.map((item, idx) => `
        <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2">
            <div>
                <h6 class="mb-0">${item.name} <small class="text-muted">x${item.qty}</small></h6>
                <div class="small text-muted">${item.size ? item.size : ''} ${item.toppings && item.toppings.length ? ' | ' + item.toppings.join(', ') : ''}</div>
                <div class="small text-muted">${item.instructions ? item.instructions : ''}</div>
            </div>
            <div class="text-end">
                <div class="fw-bold text-primary mb-1">₱${(item.total * item.qty).toFixed(2)}</div>
                <div class="input-group input-group-sm mb-1" style="width: 110px; display: inline-flex;">
                    <button class="btn btn-outline-secondary btn-sm btn-qty-minus" data-idx="${idx}" type="button">-</button>
                    <input type="text" class="form-control text-center" value="${item.qty}" readonly style="max-width: 36px;">
                    <button class="btn btn-outline-secondary btn-sm btn-qty-plus" data-idx="${idx}" type="button">+</button>
                </div>
                <button class="btn btn-sm btn-danger remove-cart-item ms-1" data-idx="${idx}"><i class="fas fa-times"></i></button>
            </div>
        </div>
    `).join('');
    if (paymentSection) paymentSection.style.display = '';
    if (orderSummary) orderSummary.style.display = '';

    // Calculate subtotal and total
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.total * item.qty;
    });
    // If you want to add tax/discount, do it here. For now, total = subtotal
    let total = subtotal;
    const subtotalElem = document.getElementById('subtotal');
    const totalElem = document.getElementById('total');
    if (subtotalElem) subtotalElem.textContent = `₱${subtotal.toFixed(2)}`;
    if (totalElem) totalElem.textContent = `₱${total.toFixed(2)}`;
    // Remove item event
    cartItems.querySelectorAll('.remove-cart-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = Number(this.dataset.idx);
            cart.splice(idx, 1);
            renderCart();
        });
    });
    // Quantity plus/minus events
    cartItems.querySelectorAll('.btn-qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = Number(this.dataset.idx);
            cart[idx].qty += 1;
            renderCart();
        });
    });
    cartItems.querySelectorAll('.btn-qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = Number(this.dataset.idx);
            if (cart[idx].qty > 1) {
                cart[idx].qty -= 1;
                renderCart();
            }
        });
    });
}

// Add to cart from modal
document.getElementById('confirmCustomization').addEventListener('click', function() {
    // Gather selected options
    const name = document.getElementById('selectedProduct').textContent;
    let size = '';
    const sizeRadio = document.querySelector('input[name="size"]:checked');
    if (sizeRadio) size = sizeRadio.value;
    let toppings = [];
    document.querySelectorAll('input[name="toppings"]:checked').forEach(cb => toppings.push(cb.value));
    let sugar = '';
    let ice = '';
    let milk = '';
    const sugarSel = document.querySelector('select[name="sugar"]');
    if (sugarSel) sugar = sugarSel.value;
    const iceSel = document.querySelector('select[name="ice"]');
    if (iceSel) ice = iceSel.value;
    const milkSel = document.querySelector('select[name="milk"]');
    if (milkSel && milkSel.offsetParent !== null) milk = milkSel.value;
    const instructions = document.querySelector('textarea[name="instructions"]').value;
    const totalText = document.getElementById('currentTotal').textContent;
    const total = Number(totalText.replace(/[^\d.]/g, ''));
    // Add to cart array
    cart.push({
        name,
        size,
        toppings,
        sugar,
        ice,
        milk,
        instructions,
        total,
        qty: 1
    });
    renderCart();
    // Hide modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('customizationModal'));
    if (modal) modal.hide();
});

// Calculate and display change
function calculateChange() {
    const paymentInput = document.getElementById('customerPayment');
    const totalElem = document.getElementById('total');
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmount = document.getElementById('changeAmount');
    let payment = parseFloat(paymentInput.value) || 0;
    let total = 0;
    if (totalElem) {
        total = parseFloat(totalElem.textContent.replace(/[^\d.]/g, '')) || 0;
    }
    let change = payment - total;
    if (changeDisplay) {
        if (!isNaN(change) && payment > 0) {
            changeDisplay.style.display = '';
            changeAmount.textContent = `₱${change.toFixed(2)}`;
            changeAmount.classList.remove('change-positive', 'change-negative');
            if (change >= 0) {
                changeAmount.classList.add('change-positive');
            } else {
                changeAmount.classList.add('change-negative');
            }
        } else {
            changeDisplay.style.display = 'none';
        }
    }
}

// Initial render
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    // Listen for cart/total changes to recalculate change
    document.getElementById('customerPayment').addEventListener('input', calculateChange);

    // Process Order button click
    document.getElementById('process-order').addEventListener('click', async function() {
        if (!cart.length) {
            alert('Cart is empty!');
            return;
        }
        const customerName = document.getElementById('customerName').value;
        const orderType = document.getElementById('orderType').value;
        const paymentMode = document.getElementById('paymentMode').value;
        const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace(/[^\d.]/g, '')) || 0;
        const total = parseFloat(document.getElementById('total').textContent.replace(/[^\d.]/g, '')) || 0;
        const amountPaid = parseFloat(document.getElementById('customerPayment').value) || 0;
        const changeDue = amountPaid - total;
        // Prepare items
        const items = cart.map(item => ({
            name: item.name,
            size: item.size || null,
            toppings: item.toppings || [],
            sugar: item.sugar || null,
            ice: item.ice || null,
            milk: item.milk || null,
            instructions: item.instructions || null,
            price: item.total,
            qty: item.qty
        }));
        try {
            const response = await fetch('/pos/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    customer_name: customerName,
                    order_type: orderType,
                    payment_mode: paymentMode,
                    subtotal,
                    total,
                    amount_paid: amountPaid,
                    change_due: changeDue,
                    items
                })
            });
            const data = await response.json();
            if (data.success) {
                alert('Order placed successfully!');
                cart.length = 0;
                renderCart();
                document.getElementById('customerName').value = '';
                document.getElementById('customerPayment').value = '';
                calculateChange();
            } else {
                alert('Order failed: ' + (data.message || 'Unknown error'));
            }
        } catch (err) {
            alert('Order failed: ' + err.message);
        }
    });
});

// Also recalculate change whenever cart is updated
const origRenderCart = renderCart;
renderCart = function() {
    origRenderCart.apply(this, arguments);
    calculateChange();
};
</script>
@endsection