<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        $category = $request->get('category');
        $type = $request->get('type');

        // Hanya tampilkan alat yang benar-benar bisa dipinjam.
        $baseQuery = Tool::query()
            ->where('status', 'available')
            ->where('stock', '>', 0);

        $totalAvailable = (clone $baseQuery)->count();

        $tools = (clone $baseQuery)->with(['category', 'type', 'place']);

        // Filter kategori; abaikan nilai yang tidak valid.
        if ($category && Category::whereKey($category)->exists()) {
            $tools->where('category_id', $category);
        } else {
            $category = null;
        }

        // Filter tipe; abaikan nilai yang tidak valid.
        if ($type && Type::whereKey($type)->exists()) {
            $tools->where('type_id', $type);
        } else {
            $type = null;
        }

        // Pencarian berdasarkan nama, kode, atau deskripsi.
        if ($query !== '') {
            $tools->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        $tools = $tools->latest()->paginate(12)->withQueryString();

        // Hitung hanya alat yang tersedia agar angka pada kategori sesuai hasil filter.
        $categories = Category::withCount(['tools' => function ($q) {
            $q->where('status', 'available')->where('stock', '>', 0);
        }])->orderBy('name')->get();

        $types = Type::withCount(['tools' => function ($q) {
            $q->where('status', 'available')->where('stock', '>', 0);
        }])->orderBy('name')->get();

        return view('catalog.index', compact('tools', 'categories', 'types', 'totalAvailable', 'query', 'category', 'type'));
    }

    public function detail($id)
    {
        $tool = Tool::with(['category', 'type', 'place'])->findOrFail($id);
        $relatedTools = Tool::where('category_id', $tool->category_id)
            ->where('id', '!=', $tool->id)
            ->take(4)
            ->get();

        return view('catalog.detail', compact('tool', 'relatedTools'));
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        $category = $request->get('category');
        $type = $request->get('type');

        $tools = Tool::with(['category', 'type', 'place'])
            ->where('status', 'available')
            ->where('stock', '>', 0);

        if ($query !== '') {
            $tools->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($category && Category::whereKey($category)->exists()) {
            $tools->where('category_id', $category);
        }

        if ($type && Type::whereKey($type)->exists()) {
            $tools->where('type_id', $type);
        }

        $tools = $tools->latest()->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $tools,
            'total' => $tools->total(),
        ]);
    }
}
