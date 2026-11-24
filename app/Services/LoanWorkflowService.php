<?php

namespace App\Services;

use App\Helpers\ActivityHelper;
use App\Models\Fine;
use App\Models\FineSetting;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanWorkflowService
{
    public const FINE_PER_DAY = 10000; // Fallback legacy, prefer FineSetting::rate()

    public function approve(Loan $loan, $actor = null): Loan
    {
        return DB::transaction(function () use ($loan, $actor) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->with(['user', 'tool'])->firstOrFail();

            if ($loan->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Hanya peminjaman pending yang bisa disetujui.']);
            }

            $tool = Tool::whereKey($loan->tool_id)->lockForUpdate()->firstOrFail();
            if ($tool->stock < 1) {
                throw ValidationException::withMessages(['stock' => 'Stok alat tidak mencukupi untuk disetujui.']);
            }

            $loan->update(['status' => 'approved']);
            $tool->decrement('stock', 1);
            $tool->refresh();
            if ($tool->stock <= 0) {
                $tool->update(['status' => 'borrowed']);
            }

            Notification::send(
                $loan->user_id,
                'Peminjaman Disetujui',
                'Peminjaman alat ' . ($loan->tool->name ?? 'alat') . ' telah disetujui.',
                'success',
                route('user.loans.detail', $loan->id)
            );

            ActivityHelper::log('approve_loan', 'Menyetujui peminjaman #' . $loan->id . ' oleh ' . ($loan->user->name ?? 'User'));

            return $loan->fresh(['user', 'tool', 'fine']);
        });
    }

    public function reject(Loan $loan, $actor = null): Loan
    {
        return DB::transaction(function () use ($loan, $actor) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->with(['user', 'tool'])->firstOrFail();

            if ($loan->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Hanya peminjaman pending yang bisa ditolak.']);
            }

            $loan->update(['status' => 'rejected']);

            Notification::send(
                $loan->user_id,
                'Peminjaman Ditolak',
                'Peminjaman alat ' . ($loan->tool->name ?? 'alat') . ' ditolak.',
                'danger',
                route('user.loans.detail', $loan->id)
            );

            ActivityHelper::log('reject_loan', 'Menolak peminjaman #' . $loan->id . ' oleh ' . ($loan->user->name ?? 'User'));

            return $loan->fresh(['user', 'tool', 'fine']);
        });
    }

    public function cancel(Loan $loan, $actor = null): Loan
    {
        return DB::transaction(function () use ($loan, $actor) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->with(['user', 'tool'])->firstOrFail();

            if ($loan->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Hanya peminjaman pending yang bisa dibatalkan.']);
            }

            $loan->update(['status' => 'cancelled']);
            ActivityHelper::log('cancel_loan', 'Membatalkan peminjaman #' . $loan->id);

            return $loan->fresh(['user', 'tool', 'fine']);
        });
    }

    public function returnLoan(Loan $loan, $actor = null, ?\Carbon\Carbon $actualReturn = null): Loan
    {
        return DB::transaction(function () use ($loan, $actor, $actualReturn) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->with(['user', 'tool', 'fine'])->firstOrFail();

            if (!in_array($loan->status, ['approved', 'borrowed', 'late'], true)) {
                throw ValidationException::withMessages(['status' => 'Hanya peminjaman yang sedang berjalan yang bisa dikembalikan.']);
            }

            $actualReturn = $actualReturn ? $actualReturn->copy()->startOfDay() : now();
            if ($loan->loan_date && $actualReturn->lt($loan->loan_date->startOfDay())) {
                throw ValidationException::withMessages(['actual_return_date' => 'Tanggal kembali aktual tidak boleh sebelum tanggal pinjam.']);
            }

            $loan->update([
                'status' => 'returned',
                'actual_return_date' => $actualReturn,
            ]);

            $tool = Tool::whereKey($loan->tool_id)->lockForUpdate()->firstOrFail();
            $tool->increment('stock', 1);
            $tool->refresh();
            if (!in_array($tool->status, ['broken', 'maintenance'], true)) {
                $tool->update(['status' => 'available']);
            }

            $loan->refresh();
            $lateDays = $loan->getLateDays();
            $fineCreated = false;
            $fineAmount = 0;

            if ($lateDays > 0 && !$loan->fine) {
                $ratePerDay = FineSetting::rate();
                $fineAmount = $lateDays * $ratePerDay;
                Fine::create([
                    'loan_id' => $loan->id,
                    'user_id' => $loan->user_id,
                    'amount' => $fineAmount,
                    'description' => 'Denda keterlambatan ' . $lateDays . ' hari (Rp ' . number_format($ratePerDay, 0, ',', '.') . '/hari)',
                    'late_days' => $lateDays,
                    'status' => 'unpaid',
                ]);
                $fineCreated = true;

                Notification::send(
                    $loan->user_id,
                    'Denda Keterlambatan',
                    'Anda mendapat denda Rp ' . number_format($fineAmount, 0, ',', '.') . ' karena terlambat mengembalikan alat. Silakan unggah bukti pembayaran.',
                    'warning',
                    route('user.fines.index')
                );
            }

            $logMessage = 'Mengembalikan alat ' . ($loan->tool->name ?? 'Tool') . ' dari peminjaman #' . $loan->id;
            if ($fineCreated) {
                $logMessage .= ' (Denda Rp ' . number_format($fineAmount, 0, ',', '.') . ' dibuat)';
            }
            ActivityHelper::log('return_loan', $logMessage);

            return $loan->fresh(['user', 'tool', 'fine']);
        });
    }
}
