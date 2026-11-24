<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Helpers\ActivityHelper;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Tool;
use App\Services\LoanWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        // Ambil semua alat yang tersedia dan stoknya lebih dari 0
        $tools = Tool::where('status', 'available')->where('stock', '>', 0)->get();
        return view('user.loans.add', compact('tools'));
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'loan_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tool = Tool::findOrFail($request->tool_id);
        if ($tool->stock <= 0 || $tool->status !== 'available') {
            return back()->withInput()->with('error', 'Stock tool tidak mencukupi atau tool tidak tersedia!');
        }

        // User boleh pinjam lagi (alat sama/berbeda, tidak dibatasi jumlahnya).
        // Yang membatasi hanya stok alat dan denda unpaid/rejected.
        $blockingFine = Fine::where('user_id', auth()->id())
            ->whereIn('status', ['unpaid', 'rejected'])
            ->exists();
        if ($blockingFine) {
            return back()->withInput()->with('error', 'Selesaikan denda unpaid/rejected terlebih dahulu sebelum mengajukan pinjaman baru.');
        }

        $loan = Loan::create([
            'user_id' => auth()->id(),
            'tool_id' => $request->tool_id,
            'loan_date' => $request->loan_date,
            'return_date' => $request->return_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        Notification::sendToToolsmans(
            'Peminjaman Baru',
            auth()->user()->name . ' mengajukan peminjaman ' . $tool->name,
            'warning',
            route('toolsman.loans.index')
        );

        ActivityHelper::log('create_loan', 'Mengajukan peminjaman alat ' . $tool->name);

        return redirect()->route('user.loans.index')->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function detail($id)
    {
        $loan = Loan::with(['tool'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        return view('user.loans.detail', compact('loan'));
    }

    public function cancel($id, LoanWorkflowService $workflow)
    {
        $loan = Loan::where('user_id', auth()->id())->findOrFail($id);

        try {
            $workflow->cancel($loan, auth()->user());
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('user.loans.index')->with('success', 'Peminjaman dibatalkan!');
    }
}
