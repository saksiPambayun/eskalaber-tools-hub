<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Services\LoanWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansExport;

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
        $statuses = ['all', 'pending', 'approved', 'returned', 'rejected', 'cancelled'];

        return view('admin.loans.index', compact('loans', 'status', 'statuses'));
    }

    public function detail($id)
    {
        $loan = Loan::with(['user', 'tool', 'fine'])->findOrFail($id);
        return view('admin.loans.detail', compact('loan'));
    }

    public function updateStatus(Request $request, $id, LoanWorkflowService $workflow)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,returned,cancelled',
        ]);

        $loan = Loan::findOrFail($id);

        try {
            match ($validated['status']) {
                'approved' => $workflow->approve($loan, auth()->user()),
                'rejected' => $workflow->reject($loan, auth()->user()),
                'returned' => $workflow->returnLoan($loan, auth()->user()),
                'cancelled' => $workflow->cancel($loan, auth()->user()),
            };
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('admin.loans.index')->with('success', 'Status peminjaman berhasil diperbarui!');
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

    public function delete($id)
    {
        $loan = Loan::with('fine')->findOrFail($id);

        if (in_array($loan->status, ['approved', 'borrowed', 'late'], true)) {
            return back()->with('error', 'Peminjaman aktif tidak bisa dihapus. Kembalikan alat terlebih dahulu.');
        }

        if ($loan->fine) {
            return back()->with('error', 'Peminjaman yang memiliki pembayaran tidak bisa dihapus.');
        }

        $loan->delete();
        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil dihapus!');
    }

    public function exportExcel(Request $request)
    {
        $status = $request->get('status');
        return Excel::download(new LoansExport($status), 'loans_' . date('Y-m-d') . '.xlsx');
    }

    public function exportCSV(Request $request)
    {
        $status = $request->get('status');

        $query = Loan::with(['user', 'tool']);
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        $loans = $query->get();

        $filename = 'loans_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($loans) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Pengguna', 'Alat', 'Tanggal Pinjam', 'Tanggal Kembali', 'Tanggal Kembali Aktual', 'Status', 'Catatan']);

            foreach ($loans as $index => $loan) {
                fputcsv($file, [
                    $index + 1,
                    $loan->user->name ?? '-',
                    $loan->tool->name ?? '-',
                    $loan->loan_date ? $loan->loan_date->format('d-m-Y') : '-',
                    $loan->return_date ? $loan->return_date->format('d-m-Y') : '-',
                    $loan->actual_return_date ? $loan->actual_return_date->format('d-m-Y') : '-',
                    match($loan->status) { 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'cancelled' => 'Dibatalkan', 'rejected' => 'Ditolak', default => $loan->status },
                    $loan->notes ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
