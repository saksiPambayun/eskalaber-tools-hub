<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        $logs = $this->query($from, $to)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('activity_logs.index', compact('logs', 'from', 'to'));
    }

    public function exportCsv(Request $request)
    {
        $validated = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $logs = $this->query($validated['from'] ?? null, $validated['to'] ?? null)
            ->latest()
            ->get();

        $filename = 'log_aktivitas_' . date('Y-m-d') . '.csv';

        return response()->stream(function () use ($logs) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Pengguna', 'Aksi', 'Deskripsi', 'IP Alamat', 'Waktu']);

            foreach ($logs as $index => $log) {
                fputcsv($file, [
                    $index + 1,
                    $log->user->name ?? '-',
                    $log->actionLabel(),
                    $log->description ?? '-',
                    $log->ip_address ?? '-',
                    $log->created_at ? $log->created_at->format('d-m-Y H:i:s') : '-',
                ]);
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function query(?string $from, ?string $to)
    {
        $query = ActivityLog::with('user');

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }
}
