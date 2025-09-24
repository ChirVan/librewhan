<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    // Show profile page
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.show', compact('user'));
    }

    // Update name/email
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->fill($data);
        $user->save();

        return back()->with('status', 'profile-updated');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'password-updated');
    }

    // Upload / update profile photo
    public function updatePhoto(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'photo' => ['required','image','max:2048'],
        ]);

        // remove previous photo file if exists
        if ($user->profile_photo_path) {
            Storage::disk(config('jetstream.profile_photo_disk', 'public'))->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', config('jetstream.profile_photo_disk', 'public'));

        $user->profile_photo_path = $path;
        $user->save();

        return back()->with('status', 'photo-updated');
    }

    // Optional: remove profile photo (revert to default)
    public function destroyPhoto(Request $request)
    {
        $user = $request->user();

        if ($user->profile_photo_path) {
            Storage::disk(config('jetstream.profile_photo_disk', 'public'))->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = null;
        $user->save();

        return back()->with('status', 'photo-removed');
    }
}
