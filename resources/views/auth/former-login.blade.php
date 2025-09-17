<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - Librewhan Cafe</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <style>
      body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        font-family: 'Public Sans', sans-serif;
        overflow: hidden;
      }
      
      .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        position: relative;
        background: radial-gradient(circle at 50% 50%, rgba(255, 193, 7, 0.1) 0%, transparent 70%);
      }
      
      .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-opacity='0.03'%3E%3Cpolygon fill='%23ffc107' points='50 0 60 40 100 50 60 60 50 100 40 60 0 50 40 40'/%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.1;
      }
      
      .login-card {
        background: #2a2a2a;
        border: 1px solid #444;
        border-radius: 15px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        padding: 40px;
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 1;
      }
      
      .login-header {
        text-align: center;
        margin-bottom: 30px;
      }
      
      .cafe-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ffc107, #e49b0f);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 24px;
        color: #1a1a1a;
      }
      
      .login-title {
        color: #fff;
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 10px;
      }
      
      .login-subtitle {
        color: #aaa;
        font-size: 16px;
        margin-bottom: 0;
      }

      .role-card {
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .role-option {
        border: 2px solid #444;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: #333;
      }

      .role-option:hover {
        border-color: #ffc107;
        background: #3a3a3a;
        transform: translateY(-3px);
      }

      .role-option.selected {
        border-color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
      }

      .demo-credentials {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
      }

      .demo-credentials h6 {
        color: #ffc107;
        margin-bottom: 8px;
        font-size: 14px;
      }

      .demo-credentials p {
        color: #ccc;
        margin-bottom: 5px;
        font-size: 13px;
      }

      .form-label {
        color: #ccc;
        font-weight: 500;
        margin-bottom: 8px;
      }

      .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid #555;
        border-radius: 8px;
        color: #fff;
        padding: 12px 16px;
        font-size: 14px;
      }

      .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        color: #fff;
      }

      .form-control::placeholder {
        color: #999;
      }

      .btn-warning {
        background: linear-gradient(135deg, #ffc107, #e49b0f);
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 600;
        color: #1a1a1a;
        transition: all 0.3s ease;
      }

      .btn-warning:hover {
        background: linear-gradient(135deg, #e49b0f, #d4930a);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
      }

      .btn-link {
        color: #aaa !important;
        text-decoration: none;
      }

      .btn-link:hover {
        color: #ffc107 !important;
      }

      .form-check-label {
        color: #ccc;
      }

      .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #ff6b6b;
      }
    </style>
    {{-- <style>.天使 {background-color: red;}</style> id="天使" --}}
  </head>

  <body>
    <div class="login-container">
      <div class="login-card">
        <div class="login-header">
          <div class="cafe-icon">
            <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Librewhan Cafe" style="width: 70px; height: 70px; border-radius: 8px;">
          </div>
          <h3 class="login-title">Librewhan Cafe</h3>
          <p class="login-subtitle">Choose your workspace</p>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 list-unstyled">
              @foreach ($errors->all() as $error)
                <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Role Selection -->
        <div id="roleSelection">
          <div class="row mb-4">
            <div class="col-6">
              <div class="role-card" data-role="sms" onclick="selectRole('sms')">
                <div class="role-option text-center p-3">
                  <div class="role-icon mb-3">
                    <i class="fas fa-cash-register fa-2x text-success"></i>
                  </div>
                  <h6 class="fw-bold text-white">Point of Sale</h6>
                  <small class="text-muted">Process orders & payments</small>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="role-card" data-role="inventory" onclick="selectRole('inventory')">
                <div class="role-option text-center p-3">
                  <div class="role-icon mb-3">
                    <i class="fas fa-boxes fa-2x text-primary"></i>
                  </div>
                  <h6 class="fw-bold text-white">Inventory</h6>
                  <small class="text-muted">Manage products & stock</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Login Form (Initially Hidden) -->
        <div id="loginForm" style="display: none;">
          <div class="text-center mb-4">
            <h5 id="selectedRoleTitle" class="text-warning"></h5>
            <button type="button" class="btn btn-link btn-sm" onclick="backToRoleSelection()">
              <i class="fas fa-arrow-left"></i> Back to role selection
            </button>
          </div>

          <!-- Demo Credentials Info -->
          <div class="demo-credentials">
            <h6><i class="fas fa-info-circle me-2"></i>Demo Access</h6>
            <p><strong>Email:</strong> admin@gmail.com</p>
            <p><strong>Password:</strong> 12345678</p>
          </div>

          <!-- backend modified 天使 -->
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="">

            <div class="mb-3">
              <label for="email" class="form-label" value="{{ __('Email') }}">
                <i class="fas fa-envelope me-2"></i>Email Address
              </label>
              <input 
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                id="email" 
                name="email" 
                :value="old('email')"
                required 
                autofocus
                placeholder="Enter your email address"
                autocomplete="username"
              />
              @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label" value="{{ __('Password') }}">
                <i class="fas fa-lock me-2"></i>Password
              </label>
              <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                name="password" 
                required 
                placeholder="Enter your password"
                autocomplete="current-password"
              />
              @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember_me">
               {{ __('Remember me') }}
              </label>
            </div>                

            <button type="submit" class="btn btn-warning w-100 mb-3">
              <i class="fas fa-sign-in-alt me-2"></i>{{ __('Log in') }} to <span id="roleText">System</span>
            </button>

            <input type="hidden" name="role" id="selectedRole" value=""> <!-- 天使 -->
            <input type="hidden" name="workspace" id="workspaceInput" value=""> <!-- 天使 -->

            @if (Route::has('password.request'))
              <div class="text-center">
                <a class="btn btn-link btn-sm" href="{{ route('password.request') }}">
                  {{ __('Forgot your password?') }}
                </a>
              </div>
            @endif

          </form>
        </div>
      </div>
    </div>

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <script>
    function selectRole(role) {
      // Hide role selection
      document.getElementById('roleSelection').style.display = 'none';
      
      // Show login form
      document.getElementById('loginForm').style.display = 'block';
      
      // Set selected role
      document.getElementById('selectedRole').value = role;

      // Also set workspace to same value so backend sees workspace
      document.getElementById('workspaceInput').value = role; // Added by atesh
      
      // Update title and button text
      const titles = {
        'sms': 'Sales Management System Login',
        'inventory': 'Inventory Management Login'
      };
      const roleTexts = {
        'sms': 'SMS System',
        'inventory': 'Inventory System'
      };
      
      document.getElementById('selectedRoleTitle').textContent = titles[role];
      document.getElementById('roleText').textContent = roleTexts[role];
    }

    function backToRoleSelection() {
      // Show role selection
      document.getElementById('roleSelection').style.display = 'block';
      
      // Hide login form
      document.getElementById('loginForm').style.display = 'none';
      
      // Reset form
      document.getElementById('selectedRole').value = '';
    }
    </script>
  </body>
</html>
