@extends('layouts.app')

@section('title', 'Login - Librewhan Cafe')

@push('head')
<style>
  /* Page-specific styles (copied from login_new) */
  body { margin: 0; padding: 0; font-family: 'Public Sans', sans-serif; background: linear-gradient(135deg,#1a1a1a 0%,#2d2d2d 100%); }
  .login-container { min-height: 100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
  .login-card { background:#2a2a2a; border:1px solid #444; border-radius:15px; box-shadow:0 25px 50px rgba(0,0,0,.5); padding:40px; width:100%; max-width:450px; position:relative; color:#fff; }
  .login-header { text-align:center; margin-bottom:30px; }
  .cafe-icon { width:60px; height:60px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin:0 auto 10px; background:linear-gradient(135deg,#ffc107,#e49b0f); }
  .login-title { color:#fff; font-weight:700; font-size:28px; margin-bottom:8px; }
  .login-subtitle { color:#aaa; font-size:14px; margin-bottom:0; }
  .role-selection { display:block; margin-bottom:20px; }
  .role-card { border:2px solid #e9ecef; border-radius:15px; padding:20px; margin-bottom:12px; cursor:pointer; transition:all .25s ease; background:#f8f9fa; color:#1a1a1a; }
  .role-card:hover { transform:translateY(-4px); border-color:#007bff; background:#e3f2fd; }
  .role-card.selected { border-color:#007bff; background:#e3f2fd; }
  .role-option { display:flex; align-items:center; gap:12px; }
  .role-icon { width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#007bff,#0056b3);color:#fff;font-size:20px; }
  .demo-credentials { background:#fff3cd;border:1px solid #ffeaa7;border-radius:10px;padding:12px;margin-bottom:15px;color:#856404; }
  .login-form { display:none; }
  .form-control.is-invalid { border-color:#dc3545; }
  .btn-warning { background:linear-gradient(135deg,#ffc107,#e49b0f); border:none; color:#111; font-weight:700; }
  @media (max-width:576px) { .login-card{ padding:24px } }
</style>
@endpush

@section('content')
<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <div class="cafe-icon">
        <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Logo" style="width:36px;height:36px;border-radius:6px;">
      </div>
      <h3 class="login-title">Kalibrewhan</h3>
      <p class="login-subtitle">Welcome!</p>
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
    <div id="roleSelection" class="role-selection">
      <div class="row g-2">
        <div class="col-6">
          <div class="role-card" data-role="sms" onclick="selectRole('sms')">
            <div class="role-option">
              <div class="role-icon"><i class="fas fa-cash-register"></i></div>
              <div>
                <h6 class="mb-0">Sales Analytics</h6>
                <small class="text-muted">Track your business growth</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="role-card" data-role="inventory" onclick="selectRole('inventory')">
            <div class="role-option">
              <div class="role-icon"><i class="fas fa-boxes"></i></div>
              <div>
                <h6 class="mb-0">Inventory Control</h6>
                <small class="text-muted">Never run out of supplies</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="demo-credentials mt-3">
        <h6><i class="fas fa-info-circle me-2"></i>Demo Access</h6>
        <p class="mb-0"><strong>Email:</strong> admin@gmail.com</p>
        <p class="mb-0"><strong>Password:</strong> 12345678</p>
      </div>
    </div>

    <!-- Login Form (hidden until role selected) -->
    <div id="loginForm" class="login-form">
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" id="selectedRole" name="role" value="">

        <div class="mb-3">
          <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
          <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" required autofocus>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
          <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Remember me</label>
          </div>
          <a href="{{ route('password.request') }}" class="text-decoration-none text-warning">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-warning w-100 mb-2"><i class="fas fa-sign-in-alt me-2"></i>Sign in →</button>
        <div class="text-center"><small class="text-muted">Select a role to begin</small></div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // show/hide role and login form
  function selectRole(role) {
    var rs = document.getElementById('roleSelection');
    if (rs) rs.style.display = 'none';
    var lf = document.getElementById('loginForm');
    if (lf) lf.style.display = 'block';
    var sel = document.getElementById('selectedRole');
    if (sel) sel.value = role;
  }
  function backToRoleSelection(){
    var rs = document.getElementById('roleSelection');
    if (rs) rs.style.display = 'block';
    var lf = document.getElementById('loginForm');
    if (lf) lf.style.display = 'none';
    var sel = document.getElementById('selectedRole');
    if (sel) sel.value = '';
  }
</script>
@endpush