<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::where('user_id', auth()->id())->latest()->get();
        return view('user.fines.index', compact('fines'));
    }
}