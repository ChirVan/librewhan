@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Add New Category
                    </h1>
                    <p class="text-muted mb-0">Create a new product category</p>
                </div>
                <a href="{{ route('inventory.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Categories
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags me-2"></i>
                        Category Information
                    </h6>
                </div>
                <div class="card-body">
                    <form id="categoryForm" action="{{ route('inventory.categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" 
                                   placeholder="Leave blank to auto-generate from name">
                            <small class="form-text text-muted">Used in URLs. Leave blank to auto-generate.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="display_order" class="form-label">Display Item</label>
                                <input type="number" class="form-control" id="display_order" name="display_order" 
                                       min="0" value="0">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Save Category
                            </button>
                        </div>
                        <input type="hidden" name="customization_json" id="customization_json">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customization Modal -->
<div class="modal fade" id="customizationModal" tabindex="-1" aria-labelledby="customizationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customizationModalLabel">Category Customizations</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Choose Size Preset</label>
          <div class="d-flex gap-2 mb-2">
            <button type="button" class="btn btn-outline-primary btn-sm" id="preset-sml">Small, Medium, Large</button>
            <button type="button" class="btn btn-outline-primary btn-sm" id="preset-ml">Medium, Large</button>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Sizes</label>
          <div id="sizes-list"></div>
          <button type="button" class="btn btn-sm btn-success" id="add-size">Add Size</button>
        </div>
        <div class="mb-3">
          <label class="form-label">Add-ons</label>
          <div id="addons-list"></div>
          <button type="button" class="btn btn-sm btn-success" id="add-addon">Add Add-on</button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-customization">Save Customizations</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '') // Remove special characters
        .replace(/\s+/g, '-')         // Replace spaces with hyphens
        .replace(/-+/g, '-')          // Replace multiple hyphens with single
        .trim('-');                   // Remove leading/trailing hyphens
    
    if (document.getElementById('slug').value === '') {
        document.getElementById('slug').value = slug;
    }
});

document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Category created successfully!');
            window.location.href = '{{ route("inventory.categories.index") }}';
        } else {
            alert('Error creating category. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating category. Please try again.');
    });
});

// Customization modal logic
let sizes = [];
let addons = [];

function renderSizes() {
  const list = document.getElementById('sizes-list');
  list.innerHTML = sizes.map((s, i) => `
    <div class='input-group mb-2'>
      <input type='text' class='form-control' placeholder='Size name' value='${s.name}' onchange='sizes[${i}].name=this.value'>
      <input type='number' class='form-control' placeholder='Price' value='${s.price}' min='0' step='0.01' onchange='sizes[${i}].price=this.value'>
      <button type='button' class='btn btn-danger' onclick='sizes.splice(${i},1);renderSizes();'>Remove</button>
    </div>
  `).join('');
}
function renderAddons() {
  const list = document.getElementById('addons-list');
  list.innerHTML = addons.map((a, i) => `
    <div class='input-group mb-2'>
      <input type='text' class='form-control' placeholder='Add-on name' value='${a.name}' onchange='addons[${i}].name=this.value'>
      <input type='number' class='form-control' placeholder='Price' value='${a.price}' min='0' step='0.01' onchange='addons[${i}].price=this.value'>
      <button type='button' class='btn btn-danger' onclick='addons.splice(${i},1);renderAddons();'>Remove</button>
    </div>
  `).join('');
}
document.getElementById('add-size').onclick = function() { sizes.push({name:'',price:0}); renderSizes(); };
document.getElementById('add-addon').onclick = function() { addons.push({name:'',price:0}); renderAddons(); };
document.getElementById('save-customization').onclick = function() {
  document.getElementById('customization_json').value = JSON.stringify({sizes,addons});
  bootstrap.Modal.getInstance(document.getElementById('customizationModal')).hide();
};
document.getElementById('preset-sml').onclick = function() {
  sizes = [
    {name:'Small',price:0},
    {name:'Medium',price:0},
    {name:'Large',price:0}
  ];
  renderSizes();
};
document.getElementById('preset-ml').onclick = function() {
  sizes = [
    {name:'Medium',price:0},
    {name:'Large',price:0}
  ];
  renderSizes();
};
// Render on modal open
$('#customizationModal').on('show.bs.modal', function(){ renderSizes(); renderAddons(); });
</script>
@endpush
@endsection
