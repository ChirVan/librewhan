@extends('layouts.app')

@section('title', 'Take New Order -'.config('app.name').' Cafe')

@section('content')
<div class="container">
    <div class="page-inner">

        <!-- Page Directory -->
        <div class="page-header">
            <h3 class="fw-bold mb-3">Order Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-item">
                    <p>Take New Order</p>
                </li>
            </ul>
        </div>

        <div class="row ">
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
                    <!-- Dynamic customization groups go into these DIVs -->
                    <div class="col-12" id="sizeOptions" style="display:none"></div>
                    <div class="col-12" id="milkOptions" style="display:none"></div>
                    <div class="col-12" id="toppingsOptions" style="display:none"></div>

                    <!-- Sugar & Ice Level (kept static as requested) -->
                    {{-- <div class="col-6" id="sugarOptions">
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
                    </div> --}}

                    <!-- Special Instructions (notes are required as requested) -->
                    <div class="col-12">
                        <label class="form-label fw-bold mb-1 small">Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" name="instructions" rows="2" placeholder="Special requests..." style="resize: none;" required></textarea>
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

<!-- Styles left mostly unchanged -->
<style>
.btn.btn-primary.add-to-cart, .btn.add-to-cart {
  background-color: #111 !important;
  border-color: #111 !important;
  color: #fff !important;
}

.btn.btn-outline-primary.category-filter {
  color: #111 !important;
  border-color: #111 !important;
  background-color: #fff !important;
}

.btn.btn-outline-primary.category-filter.active {
  background-color: #111 !important;
  color: #fff !important;
  border-color: #111 !important;
}

.card.product-card .card-title {
  font-size: 1rem !important;
  font-weight: 600;
  word-break: break-word;
  white-space: normal;
  margin-bottom: 0.25rem !important;
}
.card.product-card .card-body {
  padding: 0.5rem !important;
}
.card.product-card .small.text-muted {
  font-size: 0.85rem !important;
}
.card.product-card {
  min-width: 120px;
  max-width: 150px;
  border-left: 1px solid #000131;
  border-right: 2px solid #000131;
  border-bottom: 3px solid #000131;
}

@media (max-width: 767.98px) {
    .row {
        display: flex;
        flex-direction: column;
    }
    
    .col-md-4 {
        order: -1; /* Move cart to top on mobile */
    }
    
    .col-md-8 {
        order: 1; /* Move products below cart */
    }
}
</style>

