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
            return back()->with('error', 'Hanya denda dengan bukti menunggu verifikasi yang bisa diproses.');
        }

        if ($validated['action'] === 'approve') {
            $fine->update([
                'status' => 'paid',
                'payment_date' => now(),
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_note' => $validated['review_note'] ?? null,
            ]);

            Notification::send($fine->user_id, 'Pembayaran Denda Diterima', 'Bukti pembayaran denda #' . $fine->id . ' diterima.', 'success', route('user.fines.detail', $fine->id));
            ActivityHelper::log('verify_fine', 'Menerima pembayaran denda #' . $fine->id);
            return back()->with('success', 'Pembayaran denda diterima.');
        }

        $fine->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'] ?? null,
        ]);

        Notification::send($fine->user_id, 'Pembayaran Denda Ditolak', 'Bukti pembayaran denda #' . $fine->id . ' ditolak. ' . ($validated['review_note'] ?? 'Silakan unggah ulang bukti yang valid.'), 'danger', route('user.fines.detail', $fine->id));
        ActivityHelper::log('verify_fine', 'Menolak pembayaran denda #' . $fine->id);

        return back()->with('success', 'Pembayaran denda ditolak.');
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

        return redirect()->route('admin.fines.index')->with('success', 'Status denda berhasil diupdate!');
    }

    public function delete($id)
    {
        Fine::findOrFail($id)->delete();
        return redirect()->route('admin.fines.index')->with('success', 'Data denda berhasil dihapus!');
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
            fputcsv($file, ['No', 'User', 'Loan ID', 'Amount', 'Description', 'Late Days', 'Status', 'Payment Date', 'Payment Method']);

            foreach ($fines as $index => $fine) {
                fputcsv($file, [
                    $index + 1,
                    $fine->user->name ?? '-',
                    $fine->loan_id,
                    'Rp ' . number_format($fine->amount, 0, ',', '.'),
                    $fine->description ?? '-',
                    $fine->late_days . ' hari',
                    $fine->status,
                    $fine->payment_date ? $fine->payment_date->format('d-m-Y') : '-',
                    $fine->payment_method ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
