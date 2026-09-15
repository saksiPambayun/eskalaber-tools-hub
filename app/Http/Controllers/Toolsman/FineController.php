<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;
use App\Helpers\ActivityHelper;

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
        
        return view('toolsman.fines.index', compact('fines', 'status'));
    }

    public function paid($id)
    {
        $fine = Fine::findOrFail($id);
        $fine->update([
            'status' => 'paid',
            'payment_date' => now(),
            'payment_method' => 'cash',
        ]);

        // Log activity
        ActivityHelper::log('pay_fine', 'Membayar denda #' . $fine->id . ' sebesar Rp ' . number_format($fine->amount, 0, ',', '.') . ' untuk user ' . ($fine->user->name ?? 'User'));

        return redirect()->route('toolsman.fines.index')->with('success', 'Denda sudah dibayar!');
    }
    public function updateStatus(Request $request, $id)
{
    $fine = Fine::findOrFail($id);
    $fine->update([
        'status' => $request->status,
        'payment_date' => $request->status == 'paid' ? now() : null
    ]);

    // Log activity
    ActivityHelper::log('update_fine_status', 'Mengubah status denda #' . $fine->id . ' menjadi ' . $request->status);

    return redirect()->route('admin.fines.index')->with('success', 'Status denda berhasil diupdate!');
}
}
