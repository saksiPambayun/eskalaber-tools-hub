<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tool;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::where('status', 'available')->get();
        return view('user.tools.index', compact('tools'));
    }

    public function detail($id)
    {
        $tool = Tool::with(['category', 'type', 'place'])->findOrFail($id);
        return view('user.tools.detail', compact('tool'));
    }
}