<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    public function add()
    {
        return view('admin.departments.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);

        Department::create(['name' => $request->name]);

        return redirect()->route('admin.departments.index')->with('success', 'Department berhasil ditambahkan!');
    }

    public function update($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.update', compact('department'));
    }

    public function doUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
        ]);

        $department = Department::findOrFail($id);
        $department->update(['name' => $request->name]);

        return redirect()->route('admin.departments.index')->with('success', 'Department berhasil diupdate!');
    }

    public function delete($id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department berhasil dihapus!');
    }
}
