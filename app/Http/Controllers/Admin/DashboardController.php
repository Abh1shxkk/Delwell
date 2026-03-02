<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
  public function index()
  {
    return view('admin.dashboard');
  }

  public function adminSettings()
  {
    return view('admin.settings');
  }

  public function addquizManage()
  {
    return view('admin.registration.addquiz');
  }

  public function addsectionManage()
  {
    return view('admin.registration.addsection');
  }

  public function profileSettings()
  {
    return view('admin.profile-settings');
  }

  public function updateProfile(Request $request)
  {
    $admin = Auth::guard('admin')->user();

    $validated = $request->validate([
      'username' => 'required|string|max:255|unique:users,username,' . $admin->id,
      'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
      'first_name' => 'nullable|string|max:255',
      'last_name' => 'nullable|string|max:255',
    ]);

    $admin->update($validated);

    return redirect()->route('admin.profile.settings')->with('success', 'Profile updated successfully!');
  }

  public function updatePassword(Request $request)
  {
    $admin = Auth::guard('admin')->user();

    $validated = $request->validate([
      'current_password' => 'required',
      'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    if (!Hash::check($request->current_password, $admin->password)) {
      return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $admin->update([
      'password' => Hash::make($request->password)
    ]);

    return redirect()->route('admin.profile.settings')->with('success', 'Password changed successfully!');
  }

  public function updateProfilePicture(Request $request)
  {
    $admin = Auth::guard('admin')->user();

    $validated = $request->validate([
      'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Delete old profile image if exists
    if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
      Storage::disk('public')->delete($admin->profile_image);
    }

    // Store new profile image
    $path = $request->file('profile_image')->store('profile-images', 'public');

    $admin->update([
      'profile_image' => $path
    ]);

    return redirect()->route('admin.profile.settings')->with('success', 'Profile picture updated successfully!');
  }

  public function removeProfilePicture()
  {
    $admin = Auth::guard('admin')->user();

    // Delete profile image if exists
    if ($admin->profile_image && Storage::disk('public')->exists($admin->profile_image)) {
      Storage::disk('public')->delete($admin->profile_image);
    }

    $admin->update([
      'profile_image' => null
    ]);

    return response()->json(['success' => true, 'message' => 'Profile picture removed successfully!']);
  }

}