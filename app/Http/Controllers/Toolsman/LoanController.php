<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansExport;
use App\Helpers\ActivityHelper;
use App\Models\Notification;

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
        $loan->update([
            'status' => 'approved'
        ]);

        // Kurangi stock tool
        $tool = Tool::find($loan->tool_id);
        $tool->decrement('stock', 1);

        // Update status tool jika stock 0
        if ($tool->stock == 0) {
            $tool->update(['status' => 'borrowed']);
        }

        // Log activity
        ActivityHelper::log('approve_loan', 'Menyetujui peminjaman #' . $loan->id . ' oleh ' . ($loan->user->name ?? 'User') . ' untuk alat ' . ($loan->tool->name ?? 'Tool'));

        return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'rejected']);

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
            // Buat denda
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
        }

        // Log activity
        $logMessage = 'Mengembalikan alat ' . ($loan->tool->name ?? 'Tool') . ' dari peminjaman #' . $loan->id;
        if ($fineCreated) {
            $logMessage .= ' (Denda Rp ' . number_format($fineAmount, 0, ',', '.') . ' dibuat)';
        }
        ActivityHelper::log('return_loan', $logMessage);

        return redirect()->route('toolsman.loans.index')->with('success', 'Alat berhasil dikembalikan!');
    }

    public function exportExcel()
    {
        // Log activity
        ActivityHelper::log('export_loans', 'Mengekspor data peminjaman ke Excel');

        return Excel::download(new LoansExport(), 'loans_' . date('Y-m-d') . '.xlsx');
    }
    public function updateStatus(Request $request, $id)
{
    $loan = Loan::findOrFail($id);
    $loan->update([
        'status' => $request->status
    ]);

    if ($request->status == 'approved') {
        $tool = Tool::find($loan->tool_id);
        $tool->decrement('stock', 1);
        if ($tool->stock == 0) {
            $tool->update(['status' => 'borrowed']);
        }
    }

    // Log activity
    ActivityHelper::log('update_loan_status', 'Mengubah status peminjaman #' . $loan->id . ' menjadi ' . $request->status);

    return redirect()->route('admin.loans.index')->with('success', 'Status peminjaman berhasil diupdate!');
}

public function approve($id)
{
    $loan = Loan::findOrFail($id);
    $loan->update(['status' => 'approved']);

    // Kirim notifikasi ke user
    Notification::send(
        $loan->user_id,
        'Peminjaman Disetujui',
        'Peminjaman alat ' . $loan->tool->name . ' telah disetujui.',
        'success',
        route('user.loans.detail', $loan->id)
    );

    // Kirim notifikasi ke admin
    Notification::sendToAdmins(
        'Peminjaman Baru Disetujui',
        $loan->user->name . ' meminjam ' . $loan->tool->name,
        'info',
        route('admin.loans.detail', $loan->id)
    );

    ActivityHelper::log('approve_loan', 'Menyetujui peminjaman #' . $loan->id);

    return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman disetujui!');
}


public function delete($id)
{
    $loan = Loan::findOrFail($id);
    $loan->delete();

    // Log activity
    ActivityHelper::log('delete_loan', 'Menghapus peminjaman #' . $loan->id);

    return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil dihapus!');
}

}
