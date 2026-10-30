<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;
use App\Models\Notification;

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
        // 1. Validasi Input
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string',
        ]);

        // 2. Cek Stok Tool
        $tool = Tool::findOrFail($request->tool_id);
        if ($tool->stock <= 0) {
            return back()->with('error', 'Stock tool tidak mencukupi!');
        }

        // 3. Simpan Data Peminjaman
        $loan = Loan::create([
            'user_id' => auth()->id(),
            'tool_id' => $request->tool_id,
            'loan_date' => $request->loan_date,
            'return_date' => $request->return_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // 4. Kirim Notifikasi ke Toolsman (HANYA SEKALI)
        Notification::sendToToolsmans(
            'Peminjaman Baru',
            auth()->user()->name . ' mengajukan peminjaman ' . $tool->name,
            'warning',
            route('toolsman.loans.index')
        );

        // 5. Catat Log Aktivitas
        ActivityHelper::log('create_loan', 'Mengajukan peminjaman alat ' . $tool->name);

        // 6. Redirect ke Halaman Peminjaman Saya
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

        // Catat Log Aktivitas
        ActivityHelper::log('cancel_loan', 'Membatalkan peminjaman #' . $loan->id);

        return redirect()->route('user.loans.index')->with('success', 'Peminjaman dibatalkan!');
    }
}
