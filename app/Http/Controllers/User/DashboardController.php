<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Fine;
use App\Models\Tool;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'activeLoans' => Loan::where('user_id', $user->id)
                                 ->whereIn('status', ['approved', 'borrowed'])
                                 ->count(),
            'totalLoans' => Loan::where('user_id', $user->id)->count(),
            'totalFines' => Fine::where('user_id', $user->id)
                                ->where('status', 'unpaid')
                                ->sum('amount'),
            'availableTools' => Tool::where('status', 'available')->count(),
        ];
        
        return view('user.dashboard', $data);
    }
}