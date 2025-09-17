
@extends('layouts.app')

@section('title', 'User Management - Librewhan Cafe')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">User Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Users</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
            </ul>
        </div>

        <!-- User Management Controls -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">
                                <i class="fas fa-users-cog text-primary me-2"></i>
                                User Management
                            </h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success btn-sm" id="addUserBtn">
                                    <i class="fas fa-user-plus"></i> Add New User
                                </button>
                                <button class="btn btn-outline-info btn-sm" id="bulkActionsBtn">
                                    <i class="fas fa-tasks"></i> Bulk Actions
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" id="exportUsersBtn">
                                    <i class="fas fa-download"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter Controls -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label small">Search Users</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="searchUsers" placeholder="Search by name, email...">
                                        <div class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Role</label>
                                    <select class="form-control form-control-sm" id="roleFilter">
                                        <option value="all">All Roles</option>
                                        <option value="admin">Administrator</option>
                                        <option value="manager">Manager</option>
                                        <option value="cashier">Cashier</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Status</label>
                                    <select class="form-control form-control-sm" id="statusFilter">
                                        <option value="all">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label small">Department</label>
                                    <select class="form-control form-control-sm" id="departmentFilter">
                                        <option value="all">All Departments</option>
                                        <option value="kitchen">Kitchen</option>
                                        <option value="service">Service</option>
                                        <option value="management">Management</option>
                                        <option value="finance">Finance</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2 align-items-end">
                                    <button class="btn btn-primary btn-sm" id="applyFiltersBtn">
                                        <i class="fas fa-filter"></i> Apply Filters
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="usersTable">
                                <thead>
                                    <tr>
                                        <th width="40">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Users data will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="pagination-info">
                                Showing <span id="showingFrom">1</span> to <span id="showingTo">10</span> of <span id="totalRecords">25</span> users
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination">
                                    <!-- Pagination will be loaded here -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Recent User Activities</h4>
                    </div>
                    <div class="card-body">
                        <div class="activity-list" id="recentActivities">
                            <!-- Activities will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Roles Distribution</h4>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 250px;">
                            <canvas id="rolesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Role *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin">Administrator</option>
                                    <option value="manager">Manager</option>
                                    <option value="cashier">Cashier</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Department</label>
                                <select class="form-control" id="department" name="department">
                                    <option value="">Select Department</option>
                                    <option value="kitchen">Kitchen</option>
                                    <option value="service">Service</option>
                                    <option value="management">Management</option>
                                    <option value="finance">Finance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Minimum 8 characters</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profilePicture" name="profile_picture" accept="image/*">
                        <small class="form-text text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes about the user..."></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendWelcomeEmail" name="send_welcome_email" checked>
                            <label class="form-check-label" for="sendWelcomeEmail">
                                Send welcome email to user
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">
                        <i class="fas fa-save"></i> Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- User details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editFromDetailsBtn">
                    <i class="fas fa-edit"></i> Edit User
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Select Action</label>
                    <select class="form-control" id="bulkAction">
                        <option value="">Choose action...</option>
                        <option value="activate">Activate Users</option>
                        <option value="deactivate">Deactivate Users</option>
                        <option value="suspend">Suspend Users</option>
                        <option value="delete">Delete Users</option>
                        <option value="change_role">Change Role</option>
                        <option value="change_department">Change Department</option>
                    </select>
                </div>
                <div class="form-group" id="roleChangeGroup" style="display: none;">
                    <label class="form-label">New Role</label>
                    <select class="form-control" id="newRole">
                        <option value="admin">Administrator</option>
                        <option value="manager">Manager</option>
                        <option value="cashier">Cashier</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div class="form-group" id="departmentChangeGroup" style="display: none;">
                    <label class="form-label">New Department</label>
                    <select class="form-control" id="newDepartment">
                        <option value="kitchen">Kitchen</option>
                        <option value="service">Service</option>
                        <option value="management">Management</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>
                <div class="alert alert-warning" id="bulkActionWarning" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    This action will affect <span id="selectedCount">0</span> selected users.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="executeBulkAction">
                    <i class="fas fa-check"></i> Execute Action
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.user-email {
    font-size: 0.8rem;
    color: #6c757d;
}

.role-badge {
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: #dc3545 !important;
    color: white !important;
    border: 1px solid #dc3545;
}

.role-manager {
    background: #ffc107 !important;
    color: #212529 !important;
    border: 1px solid #ffc107;
}

.role-cashier {
    background: #17a2b8 !important;
    color: white !important;
    border: 1px solid #17a2b8;
}

.role-staff {
    background: #28a745 !important;
    color: white !important;
    border: 1px solid #28a745;
}

.status-badge {
    font-size: 0.7rem;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: #28a745 !important;
    color: white !important;
    border: 1px solid #28a745;
}

.status-inactive {
    background: #6c757d !important;
    color: white !important;
    border: 1px solid #6c757d;
}

.status-suspended {
    background: #dc3545 !important;
    color: white !important;
    border: 1px solid #dc3545;
}

.department-badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 8px;
    background: #e9ecef;
    color: #495057;
    font-weight: 500;
    text-transform: capitalize;
    border: 1px solid #dee2e6;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 0.8rem;
}

.activity-content {
    flex: 1;
}

.activity-text {
    font-size: 0.85rem;
    color: #495057;
    margin-bottom: 2px;
}

.activity-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.online-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    margin-left: 5px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
}

.btn-action {
    font-size: 0.75rem;
    padding: 4px 8px;
    margin: 0 2px;
}

.last-login {
    font-size: 0.8rem;
    color: #6c757d;
}

.user-permissions {
    max-height: 200px;
    overflow-y: auto;
}

.permission-item {
    padding: 5px 0;
    border-bottom: 1px solid #f0f0f0;
}

.permission-item:last-child {
    border-bottom: none;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    font-size: 0.85rem;
    vertical-align: middle;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(45deg, #1572e8, #17a2b8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-right: 10px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample users data
    let usersData = [
        {
            id: 1,
            first_name: 'John',
            last_name: 'Doe',
            email: 'john.doe@librewhan.com',
            phone: '+1234567890',
            role: 'admin',
            department: 'management',
            status: 'active',
            last_login: '2024-01-15 10:30:00',
            avatar: null,
            created_at: '2024-01-01',
            notes: 'System administrator'
        },
        {
            id: 2,
            first_name: 'Jane',
            last_name: 'Smith',
            email: 'jane.smith@librewhan.com',
            phone: '+1234567891',
            role: 'manager',
            department: 'service',
            status: 'active',
            last_login: '2024-01-15 09:15:00',
            avatar: null,
            created_at: '2024-01-02',
            notes: 'Floor manager'
        },
        {
            id: 3,
            first_name: 'Mike',
            last_name: 'Johnson',
            email: 'mike.johnson@librewhan.com',
            phone: '+1234567892',
            role: 'cashier',
            department: 'service',
            status: 'active',
            last_login: '2024-01-15 08:45:00',
            avatar: null,
            created_at: '2024-01-03',
            notes: 'Senior cashier'
        },
        {
            id: 4,
            first_name: 'Sarah',
            last_name: 'Wilson',
            email: 'sarah.wilson@librewhan.com',
            phone: '+1234567893',
            role: 'staff',
            department: 'kitchen',
            status: 'active',
            last_login: '2024-01-14 16:20:00',
            avatar: null,
            created_at: '2024-01-04',
            notes: 'Head chef'
        },
        {
            id: 5,
            first_name: 'David',
            last_name: 'Brown',
            email: 'david.brown@librewhan.com',
            phone: '+1234567894',
            role: 'staff',
            department: 'service',
            status: 'inactive',
            last_login: '2024-01-10 14:30:00',
            avatar: null,
            created_at: '2024-01-05',
            notes: 'On leave'
        }
    ];

    let currentPage = 1;
    let recordsPerPage = 10;
    let filteredUsers = [...usersData];
    let selectedUsers = [];

    // Initialize page
    loadUsersTable();
    loadRecentActivities();
    initializeRolesChart();
    setupEventListeners();

    function setupEventListeners() {
        // Main action buttons
        document.getElementById('addUserBtn').addEventListener('click', showAddUserModal);
        document.getElementById('bulkActionsBtn').addEventListener('click', showBulkActionsModal);
        document.getElementById('exportUsersBtn').addEventListener('click', exportUsers);

        // Filter and search
        document.getElementById('searchUsers').addEventListener('input', handleSearch);
        document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
        document.getElementById('clearFiltersBtn').addEventListener('click', clearFilters);

        // Form submissions
        document.getElementById('userForm').addEventListener('submit', handleUserSubmit);
        document.getElementById('executeBulkAction').addEventListener('click', executeBulkAction);

        // Bulk action dropdown
        document.getElementById('bulkAction').addEventListener('change', handleBulkActionChange);

        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', handleSelectAll);

        // Password toggle
        document.getElementById('togglePassword').addEventListener('click', togglePasswordVisibility);

        // Edit from details modal
        document.getElementById('editFromDetailsBtn').addEventListener('click', editFromDetails);
    }

    function loadUsersTable() {
        const tbody = document.getElementById('usersTableBody');
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = startIndex + recordsPerPage;
        const paginatedUsers = filteredUsers.slice(startIndex, endIndex);

        tbody.innerHTML = paginatedUsers.map(user => `
            <tr>
                <td>
                    <div class="form-check">
                        <input class="form-check-input user-checkbox" type="checkbox" value="${user.id}" onchange="handleUserSelect(${user.id})">
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        ${user.avatar ? 
                            `<img src="${user.avatar}" alt="${user.first_name}" class="user-avatar">` :
                            `<div class="avatar-placeholder">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>`
                        }
                        <div>
                            <div class="user-name">${user.first_name} ${user.last_name}</div>
                            <div class="user-email">${user.email}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="role-badge role-${user.role}">
                        ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                    </span>
                </td>
                <td>
                    <span class="department-badge">
                        ${user.department ? user.department.charAt(0).toUpperCase() + user.department.slice(1) : 'N/A'}
                    </span>
                </td>
                <td>
                    <span class="status-badge status-${user.status}">
                        ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                        ${user.status === 'active' && isUserOnline(user.id) ? '<span class="online-indicator"></span>' : ''}
                    </span>
                </td>
                <td>
                    <div class="last-login">
                        ${user.last_login ? formatDateTime(user.last_login) : 'Never'}
                    </div>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-primary btn-action" onclick="viewUserDetails(${user.id})" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-success btn-action" onclick="editUser(${user.id})" title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-action" onclick="resetUserPassword(${user.id})" title="Reset Password">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-action" onclick="deleteUser(${user.id})" title="Delete User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        updatePagination();
        updateUserStats();
    }

    function updatePagination() {
        const totalRecords = filteredUsers.length;
        const totalPages = Math.ceil(totalRecords / recordsPerPage);
        const showingFrom = totalRecords > 0 ? (currentPage - 1) * recordsPerPage + 1 : 0;
        const showingTo = Math.min(currentPage * recordsPerPage, totalRecords);

        document.getElementById('showingFrom').textContent = showingFrom;
        document.getElementById('showingTo').textContent = showingTo;
        document.getElementById('totalRecords').textContent = totalRecords;

        // Generate pagination buttons
        const pagination = document.getElementById('pagination');
        let paginationHTML = '';

        // Previous button
        if (currentPage > 1) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a></li>`;
        }

        // Page numbers
        for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
            paginationHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>`;
        }

        // Next button
        if (currentPage < totalPages) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a></li>`;
        }

        pagination.innerHTML = paginationHTML;
    }

    function updateUserStats() {
        const totalUsers = usersData.length;
        const activeUsers = usersData.filter(user => user.status === 'active').length;
        const adminUsers = usersData.filter(user => user.role === 'admin').length;
        const onlineUsers = 8; // Mock online users count

        document.getElementById('totalUsers').textContent = totalUsers;
        document.getElementById('activeUsers').textContent = activeUsers;
        document.getElementById('adminUsers').textContent = adminUsers;
        document.getElementById('onlineUsers').textContent = onlineUsers;
    }

    function loadRecentActivities() {
        const activities = [
            {
                icon: 'fas fa-user-plus',
                iconColor: '#28a745',
                text: 'New user "Mike Johnson" was created',
                time: '2 hours ago'
            },
            {
                icon: 'fas fa-sign-in-alt',
                iconColor: '#17a2b8',
                text: 'Jane Smith logged in',
                time: '3 hours ago'
            },
            {
                icon: 'fas fa-user-edit',
                iconColor: '#ffc107',
                text: 'User "David Brown" was updated',
                time: '5 hours ago'
            },
            {
                icon: 'fas fa-key',
                iconColor: '#dc3545',
                text: 'Password reset for "Sarah Wilson"',
                time: '1 day ago'
            },
            {
                icon: 'fas fa-user-shield',
                iconColor: '#6f42c1',
                text: 'Role updated for "John Doe"',
                time: '2 days ago'
            }
        ];

        const container = document.getElementById('recentActivities');
        container.innerHTML = activities.map(activity => `
            <div class="activity-item">
                <div class="activity-icon" style="background: ${activity.iconColor}20; color: ${activity.iconColor};">
                    <i class="${activity.icon}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-text">${activity.text}</div>
                    <div class="activity-time">${activity.time}</div>
                </div>
            </div>
        `).join('');
    }

    function initializeRolesChart() {
        const ctx = document.getElementById('rolesChart').getContext('2d');
        
        const rolesCounts = {
            admin: usersData.filter(user => user.role === 'admin').length,
            manager: usersData.filter(user => user.role === 'manager').length,
            cashier: usersData.filter(user => user.role === 'cashier').length,
            staff: usersData.filter(user => user.role === 'staff').length
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Administrator', 'Manager', 'Cashier', 'Staff'],
                datasets: [{
                    data: [rolesCounts.admin, rolesCounts.manager, rolesCounts.cashier, rolesCounts.staff],
                    backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#28a745'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    function handleSearch() {
        const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
        filteredUsers = usersData.filter(user => 
            user.first_name.toLowerCase().includes(searchTerm) ||
            user.last_name.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm)
        );
        currentPage = 1;
        loadUsersTable();
    }

    function applyFilters() {
        const roleFilter = document.getElementById('roleFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const departmentFilter = document.getElementById('departmentFilter').value;

        filteredUsers = usersData.filter(user => {
            return (roleFilter === 'all' || user.role === roleFilter) &&
                   (statusFilter === 'all' || user.status === statusFilter) &&
                   (departmentFilter === 'all' || user.department === departmentFilter);
        });

        currentPage = 1;
        loadUsersTable();
    }

    function clearFilters() {
        document.getElementById('searchUsers').value = '';
        document.getElementById('roleFilter').value = 'all';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('departmentFilter').value = 'all';
        
        filteredUsers = [...usersData];
        currentPage = 1;
        loadUsersTable();
    }

    function showAddUserModal() {
        document.getElementById('userModalTitle').textContent = 'Add New User';
        document.getElementById('userForm').reset();
        document.getElementById('password').required = true;
        document.getElementById('confirmPassword').required = true;
        
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
    }

    function editUser(userId) {
        const user = usersData.find(u => u.id === userId);
        if (!user) return;

        document.getElementById('userModalTitle').textContent = 'Edit User';
        document.getElementById('firstName').value = user.first_name;
        document.getElementById('lastName').value = user.last_name;
        document.getElementById('email').value = user.email;
        document.getElementById('phone').value = user.phone || '';
        document.getElementById('role').value = user.role;
        document.getElementById('department').value = user.department || '';
        document.getElementById('status').value = user.status;
        document.getElementById('notes').value = user.notes || '';
        
        // Make password optional for editing
        document.getElementById('password').required = false;
        document.getElementById('confirmPassword').required = false;
        document.getElementById('password').placeholder = 'Leave blank to keep current password';
        
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
    }

    function viewUserDetails(userId) {
        const user = usersData.find(u => u.id === userId);
        if (!user) return;

        const detailsHTML = `
            <div class="row">
                <div class="col-md-4 text-center">
                    ${user.avatar ? 
                        `<img src="${user.avatar}" alt="${user.first_name}" class="img-fluid rounded mb-3" style="max-width: 150px;">` :
                        `<div class="avatar-placeholder mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>`
                    }
                    <h5>${user.first_name} ${user.last_name}</h5>
                    <p class="text-muted">${user.email}</p>
                    <span class="badge role-badge role-${user.role} mb-2">${user.role.toUpperCase()}</span>
                    <br>
                    <span class="badge status-badge status-${user.status}">${user.status.toUpperCase()}</span>
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>${user.phone || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td>${user.department ? user.department.charAt(0).toUpperCase() + user.department.slice(1) : 'N/A'}</td>
                        </tr>
                        <tr>
                            <td><strong>Start Date:</strong></td>
                            <td>${user.created_at ? formatDate(user.created_at) : 'N/A'}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Login:</strong></td>
                            <td>${user.last_login ? formatDateTime(user.last_login) : 'Never'}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge status-badge status-${user.status}">
                                    ${user.status.toUpperCase()}
                                    ${user.status === 'active' && isUserOnline(user.id) ? '<span class="online-indicator"></span>' : ''}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Notes:</strong></td>
                            <td>${user.notes || 'No notes available'}</td>
                        </tr>
                    </table>
                </div>
            </div>
        `;

        document.getElementById('userDetailsContent').innerHTML = detailsHTML;
        document.getElementById('editFromDetailsBtn').onclick = () => editUser(userId);
        
        const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
        modal.show();
    }

    function handleUserSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const userData = Object.fromEntries(formData.entries());
        
        // Validate passwords match
        if (userData.password && userData.password !== userData.password_confirmation) {
            alert('Passwords do not match!');
            return;
        }
        
        // Simulate API call
        setTimeout(() => {
            alert('User saved successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
            modal.hide();
            loadUsersTable();
        }, 1000);
    }

    function deleteUser(userId) {
        const user = usersData.find(u => u.id === userId);
        if (!user) return;

        if (confirm(`Are you sure you want to delete user "${user.first_name} ${user.last_name}"?`)) {
            // Simulate API call
            setTimeout(() => {
                usersData = usersData.filter(u => u.id !== userId);
                filteredUsers = filteredUsers.filter(u => u.id !== userId);
                loadUsersTable();
                alert('User deleted successfully!');
            }, 500);
        }
    }

    function resetUserPassword(userId) {
        const user = usersData.find(u => u.id === userId);
        if (!user) return;

        if (confirm(`Reset password for "${user.first_name} ${user.last_name}"? A new password will be sent to their email.`)) {
            // Simulate API call
            setTimeout(() => {
                alert('Password reset email sent successfully!');
            }, 500);
        }
    }

    function handleUserSelect(userId) {
        const checkbox = document.querySelector(`input[value="${userId}"]`);
        if (checkbox.checked) {
            selectedUsers.push(userId);
        } else {
            selectedUsers = selectedUsers.filter(id => id !== userId);
        }
        
        updateSelectAllState();
        updateBulkActionsButton();
    }

    function handleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        if (selectAllCheckbox.checked) {
            selectedUsers = filteredUsers.map(user => user.id);
        } else {
            selectedUsers = [];
        }
        
        updateBulkActionsButton();
    }

    function updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        
        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < userCheckboxes.length;
        selectAllCheckbox.checked = checkedCheckboxes.length === userCheckboxes.length && userCheckboxes.length > 0;
    }

    function updateBulkActionsButton() {
        const bulkBtn = document.getElementById('bulkActionsBtn');
        if (selectedUsers.length > 0) {
            bulkBtn.innerHTML = `<i class="fas fa-tasks"></i> Bulk Actions (${selectedUsers.length})`;
            bulkBtn.classList.add('btn-warning');
            bulkBtn.classList.remove('btn-outline-info');
        } else {
            bulkBtn.innerHTML = `<i class="fas fa-tasks"></i> Bulk Actions`;
            bulkBtn.classList.remove('btn-warning');
            bulkBtn.classList.add('btn-outline-info');
        }
    }

    function showBulkActionsModal() {
        if (selectedUsers.length === 0) {
            alert('Please select users first!');
            return;
        }
        
        document.getElementById('selectedCount').textContent = selectedUsers.length;
        document.getElementById('bulkActionWarning').style.display = 'block';
        
        const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
        modal.show();
    }

    function handleBulkActionChange() {
        const action = document.getElementById('bulkAction').value;
        const roleGroup = document.getElementById('roleChangeGroup');
        const departmentGroup = document.getElementById('departmentChangeGroup');
        
        roleGroup.style.display = action === 'change_role' ? 'block' : 'none';
        departmentGroup.style.display = action === 'change_department' ? 'block' : 'none';
    }

    function executeBulkAction() {
        const action = document.getElementById('bulkAction').value;
        if (!action) {
            alert('Please select an action!');
            return;
        }
        
        // Simulate API call
        setTimeout(() => {
            alert(`Bulk action "${action}" executed successfully for ${selectedUsers.length} users!`);
            selectedUsers = [];
            updateBulkActionsButton();
            loadUsersTable();
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal'));
            modal.hide();
        }, 1000);
    }

    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const icon = toggleBtn.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function editFromDetails() {
        const detailsModal = bootstrap.Modal.getInstance(document.getElementById('userDetailsModal'));
        detailsModal.hide();
        // editUser function will be called from the button onclick
    }

    function exportUsers() {
        // Simulate export
        alert('Users exported successfully!');
    }

    // Utility functions
    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }

    function formatDateTime(dateString) {
        return new Date(dateString).toLocaleString();
    }

    function isUserOnline(userId) {
        // Mock online users - in real app, this would check actual online status
        return [1, 2, 3].includes(userId);
    }

    // Global functions
    window.changePage = function(page) {
        currentPage = page;
        loadUsersTable();
    };

    window.viewUserDetails = viewUserDetails;
    window.editUser = editUser;
    window.deleteUser = deleteUser;
    window.resetUserPassword = resetUserPassword;
    window.handleUserSelect = handleUserSelect;
});
</script>
@endpush
