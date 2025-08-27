<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-text text-white fw-bold fs-4">
          <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Librewhan Cafe" class="me-2" style="width: 30px; height: 30px; border-radius: 5px;">Librewhan Cafe
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
        
        @if(session('workspace_role') !== 'inventory')
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">ORDER MANAGEMENT</h4>
        </li>
        
        <!-- Orders Section - Available for SMS workspace -->
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
        @endif

        @if(session('workspace_role') === 'inventory')
        <!-- Inventory Section - Only for Inventory workspace -->
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
                <a href="{{ route('inventory.products.index') }}">
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

        <!-- Sales Reports Section - Only for Inventory workspace -->
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
        @endif

        <!-- Settings Section - Available for both workspaces -->
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

        <!-- Workspace Switcher - Only for Owners -->
        @if(session('user_role') === 'owner')
        <li class="nav-section">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">WORKSPACE</h4>
        </li>

        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#workspace">
            <i class="fas fa-exchange-alt"></i>
            <p>Switch Workspace</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="workspace">
            <ul class="nav nav-collapse">
              @if(session('workspace_role') !== 'sms')
              <li>
                <a href="{{ route('sms.dashboard') }}">
                  <span class="sub-item">
                    <i class="fas fa-cash-register me-2"></i>Sales Management System
                  </span>
                </a>
              </li>
              @endif
              @if(session('workspace_role') !== 'inventory')
              <li>
                <a href="{{ route('inventory.dashboard') }}">
                  <span class="sub-item">
                    <i class="fas fa-boxes me-2"></i>Inventory Management
                  </span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
      </ul>
    </div>
  </div>
</div>
