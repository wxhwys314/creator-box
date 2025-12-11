<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    // Show user settings page
    public function showSettings(User $user)
    {
        $user = Auth::user();
        return view('users.settings', compact('user'));
    }

    // Update user profile
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:500'],
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ];

        if ($user->isCreator()) {
            $rules['creator_id'] = [
                'required',
                'string',
                'max:255',
                'unique:users,creator_id,' . $user->id,
                'regex:/^[a-z0-9-]+$/'
            ];
        }

        $validated = $request->validate($rules, [
            'creator_id.regex' => 'Creator ID can only contain lowercase letters, numbers, and hyphens.',
            'creator_id.unique' => 'This Creator ID is already taken. Please choose another one.',
        ]);

        //handle avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        //handle cover
        if ($request->hasFile('cover')) {
            if ($user->cover) {
                Storage::disk('public')->delete($user->cover);
            }

            $coverPath = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $coverPath;
        }

        $user->update($validated);

        return redirect()->route('users.settings')->with('success', 'Profile updated successfully!');
    }

    // Delete user avatar
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('users.settings')->with('success', 'Avatar removed successfully!');
    }

    // Delete user cover
    public function deleteCover()
    {
        $user = Auth::user();

        if ($user->cover) {
            Storage::disk('public')->delete($user->cover);
            $user->update(['cover' => null]);
        }

        return redirect()->route('users.settings')->with('success', 'Cover image removed successfully!');
    }

    // Delete user account
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirm_delete' => ['required', 'accepted']
        ], [
            'password.current_password' => 'The password is incorrect.',
            'confirm_delete.accepted' => 'You must confirm that you understand this action cannot be undone.'
        ]);

        $user = $request->user();

        // delete avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // delete cover if exists
        if ($user->cover) {
            Storage::disk('public')->delete($user->cover);
        }
        
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }
}
