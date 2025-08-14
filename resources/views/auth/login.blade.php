<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - Kalibrew        <h3 class="login-title">Welcome to Kalibrewhan Cafe</h3>
        <p class="text-center text-muted mb-4">Restaurant Management System</p>
        
        <!-- Demo Credentials Info -->
        <div class="alert alert-info">
          <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Demo Credentials</h6>
          <p class="mb-1"><strong>Email:</strong> admin@gmail.com</p>
          <p class="mb-0"><strong>Password:</strong> 123</p>
        </div>
        
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">Cafe</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{ asset('assets/img/kaiadmin/favicon.ico') }}"
      type="image/x-icon"
    />

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

    <!-- Custom Login Styles -->
    <style>
      body {
        background: #1a1a1a;
        min-height: 100vh;
        font-family: 'Public Sans', sans-serif;
        color: #fff;
      }
      
      .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
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
        color: #ffc107;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 0;
      }
      
      .form-label {
        color: #fff;
        font-weight: 500;
        margin-bottom: 8px;
      }
      
      .form-control {
        background: #3a3a3a;
        border: 2px solid #555;
        border-radius: 10px;
        padding: 12px 18px;
        font-size: 14px;
        color: #fff;
        transition: all 0.3s ease;
      }
      
      .form-control:focus {
        background: #404040;
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        color: #fff;
      }
      
      .form-control::placeholder {
        color: #aaa;
      }
      
      .form-check-input {
        background: #3a3a3a;
        border: 2px solid #555;
      }
      
      .form-check-input:checked {
        background: #ffc107;
        border-color: #ffc107;
      }
      
      .form-check-label {
        color: #ccc;
        font-size: 14px;
      }
      
      .btn-login {
        background: linear-gradient(135deg, #ffc107, #e49b0f);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        color: #1a1a1a;
        font-size: 15px;
      }
      
      .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(255, 193, 7, 0.4);
        background: linear-gradient(135deg, #e49b0f, #d4940e);
      }
      
      .demo-credentials {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 25px;
      }
      
      .demo-credentials h6 {
        color: #ffc107;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 14px;
      }
      
      .demo-credentials p {
        color: #fff;
        margin: 5px 0;
        font-size: 13px;
      }
      
      .demo-credentials strong {
        color: #ffc107;
      }
      
      .forgot-password {
        text-align: center;
        margin-top: 20px;
      }
      
      .forgot-password a {
        color: #ffc107;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
      }
      
      .forgot-password a:hover {
        color: #e49b0f;
        text-decoration: underline;
      }
      
      .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #ff6b7a;
        border-radius: 10px;
      }
      
      .invalid-feedback {
        color: #ff6b7a;
      }
      
      .form-control.is-invalid {
        border-color: #dc3545;
      }
      
      .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
      }
      
      /* Coffee steam animation */
      .steam {
        position: absolute;
        top: -40px;
        right: 20px;
        opacity: 0.3;
      }
      
      .steam::before,
      .steam::after {
        content: '';
        position: absolute;
        width: 2px;
        height: 20px;
        background: #ffc107;
        border-radius: 50px;
        animation: steam 2s infinite ease-in-out;
      }
      
      .steam::before {
        left: 0;
        animation-delay: 0s;
      }
      
      .steam::after {
        left: 6px;
        animation-delay: 0.5s;
      }
      
      @keyframes steam {
        0%, 100% {
          transform: translateY(0) scaleX(1);
          opacity: 0.3;
        }
        50% {
          transform: translateY(-10px) scaleX(1.5);
          opacity: 0.1;
        }
      }
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="login-card">
        <div class="steam"></div>
        
        <div class="login-header">
          <div class="cafe-icon">
            <i class="fas fa-coffee"></i>
          </div>
          <h3 class="login-title">Kalibrewhan</h3>
          <p class="login-subtitle">Cafe Management System</p>
        </div>
        
        <!-- Demo Credentials Info -->
        <div class="demo-credentials">
          <h6><i class="fas fa-info-circle me-2"></i>Demo Access</h6>
          <p><strong>Email:</strong> admin@gmail.com</p>
          <p><strong>Password:</strong> 123</p>
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
        
        <form method="POST" action="{{ route('login') }}">
          @csrf
          
          <div class="mb-3">
            <label for="email" class="form-label">
              <i class="fas fa-envelope me-2"></i>Email Address
            </label>
            <input 
              type="email" 
              class="form-control @error('email') is-invalid @enderror" 
              id="email" 
              name="email" 
              value="{{ old('email') }}" 
              required 
              autofocus
              placeholder="Enter your email address"
            />
            @error('email')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label">
              <i class="fas fa-lock me-2"></i>Password
            </label>
            <input 
              type="password" 
              class="form-control @error('password') is-invalid @enderror" 
              id="password" 
              name="password" 
              required
              placeholder="Enter your password"
            />
            @error('password')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
          
          <div class="mb-4 form-check">
            <input 
              type="checkbox" 
              class="form-check-input" 
              id="remember" 
              name="remember" 
              {{ old('remember') ? 'checked' : '' }}
            />
            <label class="form-check-label" for="remember">
              <i class="fas fa-user-check me-2"></i>Remember me
            </label>
          </div>
          
          <button type="submit" class="btn btn-primary btn-login w-100">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In to Dashboard
          </button>
        </form>
        
        @if (Route::has('password.request'))
          <div class="forgot-password">
            <a href="{{ route('password.request') }}">
              <i class="fas fa-question-circle me-1"></i>Forgot your password?
            </a>
          </div>
        @endif
      </div>
    </div>

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  </body>
</html>
