<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use App\Models\LogoSetting;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        $category = $request->get('category');

        // Hanya tampilkan alat yang benar-benar bisa dipinjam.
        $baseQuery = Tool::query()
            ->where('status', 'available')
            ->where('stock', '>', 0);

        $totalAvailable = (clone $baseQuery)->count();

        $tools = (clone $baseQuery)->with(['category', 'place']);

        // Filter kategori; abaikan nilai yang tidak valid.
        if ($category && Category::whereKey($category)->exists()) {
            $tools->where('category_id', $category);
        } else {
            $category = null;
        }

        // Pencarian berdasarkan nama, kode, atau deskripsi.
        if ($query !== '') {
            $tools->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

       $tools = $tools->latest()->paginate(6)->withQueryString();

        // Hitung hanya alat yang tersedia agar angka pada kategori sesuai hasil filter.
        $categories = Category::withCount(['tools' => function ($q) {
            $q->where('status', 'available')->where('stock', '>', 0);
        }])->orderBy('name')->get();

        return view('catalog.index', compact('tools', 'categories', 'totalAvailable', 'query', 'category'));
    }

    public function detail($id)
    {
        $tool = Tool::with(['category', 'place'])->findOrFail($id);
        $relatedTools = Tool::where('category_id', $tool->category_id)
            ->where('id', '!=', $tool->id)
            ->take(4)
            ->get();

        $logoSetting = LogoSetting::current();

        return view('catalog.detail', compact('tool', 'relatedTools', 'logoSetting'));
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));
        $category = $request->get('category');

        $tools = Tool::with(['category', 'place'])
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

        $tools = $tools->latest()->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $tools,
            'total' => $tools->total(),
        ]);
    }
}
