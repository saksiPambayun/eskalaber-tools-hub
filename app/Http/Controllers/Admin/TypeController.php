<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('admin.types.index', compact('types'));
    }

    public function add()
    {
        return view('admin.types.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types',
        ]);

        Type::create(['name' => $request->name]);

        return redirect()->route('admin.types.index')->with('success', 'Type berhasil ditambahkan!');
    }

    public function update($id)
    {
        $type = Type::findOrFail($id);
        return view('admin.types.update', compact('type'));
    }

    public function doUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:types,name,' . $id,
        ]);

        $type = Type::findOrFail($id);
        $type->update(['name' => $request->name]);

        return redirect()->route('admin.types.index')->with('success', 'Type berhasil diupdate!');
    }

    public function delete($id)
    {
        Type::findOrFail($id)->delete();
        return redirect()->route('admin.types.index')->with('success', 'Type berhasil dihapus!');
    }
}