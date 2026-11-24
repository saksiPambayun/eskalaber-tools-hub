<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PaymentMethodController extends Controller
{
    protected string $panel = 'admin';

    public function index()
    {
        $methods = PaymentMethod::latest()->get();
        $panel = $this->panel;
        return view('payment-methods.index', compact('methods', 'panel'));
    }

    public function add()
    {
        $panel = $this->panel;
        return view('payment-methods.form', [
            'panel' => $panel,
            'method' => new PaymentMethod(['is_active' => true, 'type' => 'bank']),
            'isEdit' => false,
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->validated($request);
        $data['qr_image'] = $this->uploadQr($request);
        PaymentMethod::create($data);

        return redirect()->route($this->panel . '.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $panel = $this->panel;
        return view('payment-methods.form', [
            'panel' => $panel,
            'method' => $method,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $data = $this->validated($request);
        $qr = $this->uploadQr($request, $method->qr_image);
        if ($qr !== null) {
            $data['qr_image'] = $qr;
        }
        $method->update($data);

        return redirect()->route($this->panel . '.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $method = PaymentMethod::findOrFail($id);
        $this->deleteQr($method->qr_image);
        $method->delete();

        return redirect()->route($this->panel . '.payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:bank,ewallet,qris,other',
            'account_number' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'qr_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    protected function uploadQr(Request $request, ?string $old = null): ?string
    {
        if (!$request->hasFile('qr_image')) {
            return null;
        }

        $this->deleteQr($old);
        $dir = public_path('uploads/payment-methods');
        File::ensureDirectoryExists($dir);
        $file = $request->file('qr_image');
        $name = 'pm_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return 'uploads/payment-methods/' . $name;
    }

    protected function deleteQr(?string $path): void
    {
        if (!$path) return;
        $full = public_path($path);
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
