<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-text text-white fw-bold fs-4">
          <i class="fas fa-coffee text-warning me-2"></i>Kalibrewhan
        </span>
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
    <!-- End Logo Header -->
  </div>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <a href="{{ route('dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">ORDER MANAGEMENT</h4>
        </li>
        
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#ordersSubmenu">
            <i class="fas fa-shopping-cart"></i>
            <p>Orders</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="ordersSubmenu">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('orders.take') }}">
                  <span class="sub-item">Take New Order</span>
                </a>
              </li>
              <li>
                <a href="{{ route('orders.pending') }}">
                  <span class="sub-item">Pending Orders</span>
                  <span class="badge badge-warning">5</span>
                </a>
              </li>
              <li>
                <a href="{{ route('orders.history') }}">
                  <span class="sub-item">Order History</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">INVENTORY</h4>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#inventory">
            <i class="fas fa-boxes"></i>
            <p>Inventory</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="inventory">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('inventory.products') }}">
                  <span class="sub-item">Products</span>
                </a>
              </li>
              <li>
                <a href="{{ route('inventory.categories') }}">
                  <span class="sub-item">Categories</span>
                </a>
              </li>
              <li>
                <a href="{{ route('inventory.stock') }}">
                  <span class="sub-item">Stock Levels</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">REPORTS & ANALYTICS</h4>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#reports">
            <i class="fas fa-chart-line"></i>
            <p>Sales Reports</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="reports">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('sales.report') }}">
                  <span class="sub-item">Total Sales</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">USER MANAGEMENT</h4>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#users">
            <i class="fas fa-users"></i>
            <p>Users</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="users">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{ route('admin.users') }}">
                  <span class="sub-item">Roles and Access</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">SETTINGS</h4>
        </li>

        <li class="nav-item">
          <a href="#settings">
            <i class="fas fa-cog"></i>
            <p>Settings</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
