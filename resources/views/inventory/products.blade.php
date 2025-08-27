@extends('layouts.app')
@section('title','Products Management - Librewhan Cafe')
@section('content')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Products Management</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item"><a href="{{ route('inventory.categories') }}">Categories</a></li>
        <li class="separator"><i class="icon-arrow-right"></i></li>
        <li class="nav-item">Products</li>
      </ul>
    </div>

    <div class="row mb-4" id="product-stats-row">
      <div class="col-lg-3 col-md-6 mb-3"><div class="card card-stats card-round h-100"><div class="card-body d-flex align-items-center"><div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-box"></i></div></div><div class="ms-3"><p class="card-category mb-0 small text-uppercase">Total Products</p><h4 class="card-title mb-0" id="stat-total-products">0</h4></div></div></div></div>
      <div class="col-lg-3 col-md-6 mb-3"><div class="card card-stats card-round h-100"><div class="card-body d-flex align-items-center"><div class="col-icon"><div class="icon-big text-center icon-success bubble-shadow-small"><i class="fas fa-check"></i></div></div><div class="ms-3"><p class="card-category mb-0 small text-uppercase">Active</p><h4 class="card-title mb-0" id="stat-active-products">0</h4></div></div></div></div>
      <div class="col-lg-3 col-md-6 mb-3"><div class="card card-stats card-round h-100"><div class="card-body d-flex align-items-center"><div class="col-icon"><div class="icon-big text-center icon-warning bubble-shadow-small"><i class="fas fa-exclamation-triangle"></i></div></div><div class="ms-3"><p class="card-category mb-0 small text-uppercase">Low Stock</p><h4 class="card-title mb-0" id="stat-low-stock">0</h4></div></div></div></div>
      <div class="col-lg-3 col-md-6 mb-3"><div class="card card-stats card-round h-100"><div class="card-body d-flex align-items-center"><div class="col-icon"><div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-layer-group"></i></div></div><div class="ms-3"><p class="card-category mb-0 small text-uppercase">Categories</p><h4 class="card-title mb-0" id="stat-categories">0</h4></div></div></div></div>
    </div>

    <div class="card mb-3"><div class="card-body py-3"><div class="row g-3 align-items-end">
      <div class="col-md-3"><label class="form-label small">Search</label><input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Name or SKU"></div>
      <div class="col-md-3"><label class="form-label small">Category</label><select id="categoryFilter" class="form-control form-control-sm"><option value="all">All Categories</option></select></div>
      <div class="col-md-2"><label class="form-label small">Status</label><select id="statusFilter" class="form-control form-control-sm"><option value="all">All</option><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
      <div class="col-md-2"><label class="form-label small">Sort</label><select id="sortFilter" class="form-control form-control-sm"><option value="name">Name A-Z</option><option value="name-desc">Name Z-A</option><option value="stock-desc">Stock High-Low</option><option value="stock">Stock Low-High</option></select></div>
      <div class="col-md-2 d-flex gap-2"><button class="btn btn-primary btn-sm w-100" id="addProductBtn" type="button"><i class="fas fa-plus"></i> Add</button><button class="btn btn-secondary btn-sm w-100" id="refreshBtn" type="button"><i class="fas fa-sync"></i></button></div>
    </div></div></div>

    <div class="card"><div class="card-header d-flex justify-content-between align-items-center"><h4 class="card-title mb-0">Products</h4><div class="small text-muted" id="productCount">0</div></div><div class="card-body"><div class="table-responsive"><table class="table table-striped table-hover mb-0"><thead><tr><th>Name / SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Custom</th><th>Updated</th><th style="width:140px;">Actions</th></tr></thead><tbody id="productsTableBody"></tbody></table></div></div></div>
  </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="productModalTitle">Add Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="productForm" class="row g-3" autocomplete="off">
  <div class="col-md-6"><label class="form-label">Name *</label><input type="text" class="form-control" name="name" id="productName" required></div>
  <div class="col-md-3"><label class="form-label">SKU *</label><input type="text" class="form-control" name="sku" id="productSKU" required></div>
  <div class="col-md-3"><label class="form-label">Category *</label><select class="form-control" name="category_id" id="productCategory" required></select></div>
  <div class="col-md-3"><label class="form-label">Base Price *</label><input type="number" step="0.01" min="0" class="form-control" name="base_price" id="productPrice" required></div>
  <div class="col-md-3"><label class="form-label">Low Stock Alert *</label><input type="number" min="0" class="form-control" name="low_stock_alert" id="productLowStock" required></div>
  <div class="col-md-3"><label class="form-label">Current Stock *</label><input type="number" min="0" class="form-control" name="current_stock" id="productStock" required></div>
  <div class="col-md-3"><label class="form-label">Status *</label><select class="form-control" name="status" id="productStatus"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
  <div class="col-md-3"><label class="form-label">Customizable</label><select class="form-control" name="customizable" id="productCustomizable"><option value="0">No</option><option value="1">Yes</option></select></div>
  <div class="col-md-3"><label class="form-label">Display Order</label><input type="number" min="0" class="form-control" name="display_order" id="productOrder" value="0"></div>
  <div class="col-md-9"><label class="form-label">Description</label><textarea class="form-control" rows="3" name="description" id="productDescription"></textarea></div>
  <input type="hidden" name="slug" id="productSlug">
