<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Helpers\ActivityHelper;
use App\Models\Fine;
use App\Models\Notification;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::with(['loan.tool', 'paymentMethod'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.fines.index', compact('fines'));
    }

    public function detail($id)
    {
        $fine = Fine::with(['loan.tool', 'paymentMethod', 'reviewer'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('name')->get();

        return view('user.fines.detail', compact('fine', 'paymentMethods'));
    }

    public function pay(Request $request, $id)
    {
        $fine = Fine::where('user_id', auth()->id())->findOrFail($id);

        if (!in_array($fine->status, ['unpaid', 'rejected'], true)) {
            return back()->with('error', 'Pembayaran ini tidak dapat dibayarkan pada status saat ini.');
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_reference' => 'nullable|string|max:100',
            'proof_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'proof_image.required' => 'Bukti pembayaran wajib diunggah.',
            'proof_image.mimes' => 'Bukti pembayaran harus berupa PNG, JPG, atau JPEG.',
        ]);

        $method = PaymentMethod::where('id', $validated['payment_method_id'])->where('is_active', true)->firstOrFail();

        $dir = public_path('uploads/fine-proofs');
        File::ensureDirectoryExists($dir);
        if ($fine->proof_image && is_file(public_path($fine->proof_image))) {
            @unlink(public_path($fine->proof_image));
        }

        $file = $request->file('proof_image');
        $name = 'fine_' . $fine->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        $fine->update([
            'payment_method_id' => $method->id,
            'payment_method' => $method->name,
            'payment_reference' => $validated['payment_reference'] ?? null,
            'proof_image' => 'uploads/fine-proofs/' . $name,
            'submitted_at' => now(),
            'status' => 'pending_verification',
            'review_note' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        Notification::sendToAdmins(
            'Bukti Pembayaran',
            auth()->user()->name . ' mengunggah bukti pembayaran #' . $fine->id . '.',
            'info',
            route('admin.fines.detail', $fine->id)
        );
        Notification::sendToToolsmans(
            'Bukti Pembayaran',
            auth()->user()->name . ' mengunggah bukti pembayaran #' . $fine->id . '.',
            'info',
            route('toolsman.fines.detail', $fine->id)
        );

        ActivityHelper::log('submit_fine_payment', 'Mengunggah bukti pembayaran #' . $fine->id);

        return redirect()->route('user.fines.detail', $fine->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi petugas/admin.');
    }
}
