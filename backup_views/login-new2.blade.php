<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Kalibrewhan Cafe</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-section {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .form-section {
            padding: 40px 30px;
        }
        
        .role-selection {
            display: block;
        }
        
        .login-form {
            display: none;
        }
        
        .role-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .role-card:hover {
            border-color: #007bff;
            background: #e3f2fd;
            transform: translateY(-2px);
        }
        
        .role-card.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }
        
        .role-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
        
        .sms-card .role-icon {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        
        .inventory-card .role-icon {
            background: linear-gradient(135deg, #007bff, #6f42c1);
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        }
        
        .btn-outline-secondary {
            border-radius: 10px;
            border-width: 2px;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .demo-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .demo-info h6 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .demo-info p {
            color: #856404;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Librewhan Cafe" style="width: 80px; height: 80px; border-radius: 10px;">
                </div>
                <h2 class="mb-0">Librewhan Cafe</h2>
                <p class="mb-0 opacity-75">Cafe Management System</p>
            </div>
            
            <!-- Form Section -->
            <div class="form-section">
                <!-- Demo Info -->
                <div class="demo-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Demo Access</h6>
                    <p><strong>Email:</strong> chester@gmail.com</p>
                    <p><strong>Password:</strong> 12345678</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Role Selection -->
                <div id="roleSelection" class="role-selection">
                    <h4 class="text-center mb-4 text-dark">Choose Your Workspace</h4>
                    
                    <div class="role-card sms-card" onclick="selectRole('sms')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-dark">Point of Sale</h5>
                                <p class="mb-0 text-muted">Manage sales, orders, and transactions</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="role-card inventory-card" onclick="selectRole('inventory')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 text-dark">Inventory Management</h5>
                                <p class="mb-0 text-muted">Manage products, categories, and stock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Form -->
                <div id="loginForm" class="login-form">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="btn btn-outline-secondary me-3" onclick="backToRoleSelection()">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h4 class="mb-0 text-dark">Sign In</h4>
                            <p class="mb-0 text-muted">Access your <span id="selectedRoleText"></span> workspace</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" id="roleInput" name="role" value="">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label text-dark" value="{{ __('Email') }}">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label text-dark" value="{{ __('Password') }}">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label text-dark" for="remember_me">
                                <i class="fas fa-user-check me-2"></i>{{ __('Remember me') }}
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            {{-- <i class="fas fa-sign-in-alt me-2"></i>SIGN IN TO DASHBOARD --}}
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Log in') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function selectRole(role) {
            // Remove selection from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked card
            event.currentTarget.classList.add('selected');
            
            // Set role value
            document.getElementById('roleInput').value = role;
            
            // Update role text
            const roleText = role === 'sms' ? 'Sales Management System' : 'Inventory Management';
            document.getElementById('selectedRoleText').textContent = roleText;
            
            // Show login form after a short delay
            setTimeout(() => {
                document.getElementById('roleSelection').style.display = 'none';
                document.getElementById('loginForm').style.display = 'block';
            }, 300);
        }
        
        function backToRoleSelection() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('roleSelection').style.display = 'block';
            document.getElementById('roleInput').value = '';
            
            // Remove selection from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
        }
    </script>
</body>
</html>
