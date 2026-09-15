<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index()
    {
        $places = Place::all();
        return view('admin.places.index', compact('places'));
    }

    public function add()
    {
        return view('admin.places.add');
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:places',
        ]);

        Place::create(['name' => $request->name]);

        return redirect()->route('admin.places.index')->with('success', 'Place berhasil ditambahkan!');
    }

    public function update($id)
    {
        $place = Place::findOrFail($id);
        return view('admin.places.update', compact('place'));
    }

    public function doUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:places,name,' . $id,
        ]);

        $place = Place::findOrFail($id);
        $place->update(['name' => $request->name]);

        return redirect()->route('admin.places.index')->with('success', 'Place berhasil diupdate!');
    }

    public function delete($id)
    {
        Place::findOrFail($id)->delete();
        return redirect()->route('admin.places.index')->with('success', 'Place berhasil dihapus!');
    }
}