<!-- SMS JavaScript - dynamic renderer version -->
<script>
(function () {
  // small helpers
  function currency(n){ return Number(n || 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}); }
  function escapeHtml(s){ if (s===undefined || s===null) return ''; return String(s).replace(/[&<>"'`=\/]/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;' })[c]); }
  function clearChildren(el){ if(!el) return; while(el.firstChild) el.removeChild(el.firstChild); }

  // DOM refs
  const AUTO_FILL_CASH_PAYMENT = false;
  const productsApiUrl = "{{ url('/inventory/api/products') }}";
  const categoriesApiUrl = "{{ url('/inventory/api/products/_meta/categories') }}";
  const $categoryFilters = document.getElementById('category-filters');
  const $productsGrid = document.getElementById('products-grid');
  const $cartEmpty = document.getElementById('cart-empty');
  const $cartItemsContainer = document.getElementById('cart-items');
  const $subtotalEl = document.getElementById('subtotal');
  const $totalEl = document.getElementById('total');
  const $processOrderBtn = document.getElementById('process-order');
  const $confirmCustomizationBtn = document.getElementById('confirmCustomization');
  const $customizationModalElem = document.getElementById('customizationModal');

  let allCategories = [];
  let allProducts = [];
  let currentProduct = null; // normalized product with customization groups
  let cart = [];

  /* --------- parse customization JSON from common places --------- */
  function parseCustomization(raw) {
    // raw may be object or string
    if (!raw) return [];
    // prefer explicit fields
    if (raw.customization && Array.isArray(raw.customization.groups)) return raw.customization.groups;
    if (raw.customizations && Array.isArray(raw.customizations.groups)) return raw.customizations.groups;
    if (raw.groups && Array.isArray(raw.groups)) return raw.groups;

    // sometimes it's already a decoded array
    if (Array.isArray(raw)) {
      // maybe raw is the groups array already
      return raw;
    }

    // if raw.customization is string JSON
    if (typeof raw.customization === 'string') {
      try {
        const parsed = JSON.parse(raw.customization);
        if (parsed && Array.isArray(parsed.groups)) return parsed.groups;
      } catch(e){}
    }

    // try fields like raw.customization_json or raw.options
    if (raw.customization_json && typeof raw.customization_json === 'string') {
      try {
        const parsed = JSON.parse(raw.customization_json);
        // If groups array, return as is
        if (parsed && Array.isArray(parsed.groups)) return parsed.groups;
        // If sizes/addons, convert to groups
        let groups = [];
        if (Array.isArray(parsed.sizes) && parsed.sizes.length) {
          groups.push({
            key: 'size', label: 'Size', type: 'single', required: true,
            choices: parsed.sizes.map((s, i) => ({ key: s.name || String(i), label: s.name || `Size ${i+1}`, price: Number(s.price ?? 0) }))
          });
        }
        if (Array.isArray(parsed.addons) && parsed.addons.length) {
          groups.push({
            key: 'toppings', label: 'Add-ons', type: 'multi', required: false,
            choices: parsed.addons.map((a, i) => ({ key: a.name || String(i), label: a.name || `Add-on ${i+1}`, price: Number(a.price ?? 0) }))
          });
        }
        if (groups.length) return groups;
      } catch(e){}
    }

    // attempt to parse description if it contains JSON object with groups
    if (typeof raw.description === 'string') {
      const s = raw.description.trim();
      // find a JSON-like substring
      const idx = s.indexOf('{');
      if (idx !== -1) {
        try {
          const candidate = s.substring(idx);
          const parsed = JSON.parse(candidate);
          if (parsed && Array.isArray(parsed.groups)) return parsed.groups;
        } catch(e){}
      }
    }

    // fallback: look for legacy fields like price_tiers in raw
    if (Array.isArray(raw.price_tiers)) {
      const choices = raw.price_tiers.map((p, i) => ({ key: String(i), label: 'Size', price: Number(p) }));
      return [{ key: 'size', label: 'Size', type: 'single', required: true, choices }];
    }

    return [];
  }

  function getGroupByKey(groups, key){
    if (!groups) return null;
    return groups.find(g => (g.key ?? '').toString().toLowerCase() === key.toString().toLowerCase());
  }

  /* --------- fetchers --------- */
  async function fetchCategories() {
    try {
      const r = await fetch(categoriesApiUrl, { headers: { Accept: 'application/json' }});
      const json = await r.json().catch(()=>({}));
      let cats = [];
      if (Array.isArray(json)) cats = json;
      else if (Array.isArray(json.data)) cats = json.data;
      else if (Array.isArray(json.categories)) cats = json.categories;
      else {
        for (const k in json) if (Array.isArray(json[k])) { cats = json[k]; break; }
      }
      allCategories = cats.map(c => ({ id: c.id ?? null, name: (c.name || c.title || String(c)) })).filter(Boolean);
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

      // normalize products
      allProducts = items.map(p => {
        const raw = p;
        // Always parse customization_json for groups
        let groups = [];
        if (p.customization_json) {
          groups = parseCustomization({ customization_json: p.customization_json });
        } else {
          groups = parseCustomization(raw);
        }
        // extract size choices if present and compute min price
        let sizeChoices = [];
        if (Array.isArray(groups)) {
          const sizeGroup = groups.find(g => (g.key || '').toLowerCase() === 'size' || (g.label || '').toLowerCase().includes('size'));
          if (sizeGroup && Array.isArray(sizeGroup.choices)) {
            sizeChoices = sizeGroup.choices.map(ch => ({
              key: ch.key ?? ch.label ?? '',
              label: ch.label ?? ch.key ?? '',
              price: Number(ch.price ?? 0)
            }));
          }
        }

        // compute min price for display
        const base_price = Number(p.base_price ?? p.price ?? 0);
        const minChoicePrice = sizeChoices.length ? Math.min(...sizeChoices.map(c=>c.price)) : null;
        const displayPrice = minChoicePrice !== null ? minChoicePrice : base_price;

        return {
          id: p.id,
          name: p.name,
          base_price: base_price,
          description: p.description ?? '',
          category: p.category ? (p.category.name ?? p.category) : (p.category_name ?? ''),
          raw: raw,
          customization_groups: groups,
          size_choices: sizeChoices,
          display_price: displayPrice
        };
      });
    } catch (err) {
      console.error('fetchProducts error', err);
      allProducts = [];
    }
  }

  /* --------- renderers --------- */
  function renderCategoryFilters() {
    if (!$categoryFilters) return;
    clearChildren($categoryFilters);

    const allBtn = document.createElement('button');
    allBtn.type = 'button';
    allBtn.className = 'btn btn-outline-primary btn-sm category-filter active';
    allBtn.dataset.category = 'All';
    allBtn.innerHTML = `<span class="ms-1">All</span>`;
    $categoryFilters.appendChild(allBtn);

    allCategories.forEach(c => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'btn btn-outline-primary btn-sm category-filter';
      b.dataset.category = c.name;
      b.innerHTML = `<span class="ms-1">${escapeHtml(c.name)}</span>`;
      $categoryFilters.appendChild(b);
    });

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

    filtered.forEach(product => {
      const col = document.createElement('div');
      col.className = 'col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mb-3 product-item';

      const priceText = product.size_choices && product.size_choices.length
        ? `From ₱${currency(product.display_price)}`
        : `₱${currency(product.base_price)}`;

      const card = document.createElement('div');
      card.className = 'card product-card h-100';
      card.dataset.product = JSON.stringify({
        id: product.id,
        name: product.name,
        base_price: product.base_price,
        description: product.description,
        category: product.category,
        size_choices: product.size_choices,
        customization_groups: product.customization_groups,
        raw: product.raw
      });

      card.innerHTML = `
        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:45px">
          <i class="fas fa-utensils fa-lg text-muted"></i>
        </div>
        <div class="card-body text-center p-2">
          <h6 class="card-title mb-1">${escapeHtml(product.name)}</h6>
        </div>
        <div class="card-footer p-2">
          <button class="btn add-to-cart p-0" data-product-id="${product.id}">
            <div style="background-color:blue;">${escapeHtml(priceText)}</div>
          </button>
        </div>
      `;

      col.appendChild(card);
      $productsGrid.appendChild(col);

      // open modal on click
      card.addEventListener('click', function (e) {
        if (e.target.closest('.add-to-cart')) return;
        const p = JSON.parse(this.dataset.product);
        openCustomizationModal(p);
      });

      // add button opens modal too
      const addBtn = card.querySelector('.add-to-cart');
      addBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const p = JSON.parse(card.dataset.product);
        openCustomizationModal(p);
      });
    });
  }

  /* --------- build UI for groups --------- */
  function buildGroupUI(groups) {
    // Returns { sizeHtml, milkHtml, toppingsHtml } and sets event listeners later
    const sizeGroup = getGroupByKey(groups, 'size') || groups.find(g => (g.label||'').toLowerCase().includes('size'));
    const milkGroup = getGroupByKey(groups, 'milk') || groups.find(g => (g.key||'').toLowerCase() === 'milk');
    const toppingsGroup = getGroupByKey(groups, 'toppings') || groups.find(g => (g.key||'').toLowerCase().includes('topping'));

    // size
    let sizeHtml = '';
    if (sizeGroup && Array.isArray(sizeGroup.choices) && sizeGroup.choices.length) {
      sizeHtml = `<label class="form-label fw-bold mb-1 small">${escapeHtml(sizeGroup.label || 'Size')}</label>
        <div class="btn-group w-100 btn-group-sm" role="group">`;
      sizeGroup.choices.forEach((ch, idx) => {
        const key = ch.key ?? String(idx);
        const label = ch.label ?? (`Opt ${idx+1}`);
        const price = (ch.price !== undefined) ? Number(ch.price) : 0;
        // value uses key, store price as data-price
        sizeHtml += `
          <input type="radio" class="btn-check size-choice" name="size" id="size-${escapeHtml(key)}" value="${escapeHtml(key)}" data-price="${price}" ${idx===0 ? 'checked' : ''}>
          <label class="btn btn-outline-primary py-1" for="size-${escapeHtml(key)}">${escapeHtml(label)}<br><small>₱${currency(price)}</small></label>
        `;
      });
      sizeHtml += '</div>';
    }

    // milk (single select)
    let milkHtml = '';
    if (milkGroup && Array.isArray(milkGroup.choices) && milkGroup.choices.length) {
      milkHtml = `<label class="form-label fw-bold mb-1 small">${escapeHtml(milkGroup.label || 'Milk')}</label>
        <select class="form-control form-control-sm" name="milk">`;
      milkGroup.choices.forEach((ch, idx) => {
        const key = ch.key ?? String(idx);
        const label = ch.label ?? (`Milk ${idx+1}`);
        const price = (ch.price !== undefined) ? Number(ch.price) : 0;
        milkHtml += `<option value="${escapeHtml(key)}" data-price="${price}" ${idx===0 ? 'selected' : ''}>${escapeHtml(label)}${price ? ' (+₱' + currency(price) + ')' : ''}</option>`;
      });
      milkHtml += `</select>`;
    }

    // toppings (multiple)
    let toppingsHtml = '';
    if (toppingsGroup && Array.isArray(toppingsGroup.choices) && toppingsGroup.choices.length) {
      toppingsHtml = `<label class="form-label fw-bold mb-1 small">${escapeHtml(toppingsGroup.label || 'Add-ons')}</label>
        <div class="row g-1">`;
      toppingsGroup.choices.forEach((ch, idx) => {
        const key = ch.key ?? String(idx);
        const label = ch.label ?? (`Topping ${idx+1}`);
        const price = (ch.price !== undefined) ? Number(ch.price) : 0;
        toppingsHtml += `
          <div class="col-6">
            <div class="form-check form-check-sm">
              <input class="form-check-input topping-choice" type="checkbox" name="toppings" value="${escapeHtml(key)}" id="topping-${escapeHtml(key)}" data-price="${price}">
              <label class="form-check-label small" for="topping-${escapeHtml(key)}">${escapeHtml(label)}${price ? ' (+₱' + currency(price) + ')' : ''}</label>
            </div>
          </div>`;
      });
      toppingsHtml += `</div>`;
    }

    return { sizeHtml, milkHtml, toppingsHtml };
  }

  /* --------- modal open & wiring (dynamic) --------- */
  window.openCustomizationModal = function(product) {
    // product expects fields: id,name,base_price,description,size_choices,customization_groups,raw
    // normalize
    const normalized = {
      id: product.id,
      name: product.name,
      base_price: Number(product.base_price ?? 0),
      description: product.description ?? '',
      customization_groups: product.customization_groups ?? (Array.isArray(product.size_choices) && product.size_choices.length ? [{
        key: 'size', label: 'Size', type: 'single', choices: product.size_choices.map(sc=>({ key:sc.key, label:sc.label, price: sc.price }))
      }] : []),
      raw: product.raw ?? {}
    };

    currentProduct = normalized;

    // populate header
    try {
      document.getElementById('selectedProduct').textContent = currentProduct.name;
      document.getElementById('productPrice').textContent = `₱${currency(currentProduct.base_price)}`;
    } catch(e){}

    // build group UI
    const groups = currentProduct.customization_groups || [];
    const built = buildGroupUI(groups);

    // replace size/milk/toppings blocks
    const sizeOptionsDiv = document.getElementById('sizeOptions');
    const milkOptionsDiv = document.getElementById('milkOptions');
    const toppingsOptionsDiv = document.getElementById('toppingsOptions');

    if (sizeOptionsDiv) {
      if (built.sizeHtml) {
        sizeOptionsDiv.innerHTML = built.sizeHtml;
        sizeOptionsDiv.style.display = '';
      } else {
        sizeOptionsDiv.style.display = 'none';
        sizeOptionsDiv.innerHTML = '';
      }
    }
    if (milkOptionsDiv) {
      if (built.milkHtml) {
        milkOptionsDiv.innerHTML = built.milkHtml;
        milkOptionsDiv.style.display = '';
      } else {
        milkOptionsDiv.style.display = 'none';
        milkOptionsDiv.innerHTML = '';
      }
    }
    if (toppingsOptionsDiv) {
      if (built.toppingsHtml) {
        toppingsOptionsDiv.innerHTML = built.toppingsHtml;
        toppingsOptionsDiv.style.display = '';
      } else {
        toppingsOptionsDiv.style.display = 'none';
        toppingsOptionsDiv.innerHTML = '';
      }
    }

    // show modal
    try {
      bootstrap.Modal.getOrCreateInstance($customizationModalElem).show();
    } catch(e){}

    // attach listeners to recalc total on change
    setTimeout(() => {
      // size change
      document.querySelectorAll('input.size-choice').forEach(r => r.addEventListener('change', updateModalTotal));
      // milk change
      document.querySelectorAll('#milkOptions select[name="milk"]').forEach(s => s.addEventListener('change', updateModalTotal));
      // toppings change
      document.querySelectorAll('input.topping-choice').forEach(cb => cb.addEventListener('change', updateModalTotal));
      // initial calc
      updateModalTotal();
    }, 30);
  };

  function updateModalTotal(){
    if (!currentProduct) return;
    let total = Number(currentProduct.base_price || 0);

    // size price (size radio stores data-price)
    const selectedSize = document.querySelector('input.size-choice:checked');
    if (selectedSize) {
      const p = Number(selectedSize.dataset.price ?? 0);
      // if base_price is 0 or user wants choice price to override, use choice price.
      // We'll adopt: use choice price (most product configs specify absolute prices in choices)
      total = p || total;
    }

    // milk additional price (option data-price)
    const milkSel = document.querySelector('#milkOptions select[name="milk"]');
    if (milkSel) {
      const opt = milkSel.selectedOptions[0];
      if (opt) total += Number(opt.dataset.price ?? 0);
    }

    // toppings
    document.querySelectorAll('input.topping-choice:checked').forEach(cb => {
      total += Number(cb.dataset.price ?? 0);
    });

    // update display
    const el = document.getElementById('currentTotal');
    if (el) el.textContent = `₱${currency(total)}`;
    return total;
  }

  /* --------- confirm customization -> add to cart --------- */
  $confirmCustomizationBtn.addEventListener('click', function () {
    if (!currentProduct) { console.warn('No product selected'); return; }

    // price = computed modal total
    const price = updateModalTotal() || Number(currentProduct.base_price || 0);

    const selectedSizeInput = document.querySelector('input.size-choice:checked');
    const sizeLabel = selectedSizeInput ? (selectedSizeInput.nextElementSibling?.innerText?.split('\n')[0].trim() || selectedSizeInput.value) : null;
    const sizeKey = selectedSizeInput ? selectedSizeInput.value : null;

    const milkSel = document.querySelector('#milkOptions select[name="milk"]');
    const milkKey = milkSel ? milkSel.value : null;

    const toppings = Array.from(document.querySelectorAll('input.topping-choice:checked')).map(cb => cb.value);

    // const sugarSel = document.querySelector('select[name="sugar"]');
    // const iceSel = document.querySelector('select[name="ice"]');
    const instructions = document.querySelector('textarea[name="instructions"]')?.value ?? '';

    // qty control: for now, 1 (consistent with previous)
    const qty = 1;

    const item = {
      product_id: currentProduct.id,
      name: currentProduct.name,
      qty,
      size: sizeLabel,
      size_key: sizeKey,
      toppings,
      // sugar: sugarSel ? sugarSel.value : null,
      // ice: iceSel ? iceSel.value : null,
      milk: milkKey,
      instructions,
      price: Number(price),
      total: Number(price * qty)
    };

    cart.push(item);

    // hide modal and reset currentProduct
    try { bootstrap.Modal.getInstance($customizationModalElem).hide(); } catch(e){}
    currentProduct = null;
    renderCart();
  });

  /* --------- cart rendering --------- */
  function renderCart() {
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
      // update button visibility (no cart => hide)
      updateProcessOrderVisibility();
      return;
    }

    if ($cartEmpty) $cartEmpty.style.display = 'none';
    if ($cartItemsContainer) $cartItemsContainer.style.display = '';
    if (paymentSection) paymentSection.style.display = '';
    if (orderSummary) orderSummary.style.display = '';

    if (changeDisplay) {
      if (paymentMode === 'cash') {
        changeDisplay.style.display = '';
        window.calculateChange(); // ensure change computed
      } else {
        changeDisplay.style.display = 'none';
        // Reset change field
        const changeAmountEl = document.getElementById('changeAmount');
        if (changeAmountEl) changeAmountEl.textContent = '₱0.00';
      }
    }

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

    // Optionally auto-fill the cash payment input with the total when cart appears
    if (paymentMode === 'cash' && AUTO_FILL_CASH_PAYMENT && total > 0) {
      const paymentInput = document.getElementById('customerPayment');
      if (paymentInput) {
        // only fill if empty or less than total (avoid overwriting user input)
        const current = parseFloat(paymentInput.value) || 0;
        if (!paymentInput.value || current < total) {
          paymentInput.value = total.toFixed(2);
        }
      }
    }

    // After totals and potential auto-fill, compute change and set process-button visibility
    // (calculateChange will call updateProcessOrderVisibility internally)
    window.calculateChange();

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

  /* --------- process order (unchanged) --------- */
  $processOrderBtn.addEventListener('click', async function () {
    if (!cart.length) { 
      alert('Cart is empty!'); 
      return; 
    }
    
    const customerName = document.getElementById('customerName')?.value || null;
    let orderType = document.getElementById('orderType')?.value;
    if (orderType !== 'dine-in' && orderType !== 'takeaway') orderType = 'dine-in';
    const paymentMode = document.getElementById('paymentMode')?.value || 'cash';
    const subtotal = parseFloat($subtotalEl.textContent.replace(/[^\d.]/g, '')) || 0;
    const total = parseFloat($totalEl.textContent.replace(/[^\d.]/g, '')) || 0;
    
    // Enhanced payment validation for cash
    if (paymentMode === 'cash') {
      const amountPaidInput = document.getElementById('customerPayment');
      const amountPaid = parseFloat(amountPaidInput?.value) || 0;
      
      // Check if payment amount is entered
      if (!amountPaidInput?.value || amountPaid === 0) {
        alert('Please enter the payment amount received from customer.');
        amountPaidInput?.focus();
        return;
      }
      
      // Check if payment is sufficient
      if (amountPaid < total) {
        const shortage = total - amountPaid;
        alert(`Insufficient payment!\n\nTotal: ₱${total.toFixed(2)}\nPaid: ₱${amountPaid.toFixed(2)}\nShort by: ₱${shortage.toFixed(2)}\n\nPlease collect at least ₱${total.toFixed(2)} from the customer.`);
        amountPaidInput?.focus();
        amountPaidInput?.select(); // Highlight the input for easy correction
        return;
      }
    }
    
    // For non-cash payments, amount paid equals total
    const amountPaid = paymentMode === 'cash' 
      ? parseFloat(document.getElementById('customerPayment')?.value) || 0
      : total;
    const changeDue = amountPaid - total;

    // Rest of your code continues...
    const items = cart.map(item => ({
      product_id: item.product_id,
      name: item.name,
      size: item.size ?? null,
      toppings: item.toppings ?? [],
      sugar: item.sugar ?? null,
      ice: item.ice ?? null,
      milk: item.milk ?? null,
      instructions: item.instructions ?? null,
      price: item.price,
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
        document.getElementById('customerName').value = '';
        document.getElementById('customerPayment').value = '';
      } else {
        console.error('Order API error', data);
        alert('Order failed: ' + (data.message || JSON.stringify(data)));
      }
    } catch (err) {
      console.error('Process order failed', err);
      alert('Order failed (network). See console.');
    }
  });
  
  /* --------- init --------- */
  async function init() {
    await fetchCategories();
    await fetchProducts();
    renderCategoryFilters();
    renderProductsGrid('All');
    renderCart();
    const clearBtn = document.getElementById('clear-cart');
    if (clearBtn) clearBtn.addEventListener('click', function () { cart = []; renderCart(); });
  }

  init();

  /* --------- expose a small helper for dev (optional) --------- */
  window._takeOrderDebug = { allProducts, allCategories, cart };

  // ---------- Payment helpers & process-button visibility ----------
window.toggleCashPayment = function() {
  const paymentMode = document.getElementById('paymentMode')?.value || 'cash';
  const cashSection = document.getElementById('cashPaymentSection');
  const changeDisplay = document.getElementById('changeDisplay');
  if (!cashSection) return;
  if (paymentMode === 'cash') {
    cashSection.style.display = '';
    // show change area if cart has items
    if ((document.getElementById('total')?.textContent || '₱0.00') !== '₱0.00') {
      changeDisplay && (changeDisplay.style.display = '');
    }
  } else {
    cashSection.style.display = 'none';
    changeDisplay && (changeDisplay.style.display = 'none');
    // clear payment input when switching away from cash (optional)
    const paymentInput = document.getElementById('customerPayment');
    if (paymentInput) paymentInput.value = '';
  }
  // update button visibility after mode change
  updateProcessOrderVisibility();
};

// Replace existing calculateChange with this improved version
window.calculateChange = function() {
  const total = parseFloat(document.getElementById('total')?.textContent.replace(/[^\d.]/g, '')) || 0;
  const amountPaidInput = document.getElementById('customerPayment');
  const amountPaid = parseFloat(amountPaidInput?.value) || 0;
  const change = amountPaid - total;

  const changeAmountEl = document.getElementById('changeAmount');
  const changeDisplay = document.getElementById('changeDisplay');
  if (changeAmountEl && changeDisplay) {
    // show negative with minus sign, positive as usual, zero as 0.00
    if (change < 0) {
      // changeAmountEl.textContent = `-₱${Math.abs(change).toFixed(2)}`;
      changeAmountEl.textContent = '⚠️ ₱' + Math.abs(change).toFixed(2) + ' short ⚠️';
      changeAmountEl.classList.remove('text-success');
      changeAmountEl.classList.add('text-danger');
      changeDisplay.classList.remove('border-success');
      changeDisplay.classList.add('border-danger');
    } else if (change > 0) {
      changeAmountEl.textContent = `₱${change.toFixed(2)}`;
      changeAmountEl.classList.remove('text-danger');
      changeAmountEl.classList.add('text-success');
      changeDisplay.classList.remove('border-danger');
      changeDisplay.classList.add('border-success');
    } else {
      changeAmountEl.textContent = `₱${(0).toFixed(2)}`;
      changeAmountEl.classList.remove('text-danger', 'text-success');
      changeDisplay.classList.remove('border-danger','border-success');
      changeDisplay.classList.add('border-secondary');
    }
    // ensure change area visible only for cash when cart not empty
    if (document.getElementById('paymentMode')?.value === 'cash' && total > 0) {
      changeDisplay.style.display = '';
    } else {
      changeDisplay.style.display = 'none';
    }
  }

  // finally update process button visibility
  updateProcessOrderVisibility();
  };

  // show/hide the Process Order button based on payment sufficiency
  function updateProcessOrderVisibility() {
    const processBtn = document.getElementById('process-order');
    if (!processBtn) return;

    const total = parseFloat(document.getElementById('total')?.textContent.replace(/[^\d.]/g, '')) || 0;
    const paymentMode = document.getElementById('paymentMode')?.value || 'cash';

    if (paymentMode === 'cash') {
      const amountPaid = parseFloat(document.getElementById('customerPayment')?.value) || 0;
      // Hide when insufficient, show when sufficient
      if (amountPaid < total) {
        processBtn.style.display = 'none';
      } else {
        processBtn.style.display = ''; // show
      }
    } else {
      // non-cash payments: always show the button (they pay later / gateway)
      processBtn.style.display = '';
    }
  }

  // wire listeners so changes instantly affect the button
  (function wirePaymentListeners() {
    const paymentInput = document.getElementById('customerPayment');
    const paymentModeSel = document.getElementById('paymentMode');

    if (paymentInput) {
      paymentInput.addEventListener('input', function() {
        // recalc change and update process btn visibility
        window.calculateChange();
      });
    }

    if (paymentModeSel) {
      paymentModeSel.addEventListener('change', function() {
        window.toggleCashPayment();
      });
    }

    // ensure initial visibility correct on page load / cart render
    document.addEventListener('DOMContentLoaded', function() {
      window.toggleCashPayment();
      window.calculateChange();
    });
  })();


})();
</script>

@endsection
