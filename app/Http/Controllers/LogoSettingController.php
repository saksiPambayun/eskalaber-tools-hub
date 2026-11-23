<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityHelper;
use App\Models\LogoSetting;
use Illuminate\Http\Request;

class LogoSettingController extends Controller
{
    /**
     * Prefix route dashboard pengelola: admin | toolsman.
     */
    protected string $panel = 'admin';

    public function index()
    {
        $setting = LogoSetting::current();
        $panel = $this->panel;

        return view('logo-setting.index', compact('setting', 'panel'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $setting = LogoSetting::firstOrCreate([], [
            'image' => LogoSetting::DEFAULT_IMAGE,
            'title' => LogoSetting::DEFAULT_TITLE,
            'subtitle' => LogoSetting::DEFAULT_SUBTITLE,
        ]);

        $setting->title = $validated['title'];
        $setting->subtitle = $validated['subtitle'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Hapus file lama milik pengaturan (jangan hapus gambar default bawaan).
            if (
                $setting->image
                && $setting->image !== LogoSetting::DEFAULT_IMAGE
                && file_exists(public_path($setting->image))
            ) {
                unlink(public_path($setting->image));
            }

            $directory = public_path('uploads/logo');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $fileName = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $fileName);

            $setting->image = 'uploads/logo/' . $fileName;
        }

        $setting->save();

        ActivityHelper::log(
            'UPDATE_LOGO_SETTING',
            'Logo animasi katalog diperbarui oleh ' . auth()->user()->role
        );

        return redirect()->route($this->panel . '.logo-setting.index')
            ->with('success', 'Logo dan teks berhasil diperbarui.');
    }
}
