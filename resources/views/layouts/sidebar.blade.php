<div class="sidebar" data-background-color="light">
  <div class="sidebar-logo">
    <div class="logo-header" data-background-color="light">
      <a href="{{ route('orders.take') }}" class="logo">
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
      {{-- <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i> HERE'S THE CULPRIT, YOU'VE BEEN TORTURING ME BECAUSE I THOUGH YOU WERE AT THE NAV-BAR IN MOBILE YOU クソ
      </button> --}}
    </div>
  </div>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        @php
          $usertype = strtolower(session('usertype', ''));
          $userrole = strtolower(session('user_role', ''));
          $isAdmin = ($usertype === 'admin' || $userrole === 'admin');
          $workspaceRole = session('workspace_role', 'sms');
        @endphp

  @if($isAdmin && $workspaceRole === 'sms')
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Admin Dashboard</h4>
          </li>
          <li class="nav-item">
            <a href="{{ route('dashboard') }}">
              <i class="fas fa-clipboard-list"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Cafe</h4>
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
                    <span class="sub-item">Reports & Analytics</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
  @elseif($isAdmin && $workspaceRole === 'inventory')
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Admin Dashboard</h4>
          </li>
          <li class="nav-item">
            <a href="{{ route('dashboard') }}">
              <i class="fas fa-clipboard-list"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">INVENTORY</h4>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#inventorySubmenu">
              <i class="fas fa-boxes"></i>
              <p>Inventory</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="inventorySubmenu">
              <ul class="nav nav-collapse">
                <li>
                  <a href="{{ route('inventory.products.index') }}">
                    <span class="sub-item">Products</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('inventory.categories.index') }}">
                    <span class="sub-item">Categories</span>
                  </a>
                </li>
                <li>
                  <a href="{{ route('inventory.stocks.index') }}">
                    <span class="sub-item">Stock</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            
          </li>
  @elseif($usertype === 'barista' || $userrole === 'barista')

          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Cafe</h4>
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
                    <span class="sub-item">Reports & Analytics</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif
      </ul>
    </div>
  </div>
</div>

<style>
  .sidebar[data-background-color="light"], .sidebar-logo .logo-header[data-background-color="light"] {
    background-color: #111 !important;
  }
</style>