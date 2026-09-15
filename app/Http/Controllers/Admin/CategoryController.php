<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function add()
    {
        return view('admin.categories.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Category berhasil ditambahkan!');
    }

    public function update($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.update', compact('category'));
    }

    public function doUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Category berhasil diupdate!');
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category berhasil dihapus!');
    }
}