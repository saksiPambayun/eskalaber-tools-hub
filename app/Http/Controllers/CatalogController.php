<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $tools = Tool::with(['category', 'type', 'place'])
            ->where('status', 'available')
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('tools')->get();
        $types = Type::withCount('tools')->get();

        return view('catalog.index', compact('tools', 'categories', 'types'));
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
        $query = $request->get('q');
        $category = $request->get('category');
        $type = $request->get('type');

        $tools = Tool::with(['category', 'type', 'place'])
            ->where('status', 'available')
            ->where('stock', '>', 0);

        if ($query) {
            $tools->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($category) {
            $tools->where('category_id', $category);
        }

        if ($type) {
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
