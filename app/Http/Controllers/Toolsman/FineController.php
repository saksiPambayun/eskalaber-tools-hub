<?php

namespace App\Http\Controllers\Toolsman;

use App\Helpers\ActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\Fine;
use App\Models\Notification;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Fine::with(['user', 'loan.tool', 'paymentMethod']);

        if ($status != 'all') {
            $query->where('status', $status);
        }

        $fines = $query->latest()->paginate(10);

        return view('toolsman.fines.index', compact('fines', 'status'));
    }

    public function detail($id)
    {
        $fine = Fine::with(['user', 'loan.tool', 'paymentMethod', 'reviewer'])->findOrFail($id);
        return view('toolsman.fines.detail', compact('fine'));
    }

    public function verify(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'review_note' => 'nullable|string|max:500',
        ]);

        $fine = Fine::with('user')->findOrFail($id);

        if ($fine->status !== 'pending_verification') {
            return back()->with('error', 'Hanya pembayaran dengan bukti menunggu verifikasi yang bisa diproses.');
        }

        if ($validated['action'] === 'approve') {
            $fine->update([
                'status' => 'paid',
                'payment_date' => now(),
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_note' => $validated['review_note'] ?? null,
            ]);

            Notification::send($fine->user_id, 'Pembayaran Diterima', 'Bukti pembayaran #' . $fine->id . ' diterima.', 'success', route('user.fines.detail', $fine->id));
            ActivityHelper::log('verify_fine', 'Menerima pembayaran #' . $fine->id);
            return back()->with('success', 'Pembayaran diterima.');
        }

        $fine->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'] ?? null,
        ]);

        Notification::send($fine->user_id, 'Pembayaran Ditolak', 'Bukti pembayaran #' . $fine->id . ' ditolak. ' . ($validated['review_note'] ?? 'Silakan unggah ulang bukti yang valid.'), 'danger', route('user.fines.detail', $fine->id));
        ActivityHelper::log('verify_fine', 'Menolak pembayaran #' . $fine->id);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function paid($id)
    {
        $fine = Fine::findOrFail($id);

        if (!in_array($fine->status, ['unpaid', 'pending_verification', 'rejected'], true)) {
            return back()->with('error', 'Status pembayaran tidak dapat diubah menjadi lunas.');
        }

        $fine->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $fine->payment_method ?: 'cash',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Notification::send($fine->user_id, 'Pembayaran Ditandai Lunas', 'Pembayaran #' . $fine->id . ' telah ditandai lunas oleh petugas.', 'success', route('user.fines.detail', $fine->id));
        ActivityHelper::log('pay_fine', 'Menandai lunas pembayaran #' . $fine->id);

        return redirect()->route('toolsman.fines.index')->with('success', 'Pembayaran sudah lunas!');
    }

    public function exportExcel(Request $request)
    {
        $status = $request->get('status');
        $query = Fine::with(['user', 'loan']);
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        $fines = $query->get();
        $filename = 'fines_' . date('Y-m-d') . '.csv';

        return response()->stream(function () use ($fines) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Pengguna', 'ID Pinjaman', 'Jumlah', 'Hari Keterlambatan', 'Status', 'Metode Pembayaran', 'Tanggal Bayar']);
            foreach ($fines as $i => $fine) {
                fputcsv($file, [
                    $i + 1,
                    $fine->user->name ?? '-',
                    $fine->loan_id,
                    $fine->amount,
                    $fine->late_days,
                    match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => $fine->status },
                    $fine->payment_method ?? '-',
                    $fine->payment_date ? $fine->payment_date->format('d-m-Y') : '-',
                ]);
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
