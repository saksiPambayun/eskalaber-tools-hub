<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityHelper;
use App\Models\FineSetting;
use App\Models\FineSettingHistory;
use Illuminate\Http\Request;

class FineSettingController extends Controller
{
    protected string $panel = 'admin';

    public function index()
    {
        $setting = FineSetting::current();
        $histories = FineSettingHistory::with('changedBy')->latest()->get();
        $panel = $this->panel;

        return view('fine-setting.index', compact('setting', 'histories', 'panel'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'rate_per_day' => 'required|integer|min:0|max:1000000',
        ], [
            'rate_per_day.min' => 'Tarif pembayaran tidak boleh negatif.',
            'rate_per_day.max' => 'Tarif pembayaran maksimal Rp 1.000.000 per hari.',
        ]);

        $setting = FineSetting::current();
        $oldRate = (int) $setting->rate_per_day;
        $changed = $setting->changeRate((int) $validated['rate_per_day'], auth()->user());

        if ($changed) {
            ActivityHelper::log(
                'update_fine_rate',
                'Mengubah tarif pembayaran dari Rp ' . number_format($oldRate, 0, ',', '.')
                    . ' menjadi Rp ' . number_format((int) $validated['rate_per_day'], 0, ',', '.') . ' per hari'
            );
        }

        return back()->with('success', $changed
            ? 'Tarif pembayaran berhasil diperbarui. Berlaku untuk pembayaran baru.'
            : 'Tarif pembayaran tidak berubah.');
    }
}
