@extends('layouts.app')

@section('title', 'Take New Order - Kalibrewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- Page Directory -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Point of Sale</h3>
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
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm category-filter" data-category="All">All</button>
                                @foreach($categories as $category)
                                    @if($category !== 'All')
                                        <button type="button" class="btn btn-outline-primary btn-sm category-filter {{ $loop->first ? 'active' : '' }}" data-category="{{ $category }}">{{ $category }}</button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row" id="products-grid">
                            @foreach($products as $product)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-2 product-item" data-category="{{ $product['category'] }}">
                                    <div class="card product-card h-100" data-product='@json($product)'>
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                                            @if($product['category'] === 'Coffee')
                                                <i class="fas fa-coffee fa-lg text-brown"></i>
                                            @elseif($product['category'] === 'Pastry')
                                                <i class="fas fa-cookie-bite fa-lg text-warning"></i>
                                            @elseif($product['category'] === 'Food')
                                                <i class="fas fa-hamburger fa-lg text-success"></i>
                                            @else
                                                <i class="fas fa-utensils fa-lg text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="card-body text-center">
                                            <h6 class="card-title">{{ $product['name'] }}</h6>
                                            @if($product['category'] !== 'Coffee')
                                                <h6 class="text-primary">₱{{ number_format($product['price'], 2) }}</h6>
                                            @else
                                                <small class="text-muted">Select size for price</small>
                                            @endif
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-primary btn-sm w-100 add-to-cart" data-product-id="{{ $product['id'] }}">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Current Order</h4>
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
                                        <option value="card">Credit/Debit Card</option>
                                        <option value="bank">Bank Transfer</option>
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
document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    let currentProduct = null;
    let currentTotal = 0; // Remove tax rate since we're removing tax

    // Set Coffee as default active category
    document.querySelector('.category-filter[data-category="Coffee"]').classList.add('active');

    // Category filtering
    document.querySelectorAll('.category-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            filterProducts(category);
        });
    });

    function filterProducts(category) {
        const products = document.querySelectorAll('.product-item');
        products.forEach(product => {
            if (category === 'All' || product.dataset.category === category) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    // Add to cart functionality - now opens customization modal
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const productCard = this.closest('.product-card');
            const product = JSON.parse(productCard.dataset.product);
            openCustomizationModal(product);
        });
    });

    function openCustomizationModal(product) {
        currentProduct = product;
        
        // Update modal content
        document.getElementById('selectedProduct').textContent = product.name;
        
        // Handle price display for coffee vs non-coffee items
        const basePriceDisplay = document.getElementById('basePriceDisplay');
        if (product.category === 'Coffee') {
            basePriceDisplay.style.display = 'none'; // Hide base price for coffee
        } else {
            basePriceDisplay.style.display = 'block';
            document.getElementById('productPrice').textContent = `₱${product.price.toFixed(2)}`;
        }
        
        // Update icon based on category
        const iconElement = document.getElementById('productIcon');
        if (product.category === 'Coffee') {
            iconElement.className = 'fas fa-coffee fa-2x text-brown me-3';
        } else if (product.category === 'Pastry') {
            iconElement.className = 'fas fa-cookie-bite fa-2x text-warning me-3';
        } else if (product.category === 'Food') {
            iconElement.className = 'fas fa-hamburger fa-2x text-success me-3';
        }

        // Show/hide options based on product category
        const isCustomizable = product.category === 'Coffee';
        document.getElementById('sizeOptions').style.display = isCustomizable ? 'block' : 'none';
        document.getElementById('sugarOptions').style.display = isCustomizable ? 'block' : 'none';
        document.getElementById('iceOptions').style.display = isCustomizable ? 'block' : 'none';
        document.getElementById('milkOptions').style.display = isCustomizable ? 'block' : 'none';
        document.getElementById('toppingsOptions').style.display = isCustomizable ? 'block' : 'none';

        // Reset form
        resetCustomizationForm();
        updateCustomizationTotal();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('customizationModal'));
        modal.show();
    }

    function resetCustomizationForm() {
        // Reset size to small
        document.getElementById('size-small').checked = true;
        
        // Reset dropdowns
        document.querySelector('select[name="sugar"]').value = '100';
        document.querySelector('select[name="ice"]').value = 'normal-ice';
        document.querySelector('select[name="milk"]').value = 'regular';
        
        // Reset checkboxes
        document.querySelectorAll('input[name="toppings"]').forEach(cb => cb.checked = false);
        
        // Reset textarea
        document.querySelector('textarea[name="instructions"]').value = '';
    }

    function updateCustomizationTotal() {
        let total;
        
        // Handle coffee items differently - use size as base price
        if (currentProduct.category === 'Coffee') {
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
            total = currentProduct.price || 0;
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
    }

    // Update total when options change
    document.querySelectorAll('#customizationModal input, #customizationModal select').forEach(input => {
        input.addEventListener('change', updateCustomizationTotal);
    });

    // Confirm customization and add to cart
    document.getElementById('confirmCustomization').addEventListener('click', function() {
        const customizedProduct = {
            ...currentProduct,
            id: `${currentProduct.id}_${Date.now()}`, // Unique ID for customized items
            customizations: getCustomizations(),
            finalPrice: parseFloat(document.getElementById('currentTotal').textContent.replace('₱', '')),
            quantity: 1
        };
        
        cart.push(customizedProduct);
        updateCartDisplay();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('customizationModal'));
        modal.hide();
    });

    function getCustomizations() {
        const customizations = {};
        
        // Get size
        const selectedSize = document.querySelector('input[name="size"]:checked');
        if (selectedSize) customizations.size = selectedSize.value;
        
        // Get sugar level
        customizations.sugar = document.querySelector('select[name="sugar"]').value;
        
        // Get ice level
        customizations.ice = document.querySelector('select[name="ice"]').value;
        
        // Get milk type
        customizations.milk = document.querySelector('select[name="milk"]').value;
        
        // Get toppings
        const selectedToppings = [];
        document.querySelectorAll('input[name="toppings"]:checked').forEach(topping => {
            selectedToppings.push(topping.value);
        });
        customizations.toppings = selectedToppings;
        
        // Get instructions
        customizations.instructions = document.querySelector('textarea[name="instructions"]').value;
        
        return customizations;
    }

    function formatCustomizations(customizations) {
        const parts = [];
        
        if (customizations.size && customizations.size !== 'small') {
            parts.push(`Size: ${customizations.size.charAt(0).toUpperCase() + customizations.size.slice(1)}`);
        }
        
        if (customizations.sugar && customizations.sugar !== '100') {
            parts.push(`Sugar: ${customizations.sugar === 'no-sugar' ? 'No Sugar' : customizations.sugar + '%'}`);
        }
        
        if (customizations.ice && customizations.ice !== 'normal-ice') {
            parts.push(`Ice: ${customizations.ice.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}`);
        }
        
        if (customizations.milk && customizations.milk !== 'regular') {
            parts.push(`Milk: ${customizations.milk.charAt(0).toUpperCase() + customizations.milk.slice(1)}`);
        }
        
        if (customizations.toppings && customizations.toppings.length > 0) {
            parts.push(`Toppings: ${customizations.toppings.map(t => t.replace('-', ' ')).join(', ')}`);
        }
        
        if (customizations.instructions && customizations.instructions.trim()) {
            parts.push(`Note: ${customizations.instructions}`);
        }
        
        return parts.join(' • ');
    }

    function updateCartDisplay() {
        const cartEmpty = document.getElementById('cart-empty');
        const cartItems = document.getElementById('cart-items');
        const orderSummary = document.getElementById('order-summary');
        const paymentSection = document.getElementById('payment-section');
        
        if (cart.length === 0) {
            cartEmpty.style.display = 'block';
            cartItems.style.display = 'none';
            orderSummary.style.display = 'none';
            paymentSection.style.display = 'none';
            return;
        }
        
        cartEmpty.style.display = 'none';
        cartItems.style.display = 'block';
        orderSummary.style.display = 'block';
        paymentSection.style.display = 'block';
        
        // Render cart items
        cartItems.innerHTML = cart.map((item, index) => `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.name}</h6>
                        ${item.customizations ? `<div class="customization-details">${formatCustomizations(item.customizations)}</div>` : ''}
                        <small class="text-muted">₱${item.finalPrice ? item.finalPrice.toFixed(2) : item.price.toFixed(2)} each</small>
                    </div>
                    <div class="quantity-controls">
                        <div class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</div>
                        <span class="quantity">${item.quantity}</span>
                        <div class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="fw-bold">₱${((item.finalPrice || item.price) * item.quantity).toFixed(2)}</span>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
        
        // Update totals
        updateTotals();
        // Recalculate change when cart updates
        calculateChange();
    }

    window.updateQuantity = function(index, change) {
        if (cart[index]) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                removeFromCart(index);
            } else {
                updateCartDisplay();
            }
        }
    };

    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        updateCartDisplay();
    };

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + ((item.finalPrice || item.price) * item.quantity), 0);
        currentTotal = subtotal; // No tax calculation
        
        document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('total').textContent = `₱${subtotal.toFixed(2)}`;
    }

    // Payment mode toggle function
    window.toggleCashPayment = function() {
        const paymentMode = document.getElementById('paymentMode').value;
        const cashSection = document.getElementById('cashPaymentSection');
        const changeDisplay = document.getElementById('changeDisplay');
        
        if (paymentMode === 'cash') {
            cashSection.style.display = 'block';
            calculateChange(); // Recalculate if there's already a value
        } else {
            cashSection.style.display = 'none';
            changeDisplay.style.display = 'none';
            document.getElementById('customerPayment').value = '';
        }
    };

    // Calculate change function
    window.calculateChange = function() {
        const paymentMode = document.getElementById('paymentMode').value;
        const customerPayment = parseFloat(document.getElementById('customerPayment').value) || 0;
        const changeDisplay = document.getElementById('changeDisplay');
        const changeAmount = document.getElementById('changeAmount');
        
        if (paymentMode === 'cash' && customerPayment > 0 && currentTotal > 0) {
            const change = customerPayment - currentTotal;
            
            if (change >= 0) {
                changeAmount.textContent = `₱${change.toFixed(2)}`;
                changeAmount.className = 'fw-bold change-positive';
                changeDisplay.style.display = 'block';
            } else {
                changeAmount.textContent = `₱${Math.abs(change).toFixed(2)} (Insufficient)`;
                changeAmount.className = 'fw-bold change-negative';
                changeDisplay.style.display = 'block';
            }
        } else {
            changeDisplay.style.display = 'none';
        }
    };

    // Clear cart
    document.getElementById('clear-cart').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear the cart?')) {
            cart = [];
            document.getElementById('customerPayment').value = '';
            document.getElementById('paymentMode').value = 'cash';
            toggleCashPayment();
            updateCartDisplay();
        }
    });

    // Process order
    document.getElementById('process-order').addEventListener('click', function() {
        const customerName = document.getElementById('customerName').value;
        const orderType = document.getElementById('orderType').value;
        const paymentMode = document.getElementById('paymentMode').value;
        const customerPayment = parseFloat(document.getElementById('customerPayment').value) || 0;
        
        if (!customerName.trim()) {
            alert('Please enter customer name');
            return;
        }
        
        if (cart.length === 0) {
            alert('Cart is empty');
            return;
        }

        // Validate cash payment
        if (paymentMode === 'cash' && customerPayment < currentTotal) {
            alert('Insufficient payment amount!');
            return;
        }
        
        const orderData = {
            customer_name: customerName,
            order_type: orderType,
            payment_mode: paymentMode,
            customer_payment: paymentMode === 'cash' ? customerPayment : currentTotal,
            change_amount: paymentMode === 'cash' ? (customerPayment - currentTotal) : 0,
            items: cart,
            subtotal: currentTotal,
            total: currentTotal
        };
        
        // Here you would send the order to your backend
        console.log('Order Data:', orderData);
        
        // For now, just show a success message
        alert('Order processed successfully!');
        
        // Clear the cart and form
        cart = [];
        document.getElementById('customerName').value = '';
        document.getElementById('customerPayment').value = '';
        document.getElementById('paymentMode').value = 'cash';
        toggleCashPayment();
        updateCartDisplay();
    });
});
</script>
@endsection