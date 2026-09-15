<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\ActivityHelper;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['tool'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('user.loans.index', compact('loans'));
    }

    public function add(Request $request)
    {
        $tools = Tool::where('status', 'available')->where('stock', '>', 0)->get();
        return view('user.loans.add', compact('tools'));
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string',
        ]);

        // Cek ketersediaan tool
        $tool = Tool::find($request->tool_id);
        if ($tool->stock <= 0) {
            return back()->with('error', 'Stock tool tidak mencukupi!');
        }

        Loan::create([
            'user_id' => auth()->id(),
            'tool_id' => $request->tool_id,
            'loan_date' => $request->loan_date,
            'return_date' => $request->return_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        ActivityHelper::log('create_loan', 'Mengajukan peminjaman alat ' . ($tool->name ?? 'Tool'));

        return redirect()->route('user.loans.index')->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function detail($id)
    {
        $loan = Loan::with(['tool'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        return view('user.loans.detail', compact('loan'));
    }

   public function cancel($id)
{
    $loan = Loan::where('user_id', auth()->id())
        ->where('status', 'pending')
        ->findOrFail($id);
    
    $loan->update(['status' => 'rejected']);

    // Log activity
    ActivityHelper::log('cancel_loan', 'Membatalkan peminjaman #' . $loan->id);

    return redirect()->route('user.loans.index')->with('success', 'Peminjaman dibatalkan!');
}
}