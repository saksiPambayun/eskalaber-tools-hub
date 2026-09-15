<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ToolsmanController extends Controller
{
    public function index()
    {
        $toolsmans = User::with('department')->where('role', 'TOOLSMAN')->get();
        return view('admin.toolsmans.index', compact('toolsmans'));
    }

    public function add()
    {
        $departments = Department::all();
        return view('admin.toolsmans.add', compact('departments'));
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
            'role' => 'TOOLSMAN',
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.toolsmans.index')->with('success', 'Toolsman berhasil ditambahkan!');
    }

    public function detail($id)
    {
        $user = User::with('department')->findOrFail($id);
        return view('admin.toolsmans.detail', compact('user'));
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        return view('admin.toolsmans.update', compact('user', 'departments'));
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

        return redirect()->route('admin.toolsmans.index')->with('success', 'Toolsman berhasil diupdate!');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.toolsmans.index')->with('success', 'Toolsman berhasil dihapus!');
    }
}
