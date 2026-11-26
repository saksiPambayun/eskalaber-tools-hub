<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.fines.index', compact('fines', 'status'));
    }

    public function detail($id)
    {
        $fine = Fine::with(['user', 'loan.tool', 'paymentMethod', 'reviewer'])->findOrFail($id);
        return view('admin.fines.detail', compact('fine'));
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

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,pending_verification,paid,rejected,cancelled',
        ]);

        $fine = Fine::findOrFail($id);
        $fine->update([
            'status' => $validated['status'],
            'payment_date' => $validated['status'] === 'paid' ? now() : $fine->payment_date,
            'reviewed_by' => in_array($validated['status'], ['paid', 'rejected'], true) ? auth()->id() : $fine->reviewed_by,
            'reviewed_at' => in_array($validated['status'], ['paid', 'rejected'], true) ? now() : $fine->reviewed_at,
        ]);

        return redirect()->route('admin.fines.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    public function delete($id)
    {
        Fine::findOrFail($id)->delete();
        return redirect()->route('admin.fines.index')->with('success', 'Data pembayaran berhasil dihapus!');
    }

    public function exportCSV(Request $request)
    {
        $status = $request->get('status');

        $query = Fine::with(['user', 'loan']);
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        $fines = $query->get();

        $filename = 'fines_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($fines) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Pengguna', 'ID Pinjaman', 'Jumlah', 'Deskripsi', 'Hari Keterlambatan', 'Status', 'Tanggal Bayar', 'Metode Pembayaran']);

            foreach ($fines as $index => $fine) {
                fputcsv($file, [
                    $index + 1,
                    $fine->user->name ?? '-',
                    $fine->loan_id,
                    'Rp ' . number_format($fine->amount, 0, ',', '.'),
                    $fine->description ?? '-',
                    $fine->late_days . ' hari',
                    match($fine->status) { 'unpaid' => 'Belum Lunas', 'pending_verification' => 'Menunggu Verifikasi', 'paid' => 'Lunas', 'rejected' => 'Ditolak', default => $fine->status },
                    $fine->payment_date ? $fine->payment_date->format('d-m-Y') : '-',
                    $fine->payment_method ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
