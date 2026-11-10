<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\Fine;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;

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

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);

        // Update status loan
        $loan->update(['status' => 'approved']);

        // Kurangi stock tool
        $tool = Tool::find($loan->tool_id);
        $tool->decrement('stock', 1);

        // Update status tool jika stock 0
        if ($tool->stock == 0) {
            $tool->update(['status' => 'borrowed']);
        }

        // Kirim Notifikasi ke User
        Notification::send(
            $loan->user_id,
            'Peminjaman Disetujui',
            'Peminjaman alat ' . $loan->tool->name . ' telah disetujui.',
            'success',
            route('user.loans.detail', $loan->id)
        );

        // Log activity
        ActivityHelper::log('approve_loan', 'Menyetujui peminjaman #' . $loan->id . ' oleh ' . ($loan->user->name ?? 'User'));

        return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'rejected']);

        // Kirim Notifikasi ke User
        Notification::send(
            $loan->user_id,
            'Peminjaman Ditolak',
            'Peminjaman alat ' . $loan->tool->name . ' ditolak.',
            'danger',
            route('user.loans.detail', $loan->id)
        );

        // Log activity
        ActivityHelper::log('reject_loan', 'Menolak peminjaman #' . $loan->id . ' oleh ' . ($loan->user->name ?? 'User'));

        return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman ditolak!');
    }

    public function returned($id)
    {
        $loan = Loan::findOrFail($id);

        // Update status loan
        $loan->update([
            'status' => 'returned',
            'actual_return_date' => now()
        ]);

        // Tambah stock tool
        $tool = Tool::find($loan->tool_id);
        $tool->increment('stock', 1);
        $tool->update(['status' => 'available']);

        // Cek keterlambatan
        $lateDays = $loan->return_date->diffInDays(now(), false);
        $fineCreated = false;

        if ($lateDays > 0) {
            $fineAmount = $lateDays * 10000; // Rp 10.000 per hari
            Fine::create([
                'loan_id' => $loan->id,
                'user_id' => $loan->user_id,
                'amount' => $fineAmount,
                'description' => 'Denda keterlambatan ' . $lateDays . ' hari',
                'late_days' => $lateDays,
                'status' => 'unpaid',
            ]);
            $fineCreated = true;

            // Kirim Notifikasi Denda ke User
            Notification::send(
                $loan->user_id,
                'Denda Keterlambatan',
                'Anda mendapat denda Rp ' . number_format($fineAmount, 0, ',', '.') . ' karena terlambat mengembalikan alat.',
                'warning',
                route('user.fines.index')
            );
        }

        // Log activity
        $logMessage = 'Mengembalikan alat ' . ($loan->tool->name ?? 'Tool') . ' dari peminjaman #' . $loan->id;
        if ($fineCreated) {
            $logMessage .= ' (Denda Rp ' . number_format($fineAmount, 0, ',', '.') . ' dibuat)';
        }
        ActivityHelper::log('return_loan', $logMessage);

        return redirect()->route('toolsman.loans.index')->with('success', 'Alat berhasil dikembalikan!');
    }
}
