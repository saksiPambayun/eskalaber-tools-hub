<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Fine;
use App\Models\Tool;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalTools' => Tool::count(),
            'totalLoans' => Loan::count(),
            'pendingLoans' => Loan::where('status', 'pending')->count(),
            'totalFines' => Fine::where('status', 'unpaid')->sum('amount'),
            'recentLoans' => Loan::with(['user', 'tool'])->latest()->take(5)->get(),
        ];
        
        return view('toolsman.dashboard', $data);
    }
}