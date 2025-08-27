<!DOCTYPE html>
<html>
<head>
    <title>Login - Librewhan Cafe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            margin: 0;
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
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 450px; 
            overflow: hidden; 
        }
        .logo-section { 
            background: linear-gradient(135deg, #ff6b6b, #ffa500); 
            padding: 40px; 
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
            padding: 40px; 
        }
        .role-card { 
            border: 2px solid #e9ecef; 
            border-radius: 15px; 
            padding: 25px; 
            margin-bottom: 15px; 
            cursor: pointer; 
            transition: all 0.3s; 
        }
        .role-card:hover { 
            border-color: #007bff; 
            background: #e3f2fd; 
        }
        .role-card.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }
        .role-icon { 
            width: 60px; 
            height: 60px; 
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
        #roleSelection { 
            display: none; 
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <img src="{{ asset('assets/img/main-logo.jpg') }}" alt="Librewhan Cafe" style="width: 80px; height: 80px; border-radius: 10px;">
                </div>
                <h2 class="mb-0">Librewhan Cafe</h2>
                <p class="mb-0">Cafe Management System</p>
            </div>
            
            <div class="form-section">
                <div class="demo-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Demo Access</h6>
                    <p><strong>Email:</strong> admin@gmail.com</p>
                    <p><strong>Password:</strong> 123</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error) 
                            <div>{{ $error }}</div> 
                        @endforeach
                    </div>
                @endif

                @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <div id="roleSelection">
                    <h4 class="text-center mb-4">Choose Your Workspace</h4>
                    
                    <div class="role-card sms-card" onclick="selectRole('sms')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3"><i class="fas fa-cash-register"></i></div>
                            <div>
                                <h5 class="mb-1">Sales Management System</h5>
                                <p class="mb-0 text-muted">Manage sales, orders, and transactions</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="role-card inventory-card" onclick="selectRole('inventory')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3"><i class="fas fa-boxes"></i></div>
                            <div>
                                <h5 class="mb-1">Inventory Management</h5>
                                <p class="mb-0 text-muted">Manage products, categories, and stock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Form (Step 1) -->
                <div id="loginForm">
                    <h4 class="text-center mb-4">Sign In</h4>

                    <form method="POST" action="{{ route('login') }}" id="loginFormElement">
                        @csrf
                        <input type="hidden" id="userRole" name="user_role" value="">
                        <input type="hidden" id="workspaceRole" name="workspace_role" value="">
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user-tag me-2"></i>Role</label>
                            <select class="form-select" name="role" id="roleSelect" required onchange="handleRoleChange()">
                                <option value="">Select your role</option>
                                <option value="barista">Barista</option>
                                <option value="owner">Owner</option>
                            </select>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember">
                            <label class="form-check-label">Remember me</label>
                        </div>
                        
                        <button type="button" class="btn btn-primary w-100 py-3" onclick="handleLogin()">
                            <i class="fas fa-sign-in-alt me-2"></i>SIGN IN
                        </button>
                    </form>
                </div>

                <!-- Workspace Selection for Owner (Step 2) -->
                <div id="workspaceSelection" style="display: none;">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="btn btn-outline-secondary me-3" onclick="backToLogin()">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h4 class="mb-0">Choose Workspace</h4>
                            <p class="mb-0 text-muted">Select which area you want to access</p>
                        </div>
                    </div>
                    
                    <div class="role-card sms-card" onclick="selectWorkspace('sms')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3"><i class="fas fa-cash-register"></i></div>
                            <div>
                                <h5 class="mb-1">Sales Management System</h5>
                                <p class="mb-0 text-muted">Manage sales, orders, and transactions</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="role-card inventory-card" onclick="selectWorkspace('inventory')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3"><i class="fas fa-boxes"></i></div>
                            <div>
                                <h5 class="mb-1">Inventory Management</h5>
                                <p class="mb-0 text-muted">Manage products, categories, and stock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barista Direct Access (Step 2 for Barista) -->
                <div id="baristaAccess" style="display: none;">
                    <div class="d-flex align-items-center mb-4">
                        <button type="button" class="btn btn-outline-secondary me-3" onclick="backToLogin()">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <h4 class="mb-0">Welcome Barista!</h4>
                            <p class="mb-0 text-muted">Access your Sales Management System workspace</p>
                        </div>
                    </div>
                    
                    <div class="role-card sms-card" onclick="selectWorkspace('sms')">
                        <div class="d-flex align-items-center">
                            <div class="role-icon me-3"><i class="fas fa-cash-register"></i></div>
                            <div>
                                <h5 class="mb-1">Sales Management System</h5>
                                <p class="mb-0 text-muted">Manage sales, orders, and transactions</p>
                                <div class="mt-2">
                                    <span class="badge bg-success">Your Workspace</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentEmail = '';
        let currentPassword = '';
        let currentRole = '';

        function handleRoleChange() {
            // This function can be used for any role-specific UI changes if needed
            const roleSelect = document.getElementById('roleSelect');
            console.log('Role selected:', roleSelect.value);
        }

        function handleLogin() {
            const form = document.getElementById('loginFormElement');
            const email = form.querySelector('input[name="email"]').value;
            const password = form.querySelector('input[name="password"]').value;
            const role = form.querySelector('select[name="role"]').value;

            // Validate form
            if (!email || !password || !role) {
                alert('Please fill in all fields');
                return;
            }

            // Static credential validation
            if (email !== 'admin@gmail.com' || password !== '123') {
                alert('Invalid credentials. Use admin@gmail.com / 123');
                return;
            }

            // Store values for later submission
            currentEmail = email;
            currentPassword = password;
            currentRole = role;

            // Hide login form
            document.getElementById('loginForm').style.display = 'none';

            // Show appropriate next step based on role
            if (role === 'barista') {
                document.getElementById('baristaAccess').style.display = 'block';
            } else if (role === 'owner') {
                document.getElementById('workspaceSelection').style.display = 'block';
            }
        }

        function selectWorkspace(workspace) {
            // Set form values
            document.getElementById('userRole').value = currentRole;
            document.getElementById('workspaceRole').value = workspace;
            
            // Update the original form with values
            const form = document.getElementById('loginFormElement');
            form.querySelector('input[name="email"]').value = currentEmail;
            form.querySelector('input[name="password"]').value = currentPassword;
            form.querySelector('select[name="role"]').value = currentRole;
            
            // Add workspace role as a hidden field
            let workspaceInput = form.querySelector('input[name="workspace"]');
            if (!workspaceInput) {
                workspaceInput = document.createElement('input');
                workspaceInput.type = 'hidden';
                workspaceInput.name = 'workspace';
                form.appendChild(workspaceInput);
            }
            workspaceInput.value = workspace;

            // Submit the form
            form.submit();
        }

        function backToLogin() {
            document.getElementById('workspaceSelection').style.display = 'none';
            document.getElementById('baristaAccess').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        }

        // Legacy functions (keeping for compatibility)
        function selectRole(role) {
            // This is now unused but keeping for safety
        }
        
        function backToRoleSelection() {
            // This is now unused but keeping for safety
        }

        // Auto-focus on email field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput) {
                emailInput.focus();
            }
        });
    </script>
</body>
</html>
