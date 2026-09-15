<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\Fine;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalTools' => Tool::count(),
            'totalLoans' => Loan::count(),
            'totalFines' => Fine::where('status', 'unpaid')->sum('amount'),

            // Data untuk chart
            'pendingLoans' => Loan::where('status', 'pending')->count(),
            'approvedLoans' => Loan::where('status', 'approved')->count(),
            'borrowedLoans' => Loan::where('status', 'borrowed')->count(),
            'returnedLoans' => Loan::where('status', 'returned')->count(),
            'rejectedLoans' => Loan::where('status', 'rejected')->count(),

            // Data untuk chart per bulan
            'monthlyLoans' => Loan::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray(),
        ];

        return view('admin.dashboard', $data);
    }
}
