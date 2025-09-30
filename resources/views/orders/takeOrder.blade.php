@extends('layouts.app')

@section('title', 'Take New Order - Librewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- Page Directory -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Order Management</h3>
            <ul class="breadcrumbs mb-3">
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
                        <h4 class="card-title">My Cart</h4>
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
                    {{-- 
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
                    --}}

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
                  <input class="form-check-input" type="checkbox" name="toppings" value="creamer" id="creamer">
                  <label class="form-check-label small" for="creamer">Creamer (+₱10.00)</label>
                </div>
              </div>
              <div class="col-6">
                <div class="form-check form-check-sm">
                  <input class="form-check-input" type="checkbox" name="toppings" value="pearls" id="pearls">
                  <label class="form-check-label small" for="pearls">Tapioca Pearls (+₱20.00)</label>
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

<!-- Custom CSS for SMS -->
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

<!-- SMS JavaScript  -->
{{-- 
|
| This Javascript functions as a temporary Order Creation System,
| when submitted, it will pass it to PHP to officially process the order
|
--}}
<script>
/*
  Unified SMS / Order-taking script for takeOrder.blade.php
  - Matches your blade container IDs.
  - Replaces the previous two script blocks in the page.
  - Expects meta csrf token in layout: <meta name="csrf-token" content="{{ csrf_token() }}">
*/

(function () {
  // Calculate and update change display
  window.calculateChange = function() {
    const paymentMode = document.getElementById('paymentMode')?.value || 'cash';
    const changeAmountEl = document.getElementById('changeAmount');
    const customerPaymentEl = document.getElementById('customerPayment');
    const totalEl = document.getElementById('total');
    if (!changeAmountEl || !customerPaymentEl || !totalEl) return;
    if (paymentMode !== 'cash') {
      changeAmountEl.textContent = '₱0.00';
      return;
    }
    const total = parseFloat(totalEl.textContent.replace(/[^\d.]/g, '')) || 0;
    const paid = parseFloat(customerPaymentEl.value) || 0;
    const change = paid - total;
    changeAmountEl.textContent = `₱${currency(change >= 0 ? change : 0)}`;
    // Optionally style for negative/positive
    changeAmountEl.classList.remove('change-positive', 'change-negative');
    if (change > 0) changeAmountEl.classList.add('change-positive');
    else if (change < 0) changeAmountEl.classList.add('change-negative');
  };
  const productsApiUrl = "{{ url('/inventory/api/products') }}";
  const categoriesApiUrl = "{{ url('/inventory/api/products/_meta/categories') }}";

  // DOM refs (match your blade)
  const $categoryFilters = document.getElementById('category-filters');
  const $productsGrid = document.getElementById('products-grid');
  const $cartEmpty = document.getElementById('cart-empty');
  const $cartItemsContainer = document.getElementById('cart-items');
  const $subtotalEl = document.getElementById('subtotal');
  const $totalEl = document.getElementById('total');
  const $customerPayment = document.getElementById('customerPayment');
  const $processOrderBtn = document.getElementById('process-order');
  const $confirmCustomizationBtn = document.getElementById('confirmCustomization');
  const $customizationModalElem = document.getElementById('customizationModal');

  if (!($categoryFilters && $productsGrid && $cartEmpty && $cartItemsContainer && $subtotalEl && $totalEl && $processOrderBtn && $confirmCustomizationBtn)) {
    console.warn('SMS: Required containers missing. Check category-filters, products-grid, cart-empty, cart-items, subtotal, total, process-order, confirmCustomization.');
    // still continue to try (but won't function fully)
  }

  let allCategories = [];
  let allProducts = [];
  let currentProduct = null; // product object used by modal
  let cart = []; // array of items { product_id, name, qty, size, toppings, price (unit), total }

  /* --------- utilities --------- */
  function safeCategoryName(c) {
    if (!c) return '';
    if (typeof c === 'string') return c;
    return c.name || c.title || String(c.id || '');
  }

  function parsePriceTiersFromDescription(desc) {
    if (!desc) return [];
    const nums = String(desc).match(/(\d+(?:\.\d+)?)/g);
    if (!nums) return [];
    return nums.map(n => Number(n));
  }

  function currency(n) {
    if (isNaN(n)) return n;
    return Number(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function clearChildren(el) { if (!el) return; while (el.firstChild) el.removeChild(el.firstChild); }

  /* --------- fetchers --------- */
  async function fetchCategories() {
    try {
      const r = await fetch(categoriesApiUrl, { headers: { Accept: 'application/json' }});
      const json = await r.json();
      // support multiple shapes
      let cats = [];
      if (Array.isArray(json)) cats = json;
      else if (Array.isArray(json.data)) cats = json.data;
      else if (Array.isArray(json.categories)) cats = json.categories;
      else {
        // try find first array prop
        for (const k in json) if (Array.isArray(json[k])) { cats = json[k]; break; }
      }
      allCategories = cats.map(c => ({ id: c.id ?? null, name: safeCategoryName(c) })).filter(Boolean);
    } catch (err) {
      console.error('fetchCategories error', err);
      allCategories = [];
    }
  }

  async function fetchProducts() {
    try {
      const r = await fetch(productsApiUrl + '?status=active', { headers: { Accept: 'application/json' }});
      const json = await r.json();
      let items = [];
      if (Array.isArray(json)) items = json;
      else if (Array.isArray(json.data)) items = json.data;
      else if (Array.isArray(json.products)) items = json.products;
      else items = [];

      allProducts = items.map(p => {
        const categoryName = p.category ? (p.category.name ?? p.category) : (p.category_name ?? '');
        return {
          id: p.id,
          name: p.name,
          base_price: Number(p.base_price ?? p.price ?? p.basePrice ?? 0),
          description: p.description ?? '',
          category: categoryName,
          raw: p,
          price_tiers: parsePriceTiersFromDescription(p.description)
        };
      });
    } catch (err) {
      console.error('fetchProducts error', err);
      allProducts = [];
    }
  }

  /* --------- renders --------- */
  function renderCategoryFilters() {
    if (!$categoryFilters) return;
    clearChildren($categoryFilters);
    // All button with icon
    const allBtn = document.createElement('button');
    allBtn.type = 'button';
    allBtn.className = 'btn btn-outline-primary btn-sm category-filter active';
    allBtn.dataset.category = 'All';
    allBtn.innerHTML = `${determineIconHtml('all')} <span class="ms-1">All</span>`;
    $categoryFilters.appendChild(allBtn);

    allCategories.forEach(c => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'btn btn-outline-primary btn-sm category-filter';
      b.dataset.category = c.name;
      b.innerHTML = `${determineIconHtml(c.name)} <span class="ms-1">${escapeHtml(c.name)}</span>`;
      $categoryFilters.appendChild(b);
    });

    // attach events
    $categoryFilters.querySelectorAll('.category-filter').forEach(btn => {
      btn.addEventListener('click', function () {
        $categoryFilters.querySelectorAll('.category-filter').forEach(x => x.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.category;
        renderProductsGrid(cat);
      });
    });
  }

  function renderProductsGrid(category = 'All') {
    if (!$productsGrid) return;
    clearChildren($productsGrid);

    const filtered = (category === 'All') ? allProducts : allProducts.filter(p => (p.category || '') === category);
    if (!filtered.length) {
      $productsGrid.innerHTML = '<div class="text-center text-muted py-4">No products found.</div>';
      return;
    }

    // create product nodes
    filtered.forEach(product => {
      const col = document.createElement('div');
      col.className = 'col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-2 product-item';

      // price display (first tier or base price)
      const priceText = (product.price_tiers && product.price_tiers.length)
        ? `From ₱${currency(product.price_tiers[0])}`
        : `₱${currency(product.base_price)}`;

      // card
      const card = document.createElement('div');
      card.className = 'card product-card h-100';
      // attach serializable product for click handlers
      card.dataset.product = JSON.stringify({
        id: product.id,
        name: product.name,
        base_price: product.base_price,
        description: product.description,
        category: product.category,
        price_tiers: product.price_tiers
      });

      card.innerHTML = `
        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:45px">
          ${determineIconHtml(product.category)}
        </div>
        <div class="card-body text-center p-2">
          <h6 class="card-title mb-1">${escapeHtml(product.name)}</h6>
          <div class="small text-muted">${escapeHtml(priceText)}</div>
        </div>
        <div class="card-footer p-2">
          <button class="btn btn-primary btn-sm w-100 add-to-cart" data-product-id="${product.id}">
            <i class="fas fa-plus"></i> Add
          </button>
        </div>
      `;

      col.appendChild(card);
      $productsGrid.appendChild(col);

      // clicking the card (except the Add button) opens the modal
      card.addEventListener('click', function (e) {
        if (e.target.closest('.add-to-cart')) return; // handled by add button (will open modal too)
        const p = JSON.parse(this.dataset.product);
        openCustomizationModal(p);
      });

      // add button opens modal as well
      const addBtn = card.querySelector('.add-to-cart');
      addBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const p = JSON.parse(card.dataset.product);
        openCustomizationModal(p);
      });
    });
  }

  function determineIconHtml(category) {
    const cat = (category || '').toString().toLowerCase();
    // Snacks: keep their own icon
    if (cat.includes('snack')) return '<i class="fas fa-utensils fa-lg text-muted"></i>';
    // Coffee, frappe, fruit tea, milk tea: use coffee icon
    if (
      cat.includes('coffee') ||
      cat.includes('frappe') ||
      cat.includes('fruit tea') ||
      cat.includes('milk tea') ||
      cat.includes('tea')
    ) return '<i class="fas fa-coffee fa-lg text-brown"></i>';
    if (cat.includes('pastry')) return '<i class="fas fa-cookie-bite fa-lg text-warning"></i>';
    if (cat.includes('food')) return '<i class="fas fa-hamburger fa-lg text-success"></i>';
    return '<i class="fas fa-utensils fa-lg text-muted"></i>';
  }

  function escapeHtml(s) {
    if (s === undefined || s === null) return '';
    return String(s).replace(/[&<>"'`=\/]/g, function (c) {
      return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;' }[c];
    });
  }

  /* --------- customization modal confirm (add to cart) --------- */
  // When user presses "Add to Cart" inside customization modal
  $confirmCustomizationBtn.addEventListener('click', function () {
    // read values from modal (your modal markup names)
    // currentProduct assigned by openCustomizationModal
    if (!currentProduct) {
      console.warn('No product selected for customization');
      return;
    }

    // determine chosen price
    let unitPrice = currentProduct.base_price || 0;
    // if there are price_tiers, there will be inputs named 'size' with values representing index
    const selectedSizeInput = document.querySelector('input[name="size"]:checked');
    if (selectedSizeInput && Array.isArray(currentProduct.price_tiers) && currentProduct.price_tiers.length) {
      const idx = Number(selectedSizeInput.value);
      unitPrice = Number(currentProduct.price_tiers[idx] ?? currentProduct.base_price ?? unitPrice);
    }

    // milk surcharge
    const milkSel = document.querySelector('select[name="milk"]');
    if (milkSel && !milkSel.disabled && milkSel.offsetParent !== null) {
      const m = milkSel.value;
      if (m === 'almond' || m === 'oat') unitPrice += 15;
      if (m === 'soy' || m === 'coconut') unitPrice += 10;
    }

    // toppings
    const toppings = Array.from(document.querySelectorAll('input[name="toppings"]:checked')).map(cb => cb.value);
    toppings.forEach(t => {
      if (t === 'extra-shot') unitPrice += 15;
      if (t === 'whipped-cream' || t === 'vanilla-syrup' || t === 'caramel-syrup') unitPrice += 10;
      if (t === 'cinnamon') unitPrice += 5;
      if (t === 'pearls') unitPrice += 20;
      if (t === 'jelly') unitPrice += 15;
    });

    // sugar/ice/instructions
    const sugarSel = document.querySelector('select[name="sugar"]');
    const iceSel = document.querySelector('select[name="ice"]');
    const instructions = document.querySelector('textarea[name="instructions"]')?.value ?? '';

    const qty = 1; // modal doesn't have qty, use 1 (you can expand)
    // build cart item
    const item = {
      product_id: currentProduct.id,
      name: currentProduct.name,
      qty,
      size: selectedSizeInput ? (selectedSizeInput.nextElementSibling?.textContent?.trim() || selectedSizeInput.value) : null,
      toppings,
      sugar: sugarSel ? sugarSel.value : null,
      ice: iceSel ? iceSel.value : null,
      milk: milkSel ? milkSel.value : null,
      instructions,
      price: Number(unitPrice),      // **unit price**
      total: Number(unitPrice)       // total for one unit (qty multiplies later)
    };

    // add to cart (simple push). You could de-dup by product_id+size later.
    cart.push(item);
    // hide modal
    try { bootstrap.Modal.getInstance($customizationModalElem)?.hide(); } catch (e) {}
    currentProduct = null;
    renderCart();
  });

  /* --------- cart render & events --------- */
  function renderCart() {
    // show/hide
    const paymentSection = document.getElementById('payment-section');
    const changeDisplay = document.getElementById('changeDisplay');
    const orderSummary = document.getElementById('order-summary');
    const paymentMode = document.getElementById('paymentMode')?.value || 'cash';
    if (!cart.length) {
      if ($cartEmpty) $cartEmpty.style.display = '';
      if ($cartItemsContainer) { $cartItemsContainer.style.display = 'none'; $cartItemsContainer.innerHTML = ''; }
      if ($subtotalEl) $subtotalEl.textContent = `₱0.00`;
      if ($totalEl) $totalEl.textContent = `₱0.00`;
      if (paymentSection) paymentSection.style.display = 'none';
      if (changeDisplay) changeDisplay.style.display = 'none';
      if (orderSummary) orderSummary.style.display = 'none';
      return;
    }

    if ($cartEmpty) $cartEmpty.style.display = 'none';
    if ($cartItemsContainer) $cartItemsContainer.style.display = '';
    if (paymentSection) paymentSection.style.display = '';
    if (orderSummary) orderSummary.style.display = '';

    // Show change display only for cash payment
    if (changeDisplay) {
      if (paymentMode === 'cash') {
        changeDisplay.style.display = '';
        window.calculateChange();
      } else {
        changeDisplay.style.display = 'none';
        // Reset change field
        const changeAmountEl = document.getElementById('changeAmount');
        if (changeAmountEl) changeAmountEl.textContent = '₱0.00';
      }
    }

    // render items
    $cartItemsContainer.innerHTML = cart.map((item, idx) => {
      const total = item.price * item.qty;
      return `
      <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2">
        <div>
          <h6 class="mb-0">${escapeHtml(item.name)} <small class="text-muted">x${item.qty}</small></h6>
          <div class="small text-muted">${item.size ? 'Size: ' + escapeHtml(item.size) : ''} ${item.toppings && item.toppings.length ? ' | ' + escapeHtml(item.toppings.join(', ')) : ''}</div>
          <div class="small text-muted">${item.instructions ? escapeHtml(item.instructions) : ''}</div>
        </div>
        <div class="text-end">
          <div class="fw-bold text-primary mb-1">₱${currency(total)}</div>
          <div class="input-group input-group-sm mb-1" style="width: 110px; display: inline-flex;">
            <button class="btn btn-outline-secondary btn-sm btn-qty-minus" data-idx="${idx}" type="button">-</button>
            <input type="text" class="form-control text-center" value="${item.qty}" readonly style="max-width: 36px;">
            <button class="btn btn-outline-secondary btn-sm btn-qty-plus" data-idx="${idx}" type="button">+</button>
          </div>
          <button class="btn btn-sm btn-danger remove-cart-item ms-1" data-idx="${idx}"><i class="fas fa-times"></i></button>
        </div>
      </div>`;
    }).join('');

    // totals
    let subtotal = 0;
    cart.forEach(i => subtotal += i.price * i.qty);
    const total = subtotal;
    $subtotalEl.textContent = `₱${currency(subtotal)}`;
    $totalEl.textContent = `₱${currency(total)}`;

    // attach cart buttons
    $cartItemsContainer.querySelectorAll('.remove-cart-item').forEach(btn => {
      btn.addEventListener('click', function () {
        const idx = Number(this.dataset.idx);
        cart.splice(idx, 1);
        renderCart();
      });
    });
    $cartItemsContainer.querySelectorAll('.btn-qty-plus').forEach(btn => {
      btn.addEventListener('click', function () {
        const idx = Number(this.dataset.idx);
        cart[idx].qty++;
        renderCart();
      });
    });
    $cartItemsContainer.querySelectorAll('.btn-qty-minus').forEach(btn => {
      btn.addEventListener('click', function () {
        const idx = Number(this.dataset.idx);
        if (cart[idx].qty > 1) cart[idx].qty--;
        renderCart();
      });
    });
  }

  /* --------- order processing (existing handler uses /orders/store) --------- */
  $processOrderBtn.addEventListener('click', async function () {
    if (!cart.length) { alert('Cart is empty!'); return; }
  const customerName = document.getElementById('customerName')?.value || null;
  let orderType = document.getElementById('orderType')?.value;
  if (orderType !== 'dine-in' && orderType !== 'takeaway') orderType = 'dine-in';
  const paymentMode = document.getElementById('paymentMode')?.value || 'cash';
  const subtotal = parseFloat($subtotalEl.textContent.replace(/[^\d.]/g, '')) || 0;
  const total = parseFloat($totalEl.textContent.replace(/[^\d.]/g, '')) || 0;
  const amountPaid = parseFloat($customerPayment?.value) || total;
  const changeDue = amountPaid - total;

    const items = cart.map(item => ({
      product_id: item.product_id,
      name: item.name,
      size: item.size ?? null,
      toppings: item.toppings ?? [],
      sugar: item.sugar ?? null,
      ice: item.ice ?? null,
      milk: item.milk ?? null,
      instructions: item.instructions ?? null,
      price: item.price,  // per-unit
      qty: item.qty
    }));

    try {
      const res = await fetch('/orders/store', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
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
        }),
        credentials: 'same-origin'
      });
      const data = await res.json();
      if (res.ok && data.success) {
        alert('Order placed successfully! Order ID: ' + data.order_id);
        cart = [];
        renderCart();
        // reset inputs
        document.getElementById('customerName').value = '';
        document.getElementById('customerPayment').value = '';
        // optionally go to receipt: window.location = '/sales/receipt/' + data.order_id
      } else {
        console.error('Order API error', data);
        alert('Order failed: ' + (data.message || JSON.stringify(data)));
      }
    } catch (err) {
      console.error('Process order failed', err);
      alert('Order failed (network). See console.');
    }
  });

  /* --------- openCustomizationModal wrapper so currentProduct is set for confirm --------- */
  // You already have openCustomizationModal(product) in your blade. We will wrap/augment it so currentProduct gets set.
  const originalOpenCustomizationModal = window.openCustomizationModal;
  window.openCustomizationModal = function (product) {
    currentProduct = {
      id: product.id,
      name: product.name,
      base_price: Number(product.base_price ?? 0),
      price_tiers: Array.isArray(product.price_tiers) ? product.price_tiers : [],
      description: product.description ?? '',
      category: product.category ?? ''
    };


    // Modal field references
    const cat = (currentProduct.category || '').toLowerCase();
    const sizeOptionsDiv = document.getElementById('sizeOptions');
    const sugarOptionsDiv = document.getElementById('sugarOptions');
    const iceOptionsDiv = document.getElementById('iceOptions');
    const milkOptionsDiv = document.getElementById('milkOptions');
    const toppingsOptionsDiv = document.getElementById('toppingsOptions');

    if (cat.includes('snacks')) {
      // Snacks: Hide all except quantity and notes
      if (sizeOptionsDiv) sizeOptionsDiv.style.display = 'none';
      if (sugarOptionsDiv) sugarOptionsDiv.style.display = 'none';
      if (iceOptionsDiv) iceOptionsDiv.style.display = 'none';
      if (milkOptionsDiv) milkOptionsDiv.style.display = 'none';
      if (toppingsOptionsDiv) toppingsOptionsDiv.style.display = 'none';
      // Show notes
      const notesDiv = document.querySelector('textarea[name="instructions"]')?.parentElement;
      if (notesDiv) notesDiv.style.display = '';
      // Add quantity input if not present
      let qtyInput = document.getElementById('snacks-qty-input');
      if (!qtyInput) {
        qtyInput = document.createElement('div');
        qtyInput.className = 'col-12';
        qtyInput.innerHTML = `<label class="form-label fw-bold mb-1 small">Quantity</label><input type="number" class="form-control" name="snacks-qty" id="snacks-qty-input" min="1" value="1">`;
        notesDiv?.parentElement?.insertBefore(qtyInput, notesDiv);
      } else {
        qtyInput.style.display = '';
      }
  } else if (cat.includes('frappe')) {
      // Frappe: Only 12oz and 16oz, prices 99 and 109
      if (sizeOptionsDiv) {
        sizeOptionsDiv.style.display = '';
        sizeOptionsDiv.innerHTML = `
          <label class=\"form-label fw-bold mb-1 small\">Size</label>
          <div class=\"btn-group w-100 btn-group-sm\" role=\"group\">\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-12oz\" value=\"0\" checked>\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-12oz\">12oz<br><small>₱99</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-16oz\" value=\"1\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-16oz\">16oz<br><small>₱109</small></label>\n          </div>\n        `;
        currentProduct.price_tiers = [99, 109];
        currentProduct.base_price = 99;
      }
      if (sugarOptionsDiv) sugarOptionsDiv.style.display = '';
      if (iceOptionsDiv) iceOptionsDiv.style.display = '';
      if (milkOptionsDiv) milkOptionsDiv.style.display = '';
      if (toppingsOptionsDiv) toppingsOptionsDiv.style.display = '';
      // Remove snacks qty input if present
      let qtyInput = document.getElementById('snacks-qty-input');
      if (qtyInput) qtyInput.style.display = 'none';
  } else if (cat.includes('iced coffee')) {
      // Iced Coffee: Only 12oz and 16oz, prices 89 and 99
      if (sizeOptionsDiv) {
        sizeOptionsDiv.style.display = '';
        sizeOptionsDiv.innerHTML = `
          <label class=\"form-label fw-bold mb-1 small\">Size</label>
          <div class=\"btn-group w-100 btn-group-sm\" role=\"group\">\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-12oz\" value=\"0\" checked>\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-12oz\">12oz<br><small>₱89</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-16oz\" value=\"1\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-16oz\">16oz<br><small>₱99</small></label>\n          </div>\n        `;
        currentProduct.price_tiers = [89, 99];
        currentProduct.base_price = 89;
      }
      if (sugarOptionsDiv) sugarOptionsDiv.style.display = '';
      if (iceOptionsDiv) iceOptionsDiv.style.display = '';
      if (milkOptionsDiv) milkOptionsDiv.style.display = '';
      if (toppingsOptionsDiv) toppingsOptionsDiv.style.display = '';
      // Remove snacks qty input if present
      let qtyInput = document.getElementById('snacks-qty-input');
      if (qtyInput) qtyInput.style.display = 'none';
  } else if (cat.includes('hot coffee')) {
      // Hot Coffee: Only 12oz and 16oz, prices 60 and 75
      if (sizeOptionsDiv) {
        sizeOptionsDiv.style.display = '';
        sizeOptionsDiv.innerHTML = `
          <label class=\"form-label fw-bold mb-1 small\">Size</label>
          <div class=\"btn-group w-100 btn-group-sm\" role=\"group\">\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-12oz\" value=\"0\" checked>\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-12oz\">12oz<br><small>₱60</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-16oz\" value=\"1\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-16oz\">16oz<br><small>₱75</small></label>\n          </div>\n        `;
        currentProduct.price_tiers = [60, 75];
        currentProduct.base_price = 60;
      }
      if (sugarOptionsDiv) sugarOptionsDiv.style.display = '';
      if (iceOptionsDiv) iceOptionsDiv.style.display = '';
      if (milkOptionsDiv) milkOptionsDiv.style.display = '';
      if (toppingsOptionsDiv) toppingsOptionsDiv.style.display = '';
      // Remove snacks qty input if present
      let qtyInput = document.getElementById('snacks-qty-input');
      if (qtyInput) qtyInput.style.display = 'none';
    } else if (cat.includes('fruit tea') || cat.includes('milk tea')) {
      // Fruit Tea & Milk Tea: S/M/L prices 39, 49, 59
      if (sizeOptionsDiv) {
        sizeOptionsDiv.style.display = '';
        sizeOptionsDiv.innerHTML = `
          <label class=\"form-label fw-bold mb-1 small\">Size</label>
          <div class=\"btn-group w-100 btn-group-sm\" role=\"group\">\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-small\" value=\"0\" checked>\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-small\">S<br><small>₱39</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-medium\" value=\"1\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-medium\">M<br><small>₱49</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-large\" value=\"2\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-large\">L<br><small>₱59</small></label>\n          </div>\n        `;
        currentProduct.price_tiers = [39, 49, 59];
        currentProduct.base_price = 39;
      }
      if (sugarOptionsDiv) sugarOptionsDiv.style.display = '';
      if (iceOptionsDiv) iceOptionsDiv.style.display = '';
      if (milkOptionsDiv) milkOptionsDiv.style.display = '';
      if (toppingsOptionsDiv) toppingsOptionsDiv.style.display = '';
      // Remove snacks qty input if present
      let qtyInput = document.getElementById('snacks-qty-input');
      if (qtyInput) qtyInput.style.display = 'none';
    } else {
      // All other categories: Default size options
      if (sizeOptionsDiv) {
        sizeOptionsDiv.style.display = '';
        sizeOptionsDiv.innerHTML = `
          <label class=\"form-label fw-bold mb-1 small\">Size</label>
          <div class=\"btn-group w-100 btn-group-sm\" role=\"group\">\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-small\" value=\"0\" checked>\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-small\">S<br><small>₱49</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-medium\" value=\"1\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-medium\">M<br><small>₱59</small></label>\n            <input type=\"radio\" class=\"btn-check\" name=\"size\" id=\"size-large\" value=\"2\">\n            <label class=\"btn btn-outline-primary py-1\" for=\"size-large\">L<br><small>₱69</small></label>\n          </div>\n        `;
        currentProduct.price_tiers = [49, 59, 69];
        currentProduct.base_price = 49;
      }
      if (sugarOptionsDiv) sugarOptionsDiv.style.display = '';
      if (iceOptionsDiv) iceOptionsDiv.style.display = '';
      if (milkOptionsDiv) milkOptionsDiv.style.display = '';
      if (toppingsOptionsDiv) toppingsOptionsDiv.style.display = '';
      // Remove snacks qty input if present
      let qtyInput = document.getElementById('snacks-qty-input');
      if (qtyInput) qtyInput.style.display = 'none';
    }

    // call original (keeps the UI you already made)
    if (typeof originalOpenCustomizationModal === 'function') {
      originalOpenCustomizationModal(product);
    } else {
      // fallback: show modal minimal info
      try {
        document.getElementById('selectedProduct').textContent = currentProduct.name;
        document.getElementById('productPrice').textContent = `₱${currency(currentProduct.base_price)}`;
        bootstrap.Modal.getOrCreateInstance($customizationModalElem).show();
      } catch (e) {}
    }
  };

  /* --------- init: fetch and render --------- */
  async function init() {
    await fetchCategories();
    await fetchProducts();
    renderCategoryFilters();
    renderProductsGrid('All');
    renderCart();
    // wire clear cart button if present
    const clearBtn = document.getElementById('clear-cart');
    if (clearBtn) clearBtn.addEventListener('click', function () { cart = []; renderCart(); });
  }

  init();

})();
</script>

@endsection