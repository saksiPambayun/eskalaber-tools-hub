<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        // Hapus foto lama jika ada
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        // Upload foto baru
        $file = $request->file('photo');
        $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $fileName);

        $user->update([
            'photo' => 'uploads/profile/' . $fileName
        ]);

        return back()->with('success', 'Foto profile berhasil diupdate!');
    }
    public function deletePhoto()
{
    $user = auth()->user();

    if ($user->photo && file_exists(public_path($user->photo))) {
        unlink(public_path($user->photo));
        $user->update(['photo' => null]);
        return back()->with('success', 'Foto profile berhasil dihapus!');
    }

    return back()->with('error', 'Tidak ada foto untuk dihapus.');
}
}