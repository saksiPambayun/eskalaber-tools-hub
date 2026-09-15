<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('department')->where('role', 'USER')->get();
        return view('admin.users.index', compact('users'));
    }

    public function add()
    {
        $departments = Department::all();
        return view('admin.users.add', compact('departments'));
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'USER',
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function detail($id)
    {
        $user = User::with('department')->findOrFail($id);
        return view('admin.users.detail', compact('user'));
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        return view('admin.users.update', compact('user', 'departments'));
    }

    public function doUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'department_id' => 'required|exists:departments,id',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}
