<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\FormattedMail;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id','desc')->paginate(25);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'usertype' => 'required|in:admin,barista',
        // password optional: if absent we generate one. If provided it must be confirmed.
        'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $password = $data['password'] ?? \Illuminate\Support\Str::random(10);

            $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'usertype' => $data['usertype'],
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            ]);

            // Trigger password reset link
            Password::sendResetLink(['email' => $user->email]);

            // optionally: flash message to admin
            return redirect()->route('admin.users.index')->with('status', 'User created. A password setup email has been sent.');
        } catch (\Illuminate\Database\QueryException $ex) {
            // likely DB/unique constraint; surface message
            return back()->withInput()->withErrors(['db' => 'Database error: '.$ex->getMessage()]);
        } catch (\Exception $ex) {
            return back()->withInput()->withErrors(['error' => $ex->getMessage()]);
        }
    }


    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|email|unique:users,email,' . $user->id,
           'usertype' => 'required|in:admin,barista',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors(['You cannot delete your own account.']);
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    public function resetPassword(User $user)
    {
       $new = Str::random(10);
        $user->password = Hash::make($new);
        $user->save();

        $subject = 'Password reset';
        $bodyHtml = "<p>Your password was reset by admin.</p><p><strong>New password:</strong> {$new}</p>"
                  . "<p>Please login and change this password as soon as possible.</p>";

        try {
            // Send synchronously (quick, no queue worker required)
            Mail::to($user->email)->send(new FormattedMail($subject, $bodyHtml));

            return redirect()->route('admin.users.index')->with('status', 'Password reset and email sent.');
        } catch (\Exception $e) {
            \Log::warning('Password reset email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            // Fallback: still inform admin the password was changed
            return redirect()->route('admin.users.index')
                             ->with('status', 'Password reset. Email could not be sent; check logs.');
        }
    }

    public function toggleStatus(User $user)
    {
        $user->status = ($user->status ?? 'active') === 'active' ? 'inactive' : 'active';
        $user->save();
        return redirect()->route('admin.users.index')->with('status', 'Status updated.');
    }
}
