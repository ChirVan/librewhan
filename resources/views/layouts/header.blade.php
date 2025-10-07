<div class="main-header">

  <div class="main-header-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="{{ route('dashboard') }}" class="logo">
        <span class="logo-text text-white fw-bold fs-4">
          <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Librewhan Cafe" class="me-2" style="width: 25px; height: 25px; border-radius: 5px;">Librewhan
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
  </div>

  <!-- Navbar Header -->
  <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid bg-neutral-300">
      <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
        <span class="fw-bold fs-5 text-dark"><h2>Librewhan <span class="text-warning">Cafe</span></h2>
      </nav>

      <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

        <li class="nav-item topbar-icon dropdown hidden-caret">
          <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-envelope"></i>
          </a>
          {{-- <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
            <li>
              <div class="dropdown-title d-flex justify-content-between align-items-center">
                Messages
                <a href="#" class="small">Mark all as read</a>
              </div>
            </li>
            <li>
              <div class="message-notif-scroll scrollbar-outer">
                <div class="notif-center">
                  <a href="#">
                    <div class="notif-img">
                      <img
                        src="{{ optional(auth()->user())->profile_photo_url ?? asset('assets/img/chadengle.jpg') }}"
                        alt="Img Profile"
                      />
                    </div>
                    <div class="notif-content">
                      <span class="subject">Jimmy Denis</span>
                      <span class="block"> How are you ? </span>
                      <span class="time">5 minutes ago</span>
                    </div>
                  </a>
                </div>
              </div>
            </li>
            <li>
              <a class="see-all" href="javascript:void(0);"
                >See all messages<i class="fa fa-angle-right"></i>
              </a>
            </li>
          </ul> --}}
        </li>

        <li class="nav-item topbar-icon dropdown hidden-caret">
          <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell"></i>
            {{-- <span class="notification">5</span> --}}
          </a>
          {{-- <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
            <li>
              <div class="dropdown-title">
                You have N new notification
              </div>
            </li>
            <li>
              <div class="notif-scroll scrollbar-outer">
                <div class="notif-center">
                  <a href="#">
                    <div class="notif-icon notif-primary">
                      <i class="fa fa-user-plus"></i>
                    </div>
                    <div class="notif-content">
                      <span class="block"> Sample Notification  </span>
                      <span class="time">NN minutes ago</span>
                    </div>
                  </a>
                </div>
              </div>
            </li>
            <li>
              <a class="see-all" href="javascript:void(0);"
                >See all notifications<i class="fa fa-angle-right"></i>
              </a>
            </li>
          </ul> --}}
        </li>

        <li class="nav-item topbar-user dropdown hidden-caret">
          <a
            class="dropdown-toggle profile-pic"
            data-bs-toggle="dropdown"
            href="#"
            aria-expanded="false"
          >
            <div class="avatar-sm">
              <img
                src="{{ optional(auth()->user())->profile_photo_url ?? asset('assets/img/chadengle.jpg') }}"
                alt="..."
                class="avatar-img rounded-circle"
              />
            </div>
            <span class="profile-username">
              <span class="op-7">Hi,</span>
              <span class="fw-bold">
                @if(session('user_role') === 'barista')
                  Barista
                @elseif(session('user_role') === 'admin')
                  admin
                @else
                  ROLE NOT SET
                @endif
              </span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    <img
                      src="{{ optional(auth()->user())->profile_photo_url ?? asset('assets/img/chadengle.jpg') }}"
                      alt="image profile"
                      class="avatar-img rounded"
                    />
                  </div>
                  <div class="u-text">
                    <h4>
                      @if(session('user_role') === 'barista')
                        Barista Account
                      @elseif(session('user_role') === 'admin')
                        Admin Account
                      @else
                        ROLE NOT SET
                      @endif
                    </h4>
                    <p class="text-muted">{{ session('user_email', 'admin@gmail.com') }}</p>
                    <div class="mb-2">
                      <span class="badge badge-{{ session('workspace_role') === 'sms' ? 'success' : 'primary' }}">
                        @if(session('workspace_role') === 'sms')
                          <i class="fas fa-cash-register me-1"></i>SMS Workspace
                        @else
                          <i class="fas fa-boxes me-1"></i>Inventory Workspace
                        @endif
                      </span>
                    </div>
                    <a
                      href="{{ route('profile.show') }}"
                      class="btn btn-xs btn-secondary btn-sm"
                      >View Profile</a
                    >
                  </div>
                </div>
              </li>
              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.show') }}">My Profile</a>
                @if((auth()->check() && auth()->user()->usertype === 'admin'))
                <div class="dropdown-divider"></div>
                <div class="dropdown-header">Switch Workspace</div>
                @if(session('workspace_role') !== 'sms')
                <a class="dropdown-item" href="{{ route('dashboard') }}?workspace=sms">
                  <i class="fas fa-cash-register me-2"></i>Sales Management System
                </a>
                @endif
                @if(session('workspace_role') !== 'inventory')
                <a class="dropdown-item" href="{{ route('dashboard') }}?workspace=inventory">
                  <i class="fas fa-boxes me-2"></i>Inventory Management
                </a>
                @endif
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Account Setting</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                  @csrf
                  <button type="submit" class="dropdown-item" style="border:none; background:none; padding:0;">
                      <i class="fas fa-sign-out-alt me-2"></i>Logout
                  </button>
              </form>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</div>