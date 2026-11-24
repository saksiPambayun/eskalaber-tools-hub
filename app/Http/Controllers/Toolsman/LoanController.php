<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Loan::with(['user', 'tool']);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $loans = $query->latest()->paginate(10);
        $statuses = ['all', 'pending', 'approved', 'borrowed', 'returned', 'rejected'];

        return view('toolsman.loans.index', compact('loans', 'status', 'statuses'));
    }

    public function detail($id)
    {
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);
        return view('toolsman.loans.detail', compact('loan'));
    }

    public function approve($id, LoanWorkflowService $workflow)
    {
        try {
            $workflow->approve(Loan::findOrFail($id), auth()->user());
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function reject($id, LoanWorkflowService $workflow)
    {
        try {
            $workflow->reject(Loan::findOrFail($id), auth()->user());
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman ditolak!');
    }

    public function returned($id, LoanWorkflowService $workflow)
    {
        try {
            $workflow->returnLoan(Loan::findOrFail($id), auth()->user());
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('toolsman.loans.index')->with('success', 'Alat berhasil dikembalikan!');
    }

    public function testReturn(Request $request, $id, LoanWorkflowService $workflow)
    {
        abort_unless(app()->environment(['local', 'testing']), 404);

        $validated = $request->validate([
            'actual_return_date' => 'required|date',
        ]);

        try {
            $workflow->returnLoan(Loan::findOrFail($id), auth()->user(), \Carbon\Carbon::parse($validated['actual_return_date']));
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return back()->with('success', 'Simulasi pengembalian berhasil diproses.');
    }
}
