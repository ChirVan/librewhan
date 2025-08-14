<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    /**
     * Display the user management page
     */
    public function index()
    {
        return view('auth.usermanagement');
    }

    /**
     * Get all users with filtering and pagination
     */
    public function getUsers(Request $request)
    {
        // Sample users data - replace with actual database query
        $users = collect([
            [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@kalibrewhan.com',
                'phone' => '+1234567890',
                'role' => 'admin',
                'department' => 'management',
                'status' => 'active',
                'last_login' => '2024-01-15 10:30:00',
                'avatar' => null,
                'created_at' => '2024-01-01',
                'notes' => 'System administrator',
                'is_online' => true
            ],
            [
                'id' => 2,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@kalibrewhan.com',
                'phone' => '+1234567891',
                'role' => 'manager',
                'department' => 'service',
                'status' => 'active',
                'last_login' => '2024-01-15 09:15:00',
                'avatar' => null,
                'created_at' => '2024-01-02',
                'notes' => 'Floor manager',
                'is_online' => true
            ],
            [
                'id' => 3,
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@kalibrewhan.com',
                'phone' => '+1234567892',
                'role' => 'cashier',
                'department' => 'service',
                'status' => 'active',
                'last_login' => '2024-01-15 08:45:00',
                'avatar' => null,
                'created_at' => '2024-01-03',
                'notes' => 'Senior cashier',
                'is_online' => true
            ],
            [
                'id' => 4,
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'email' => 'sarah.wilson@kalibrewhan.com',
                'phone' => '+1234567893',
                'role' => 'staff',
                'department' => 'kitchen',
                'status' => 'active',
                'last_login' => '2024-01-14 16:20:00',
                'avatar' => null,
                'created_at' => '2024-01-04',
                'notes' => 'Head chef',
                'is_online' => false
            ],
            [
                'id' => 5,
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@kalibrewhan.com',
                'phone' => '+1234567894',
                'role' => 'staff',
                'department' => 'service',
                'status' => 'inactive',
                'last_login' => '2024-01-10 14:30:00',
                'avatar' => null,
                'created_at' => '2024-01-05',
                'notes' => 'On leave',
                'is_online' => false
            ]
        ]);

        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $users = $users->filter(function ($user) use ($search) {
                return str_contains(strtolower($user['first_name']), $search) ||
                       str_contains(strtolower($user['last_name']), $search) ||
                       str_contains(strtolower($user['email']), $search);
            });
        }

        if ($request->has('role') && $request->role !== 'all') {
            $users = $users->where('role', $request->role);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $users = $users->where('status', $request->status);
        }

        if ($request->has('department') && $request->department !== 'all') {
            $users = $users->where('department', $request->department);
        }

        // Pagination
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $total = $users->count();
        $users = $users->forPage($page, $perPage);

        return response()->json([
            'success' => true,
            'data' => $users->values(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ]);
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,cashier,staff',
            'department' => 'nullable|in:kitchen,service,management,finance',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,suspended',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'send_welcome_email' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userData = $request->all();
            $userData['password'] = Hash::make($request->password);

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profile_pictures', $filename, 'public');
                $userData['profile_picture'] = $path;
            }

            // In a real application, you would save to database
            // $user = User::create($userData);

            // Send welcome email if requested
            if ($request->send_welcome_email) {
                // Send welcome email logic here
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        // Sample user data - replace with actual database query
        $user = [
            'id' => $id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@kalibrewhan.com',
            'phone' => '+1234567890',
            'role' => 'admin',
            'department' => 'management',
            'status' => 'active',
            'last_login' => '2024-01-15 10:30:00',
            'avatar' => null,
            'created_at' => '2024-01-01',
            'notes' => 'System administrator',
            'is_online' => true,
            'permissions' => [
                'user_management',
                'inventory_management',
                'sales_reports',
                'system_settings'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,cashier,staff',
            'department' => 'nullable|in:kitchen,service,management,finance',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,suspended',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userData = $request->all();

            // Update password only if provided
            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            } else {
                unset($userData['password']);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profile_pictures', $filename, 'public');
                $userData['profile_picture'] = $path;
            }

            // In a real application, you would update the database
            // $user = User::findOrFail($id);
            // $user->update($userData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            // In a real application, you would delete from database
            // $user = User::findOrFail($id);
            // $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword($id)
    {
        try {
            // Generate new password
            $newPassword = $this->generateRandomPassword();
            $hashedPassword = Hash::make($newPassword);

            // In a real application, you would update the database
            // $user = User::findOrFail($id);
            // $user->password = $hashedPassword;
            // $user->save();

            // Send password reset email
            // Mail::to($user->email)->send(new PasswordResetMail($newPassword));

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully. New password has been sent to user\'s email.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions on multiple users
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,suspend,delete,change_role,change_department',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer',
            'new_role' => 'required_if:action,change_role|in:admin,manager,cashier,staff',
            'new_department' => 'required_if:action,change_department|in:kitchen,service,management,finance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $action = $request->action;
            $userIds = $request->user_ids;
            $affectedUsers = count($userIds);

            // In a real application, you would perform bulk operations on database
            switch ($action) {
                case 'activate':
                    // User::whereIn('id', $userIds)->update(['status' => 'active']);
                    break;
                case 'deactivate':
                    // User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                    break;
                case 'suspend':
                    // User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                    break;
                case 'delete':
                    // User::whereIn('id', $userIds)->delete();
                    break;
                case 'change_role':
                    // User::whereIn('id', $userIds)->update(['role' => $request->new_role]);
                    break;
                case 'change_department':
                    // User::whereIn('id', $userIds)->update(['department' => $request->new_department]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk action '{$action}' completed successfully for {$affectedUsers} users."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function getStats()
    {
        // In a real application, you would query the database
        $stats = [
            'total_users' => 25,
            'active_users' => 22,
            'admin_users' => 3,
            'online_users' => 8,
            'new_users_this_month' => 3,
            'role_distribution' => [
                'admin' => 3,
                'manager' => 5,
                'cashier' => 8,
                'staff' => 9
            ],
            'department_distribution' => [
                'management' => 3,
                'service' => 12,
                'kitchen' => 7,
                'finance' => 3
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get recent user activities
     */
    public function getRecentActivities()
    {
        $activities = [
            [
                'id' => 1,
                'type' => 'user_created',
                'user_name' => 'Mike Johnson',
                'description' => 'New user "Mike Johnson" was created',
                'timestamp' => now()->subHours(2)->toISOString(),
                'icon' => 'fas fa-user-plus',
                'color' => '#28a745'
            ],
            [
                'id' => 2,
                'type' => 'user_login',
                'user_name' => 'Jane Smith',
                'description' => 'Jane Smith logged in',
                'timestamp' => now()->subHours(3)->toISOString(),
                'icon' => 'fas fa-sign-in-alt',
                'color' => '#17a2b8'
            ],
            [
                'id' => 3,
                'type' => 'user_updated',
                'user_name' => 'David Brown',
                'description' => 'User "David Brown" was updated',
                'timestamp' => now()->subHours(5)->toISOString(),
                'icon' => 'fas fa-user-edit',
                'color' => '#ffc107'
            ],
            [
                'id' => 4,
                'type' => 'password_reset',
                'user_name' => 'Sarah Wilson',
                'description' => 'Password reset for "Sarah Wilson"',
                'timestamp' => now()->subDay()->toISOString(),
                'icon' => 'fas fa-key',
                'color' => '#dc3545'
            ],
            [
                'id' => 5,
                'type' => 'role_updated',
                'user_name' => 'John Doe',
                'description' => 'Role updated for "John Doe"',
                'timestamp' => now()->subDays(2)->toISOString(),
                'icon' => 'fas fa-user-shield',
                'color' => '#6f42c1'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // In a real application, you would generate actual export files
        // For now, we'll just return a success message
        
        return response()->json([
            'success' => true,
            'message' => "Users exported successfully in {$format} format",
            'download_url' => '/downloads/users_export_' . date('Y-m-d') . '.' . ($format === 'excel' ? 'xlsx' : $format)
        ]);
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        try {
            // In a real application, you would update the database
            // $user = User::findOrFail($id);
            // $user->status = $user->status === 'active' ? 'inactive' : 'active';
            // $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate random password
     */
    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    /**
     * Check if user has permission
     */
    private function hasPermission($userId, $permission)
    {
        // In a real application, you would check user permissions from database
        return true;
    }

    /**
     * Log user activity
     */
    private function logActivity($userId, $action, $description)
    {
        // In a real application, you would log activities to database
        // ActivityLog::create([
        //     'user_id' => $userId,
        //     'action' => $action,
        //     'description' => $description,
        //     'created_at' => now()
        // ]);
    }
}