</form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="button" class="btn btn-primary" id="saveProductBtn">Save</button></div></div></div></div>
<div class="modal fade" id="stockModal" tabindex="-1"><!-- placeholder for future advanced stock modal, simple stock handled in script --></div>
<div class="modal fade" id="deleteModal" tabindex="-1"><!-- placeholder for future confirm modal, simple confirm handled in script --></div>

<style>
.status-badge{font-size:.65rem;padding:4px 6px;border-radius:10px;font-weight:600;text-transform:uppercase}
.status-active{background:#d4edda;color:#155724}
.status-inactive{background:#f8d7da;color:#721c24}
.stock-low{color:#856404;font-weight:600}
.stock-out{color:#721c24;font-weight:600}
.stock-normal{color:#155724;font-weight:600}
.btn-action{font-size:.7rem;padding:4px 6px}
</style>

<script>
(()=>{
    let allProducts=[];let editingId=null;let categories=[];let currentCategoryFilter='all';
    const productsBaseUrl = "{{ url('/inventory/api/products') }}";
    const qs=s=>document.querySelector(s);
    const paramCat=new URLSearchParams(location.search).get('category_id');if(paramCat)currentCategoryFilter=paramCat;
    document.addEventListener('DOMContentLoaded',init);
    function init(){loadCategories().then(loadProducts);bind();}
    function bind(){qs('#searchInput').addEventListener('input',debounce(loadProducts,300));qs('#categoryFilter').addEventListener('change',()=>{currentCategoryFilter=qs('#categoryFilter').value;loadProducts();});qs('#statusFilter').addEventListener('change',loadProducts);qs('#sortFilter').addEventListener('change',loadProducts);qs('#addProductBtn').addEventListener('click',showAddModal);qs('#refreshBtn').addEventListener('click',loadProducts);qs('#saveProductBtn').addEventListener('click',saveProduct);qs('#applyStockBtn')&&qs('#applyStockBtn').addEventListener('click',applyStockChange);qs('#confirmDeleteBtn')&&qs('#confirmDeleteBtn').addEventListener('click',doDelete);}
    function loadCategories(){return fetch("{{ route('inventory.categories.data') }}",{headers:{Accept:'application/json'}}).then(r=>r.json()).then(j=>{categories=j.data.filter(c=>c.status==='active');fillCategorySelects();});}
    function fillCategorySelects(){const filter=qs('#categoryFilter');const formSel=qs('#productCategory');filter.innerHTML='<option value="all">All Categories</option>'+categories.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');formSel.innerHTML='<option value="">-- Select --</option>'+categories.map(c=>`<option value="${c.id}">${c.name}</option>`).join('');if(currentCategoryFilter!=='all')filter.value=currentCategoryFilter;}
    function loadProducts(){const p=new URLSearchParams();const s=qs('#searchInput').value.trim();if(s)p.set('search',s);const st=qs('#statusFilter').value;if(st!=='all')p.set('status',st);if(currentCategoryFilter!=='all')p.set('category_id',currentCategoryFilter);fetch("{{ route('inventory.api.products.list') }}?"+p.toString(),{headers:{Accept:'application/json'}}).then(r=>r.json()).then(j=>{allProducts=j.data;applySort();render();updateStats();}).catch(()=>alert('Failed to load products'));}
    function applySort(){const v=qs('#sortFilter').value;allProducts.sort((a,b)=>{switch(v){case 'name-desc':return b.name.localeCompare(a.name);case 'stock':return a.current_stock-b.current_stock;case 'stock-desc':return b.current_stock-a.current_stock;default:return a.name.localeCompare(b.name);}});} 
    function render(){const tb=qs('#productsTableBody');if(!allProducts.length){tb.innerHTML='<tr><td colspan="8" class="text-center text-muted py-4">No products</td></tr>';qs('#productCount').textContent='0 items';return;}tb.innerHTML=allProducts.map(rowHtml).join('');qs('#productCount').textContent=allProducts.length+' item'+(allProducts.length>1?'s':'');}
  function rowHtml(p){
    const sc=p.current_stock===0?'stock-out':(p.current_stock<=p.low_stock_alert?'stock-low':'stock-normal');
    const updated=new Date(p.updated_at).toLocaleString();
    const customizableDisplay = p.customizable ? '<i class="fas fa-check text-success" title="Yes"></i>' : '<span class="text-muted">No</span>';
    return `<tr>
      <td><div class="fw-semibold">${p.name}</div><small class="text-muted">${p.sku}</small></td>
      <td>${p.category? p.category.name:''}</td>
      <td>₱${Number(p.base_price).toFixed(2)}</td>
      <td class="${sc}">${p.current_stock}</td>
      <td><span class="status-badge status-${p.status}">${p.status}</span></td>
      <td>${customizableDisplay}</td>
      <td><small>${updated}</small></td>
      <td><div class="d-flex gap-1 flex-wrap">
        <button type="button" class="btn btn-outline-info btn-action" onclick="window.editProduct(${p.id})"><i class="fas fa-edit"></i></button>
        <button type="button" class="btn btn-outline-secondary btn-action" onclick="window.openStock(${p.id})"><i class="fas fa-box"></i></button>
        <button type="button" class="btn btn-outline-${p.status==='active'?'warning':'success'} btn-action" onclick="window.toggleStatus(${p.id})"><i class="fas fa-${p.status==='active'?'pause':'play'}"></i></button>
        <button type="button" class="btn btn-outline-danger btn-action" onclick="window.deleteProductModal(${p.id})"><i class="fas fa-trash"></i></button>
      </div></td>
    </tr>`;
  }
    function updateStats(){qs('#stat-total-products').textContent=allProducts.length;qs('#stat-active-products').textContent=allProducts.filter(p=>p.status==='active').length;qs('#stat-low-stock').textContent=allProducts.filter(p=>p.current_stock<=p.low_stock_alert).length;const set=new Set(allProducts.map(p=>p.category? p.category.name:''));set.delete('');qs('#stat-categories').textContent=set.size;}
    function showAddModal(){editingId=null;qs('#productForm').reset();qs('#productModalTitle').textContent='Add Product';qs('#saveProductBtn').disabled=false;new bootstrap.Modal(document.getElementById('productModal')).show();}
    window.editProduct=id=>{const p=allProducts.find(x=>x.id===id);if(!p)return;editingId=id;qs('#productModalTitle').textContent='Edit Product';qs('#productName').value=p.name;qs('#productSKU').value=p.sku;qs('#productCategory').value=p.category_id;qs('#productPrice').value=p.base_price;qs('#productLowStock').value=p.low_stock_alert;qs('#productStock').value=p.current_stock;qs('#productStatus').value=p.status;qs('#productCustomizable').value=p.customizable?1:0;qs('#productOrder').value=p.display_order;qs('#productDescription').value=p.description||'';qs('#saveProductBtn').disabled=false;new bootstrap.Modal(document.getElementById('productModal')).show();};
    function saveProduct(){const btn=qs('#saveProductBtn');if(btn.disabled)return;const form=document.getElementById('productForm');if(!form.reportValidity())return;btn.disabled=true;const fd=new FormData(form);const payload=Object.fromEntries(fd.entries());payload.customizable=fd.get('customizable')==='1';const method=editingId?'PUT':'POST';const url=editingId?productsBaseUrl+'/'+editingId:`{{ route('inventory.api.products.store') }}`;fetch(url,{method,headers:{Accept:'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Content-Type':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json()).then(resp=>{if(resp.success){loadProducts();bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();}else{btn.disabled=false;if(resp.errors){alert(Object.values(resp.errors).flat().join('\n'));}else alert(resp.message||'Error');}}).catch(()=>{btn.disabled=false;alert('Save failed');});}
    function applyStockChange(){/* optional simple stock modal if implemented */}
    window.toggleStatus=id=>{fetch(productsBaseUrl+'/'+id+'/toggle-status',{method:'PATCH',headers:{Accept:'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}}).then(r=>r.json()).then(()=>loadProducts());};
    window.openStock=id=>{const p=allProducts.find(x=>x.id===id);if(!p)return;const qty=prompt('Enter quantity to add/remove (prefix - to remove)');if(qty===null)return;let n=parseInt(qty,10);if(isNaN(n))return alert('Invalid');let action='add';if(n<0){action='remove';n=Math.abs(n);}fetch(productsBaseUrl+'/'+id+'/stock',{method:'PATCH',headers:{Accept:'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Content-Type':'application/json'},body:JSON.stringify({action,quantity:n})}).then(r=>r.json()).then(()=>loadProducts());};
    window.deleteProductModal=id=>{if(!confirm('Delete this product?'))return;fetch(productsBaseUrl+'/'+id,{method:'DELETE',headers:{Accept:'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}}).then(r=>r.json()).then(()=>loadProducts());};
    function doDelete(){}
    function debounce(fn,wait){let t;return(...a)=>{clearTimeout(t);t=setTimeout(()=>fn(...a),wait);};}
})();
</script>
@endsection