<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Fine::with(['user', 'loan']);
        
        if ($status != 'all') {
            $query->where('status', $status);
        }
        
        $fines = $query->latest()->paginate(10);
        
        return view('admin.fines.index', compact('fines', 'status'));
    }

    public function detail($id)
    {
        $fine = Fine::with(['user', 'loan'])->findOrFail($id);
        return view('admin.fines.detail', compact('fine'));
    }

    public function updateStatus(Request $request, $id)
    {
        $fine = Fine::findOrFail($id);
        $fine->update([
            'status' => $request->status,
            'payment_date' => $request->status == 'paid' ? now() : null
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
        
        $callback = function() use ($fines) {
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
                    ucfirst($fine->status),
                    $fine->payment_date ? $fine->payment_date->format('d-m-Y') : '-',
                    $fine->payment_method ?? '-',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}