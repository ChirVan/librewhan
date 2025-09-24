@extends('layouts.guest') {{-- @extends('layouts.app') --}}

@section('title', 'Login - '.config('app.name'))

@push('head')
<style>
  /* Page layout */
  .login-viewport {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background: linear-gradient(135deg,#1f2a37 0%,#283443 100%);
    font-family: "Public Sans", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  }

  .auth-card {
    width: 980px;
    max-width: 96%;
    display: flex;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(4,10,25,0.5);
    background: transparent;
  }

  /* Left panel */
  .auth-left {
    flex: 1;
    min-width: 320px;
    padding: 48px 36px;
    background: linear-gradient(180deg,#16202a 0%,#1e2b38 100%);
    color: #dbe7ee;
    display: flex;
    flex-direction: column;
    gap: 18px;
    justify-content: center;
  }
  .brand-badge {
    width:60px;height:60px;border-radius:12px;
    display:inline-flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,#ffbf47,#e69b06);
    box-shadow: 0 6px 18px rgba(0,0,0,0.3);
  }
  .auth-left h2 { margin:0; font-size:22px; color:#ffffff; font-weight:700; }
  .auth-left p { margin:0; color:#9fb0bf; }

  .role-list { margin-top:18px; display:flex; flex-direction:column; gap:12px; }
  .role-item {
    display:flex; align-items:center; gap:12px;
    padding:12px 14px; border-radius:12px; background:rgba(255,255,255,0.03);
    color:#cfe6f3; cursor:pointer; transition:transform .12s, background .12s;
    border:1px solid rgba(255,255,255,0.03);
  }
  .role-item:hover { transform:translateY(-4px); background: rgba(255,255,255,0.04); }
  .role-icon { width:42px;height:42px;border-radius:10px; display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.03); color:#ca840c; font-size:18px; }

  /* Right panel (form) */
  .auth-right {
    flex: 1;
    min-width: 360px;
    padding: 34px 40px;
    background:#ffffff;
    color:#122027;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }
  .auth-right .form-title { font-size:18px; font-weight:700; margin-bottom:6px; color:#122027; }
  .auth-right .form-sub { color:#6b7b85; margin-bottom:18px; }

  .form-control {
    border-radius: 999px;
    padding: 12px 18px;
    box-shadow: none;
  }
  .form-label { font-weight:600; color:#3b4950; }
  .btn-submit {
    border-radius: 999px;
    background: linear-gradient(90deg,#122032,#0f2430);
    color: #fff;
    padding: 10px 16px;
    border: none;
    font-weight:700;
  }
  .btn-submit:hover { opacity:0.95; }

  .login-footer { margin-top:14px; text-align:center; color:#9aa9b2; font-size:13px; }

  /* demo box */
  .demo-credentials { background:#fff8e6;border:1px solid #ffe6a8;padding:10px;border-radius:10px;color:#7a5b00;font-size:13px; }

  @media (max-width: 860px) {
    .auth-card { flex-direction: column; border-radius:16px; }
    .auth-left, .auth-right { min-width: auto; padding:20px; }
    .auth-left { order:2; }
    .auth-right { order:1; }
  }
</style>
@endpush

@section('content')
<div class="login-viewport">
  <div class="auth-card">

    <!-- LEFT: brand + role selection -->
    <div class="auth-left">
      <div>
        <div class="cafe-icon">
            <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Logo" style="width:75px;height:75px;border-radius:6px;">
        </div>
        <h2>Kalibrewhan</h2>
        <p>Welcome!</p>
      </div>

      <div class="role-list">
        <div class="role-item" onclick="selectRole('sms')">
          <div class="role-icon"><i class="fas fa-receipt"></i></div>
          <div>
            <div style="font-weight:700">Order Management</div>
            <div style="font-size:13px;color:#9fb0bf">Streamline your cafe operations</div>
          </div>
        </div>

        <div class="role-item" onclick="selectRole('analytics')">
          <div class="role-icon"><i class="fas fa-chart-line"></i></div>
          <div>
            <div style="font-weight:700">Sales Analytics</div>
            <div style="font-size:13px;color:#9fb0bf">Track your business growth</div>
          </div>
        </div>

        <div class="role-item" onclick="selectRole('inventory')">
          <div class="role-icon"><i class="fas fa-boxes"></i></div>
          <div>
            <div style="font-weight:700">Inventory Control</div>
            <div style="font-size:13px;color:#9fb0bf">Never run out of supplies</div>
          </div>
        </div>
      </div>

      <div style="margin-top:18px">
        <div class="demo-credentials">
          <strong>Demo Access</strong>
          <div style="font-size:13px">Email: <code>admin@gmail.com</code> | Password: <code>12345678</code></div>
        </div>
      </div>
    </div>

    <!-- RIGHT: login form -->
    <div class="auth-right">
      <div>
        <div class="form-title">Welcome Back!</div>
        <div class="form-sub">Please sign in to continue</div>
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

      <form method="POST" action="{{ route('login') }}" class="mt-2">
        @csrf
        <input type="hidden" id="selectedRole" name="role" value="">

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input" style="border: 1px solid #ca840c;">
            <label for="remember" class="form-check-label">Remember me</label>
          </div>
          <a href="{{ route('password.request') }}" class="text-decoration-none" style="color:#ca840c;font-weight:600">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-submit w-100 mb-2"><i class="fas fa-sign-in-alt me-2"></i>Sign In</button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // preserve existing behavior: show login form after role selection
  function selectRole(role) {
    var sel = document.getElementById('selectedRole');
    if (sel) sel.value = role;

    // submit role and show form: we reveal the form area by focusing it
    // For UX we keep current behavior of showing the same form (no redirect)
    var lf = document.querySelector('.auth-right input[name="email"]');
    if (lf) lf.focus();
    // optional visual feedback: highlight chosen role (non-persistent)
    var items = document.querySelectorAll('.role-item');
    items.forEach(i => i.classList.remove('selected'));
    // find clicked element and mark (if event exists)
  }

  function backToRoleSelection(){
    var sel = document.getElementById('selectedRole');
    if (sel) sel.value = '';
  }
</script>
@endpush