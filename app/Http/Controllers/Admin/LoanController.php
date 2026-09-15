<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansExport;
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
        
        // Untuk dropdown filter
        $statuses = ['all', 'pending', 'approved', 'borrowed', 'returned', 'rejected'];
        
        return view('admin.loans.index', compact('loans', 'status', 'statuses'));
    }

    public function detail($id)
    {
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);
        return view('admin.loans.detail', compact('loan'));
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

        return redirect()->route('admin.loans.index')->with('success', 'Status peminjaman berhasil diupdate!');
    }

    public function delete($id)
    {
        $loan = Loan::findOrFail($id);
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
    
    $callback = function() use ($loans) {
        $file = fopen('php://output', 'w');
        
        // Biar Excel bisa baca UTF-8
        fwrite($file, "\xEF\xBB\xBF");
        
        // Header
        fputcsv($file, ['No', 'User', 'Tool', 'Loan Date', 'Return Date', 'Status', 'Notes']);
        
        // Data
        foreach ($loans as $index => $loan) {
            fputcsv($file, [
                $index + 1,
                $loan->user->name ?? '-',
                $loan->tool->name ?? '-',
                $loan->loan_date ? $loan->loan_date->format('d-m-Y') : '-',
                $loan->return_date ? $loan->return_date->format('d-m-Y') : '-',
                $loan->status,
                $loan->notes ?? '-',
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
public function approve($id)
{
    $loan = Loan::findOrFail($id);
    $loan->update(['status' => 'approved']);
    
    ActivityHelper::log('approve_loan', 'Menyetujui peminjaman #' . $loan->id . ' oleh ' . $loan->user->name);
    
    return redirect()->route('toolsman.loans.index')->with('success', 'Peminjaman disetujui!');
}
}