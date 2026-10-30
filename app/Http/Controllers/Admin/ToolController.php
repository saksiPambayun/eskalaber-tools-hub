<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Type;
use App\Models\Place;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with(['category', 'type', 'place'])->get();
        return view('admin.tools.index', compact('tools'));
    }

    public function add()
    {
        $categories = Category::all();
        $types = Type::all();
        $places = Place::all();
        return view('admin.tools.add', compact('categories', 'types', 'places'));
    }

    public function doCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:tools',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'place_id' => 'required|exists:places,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/tools'), $imageName);
            $data['image'] = 'uploads/tools/' . $imageName;
        }

        $tool = Tool::create($data);

        // Generate QR Code Otomatis saat alat dibuat
        $this->generateToolQRCode($tool);

        return redirect()->route('admin.tools.index')->with('success', 'Tool berhasil ditambahkan!');
    }

    public function detail($id)
    {
        $tool = Tool::with(['category', 'type', 'place'])->findOrFail($id);
        return view('admin.tools.detail', compact('tool'));
    }

    public function update($id)
    {
        $tool = Tool::findOrFail($id);
        $categories = Category::all();
        $types = Type::all();
        $places = Place::all();
        return view('admin.tools.update', compact('tool', 'categories', 'types', 'places'));
    }

    public function doUpdate(Request $request, $id)
    {
        $tool = Tool::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:tools,code,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'place_id' => 'required|exists:places,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($tool->image && file_exists(public_path($tool->image))) {
                unlink(public_path($tool->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/tools'), $imageName);
            $data['image'] = 'uploads/tools/' . $imageName;
        }

        $tool->update($data);

        return redirect()->route('admin.tools.index')->with('success', 'Tool berhasil diupdate!');
    }

 public function delete($id)
{
    $tool = Tool::findOrFail($id);

    // 1. Cek apakah alat ini pernah/sedang dipinjam
    $cekPinjaman = \App\Models\Loan::where('tool_id', $id)->exists();

    if ($cekPinjaman) {
        return redirect()->route('admin.tools.index')->with('error', 'Gagal menghapus! Alat ini memiliki riwayat peminjaman.');
    }

    // 2. Hapus image jika ada
    if ($tool->image && file_exists(public_path($tool->image))) {
        unlink(public_path($tool->image));
    }

    // 3. Hapus QR Code jika ada
    if ($tool->qr_code && file_exists(public_path($tool->qr_code))) {
        unlink(public_path($tool->qr_code));
    }

    // 4. Hapus dari database
    $tool->delete();

    return redirect()->route('admin.tools.index')->with('success', 'Tool berhasil dihapus!');
}

    public function generateQR($id)
    {
        $tool = Tool::findOrFail($id);

        // Panggil fungsi private untuk generate QR
        $this->generateToolQRCode($tool);

        return redirect()->route('admin.tools.preview-qr', $tool->id)->with('success', 'QR Code berhasil digenerate!');
    }

    public function previewQR($id)
    {
        $tool = Tool::findOrFail($id);
        return view('admin.tools.preview-qr', compact('tool'));
    }

    /**
     * Fungsi Private untuk membuat QR Code secara dinamis
     * Otomatis mendeteksi apakah sedang diakses via Ngrok, IP LAN, atau Localhost
     */
   private function generateToolQRCode($tool)
{
    // GANTI DENGAN URL NGROK YANG MUNCUL DI TERMINAL ANDA SAAT INI
    $baseUrl = 'https://basis-designed-antsy.ngrok-free.dev';

    // URL katalog publik
    $catalogUrl = $baseUrl . '/catalog/detail/' . $tool->id;

    // Generate QR Code (SVG)
    $qrCode = QrCode::size(300)->generate($catalogUrl);

    $qrFileName = 'qr_' . $tool->id . '.svg';

    if (!file_exists(public_path('uploads/qr'))) {
        mkdir(public_path('uploads/qr'), 0777, true);
    }

    file_put_contents(public_path('uploads/qr/' . $qrFileName), $qrCode);

    $tool->update([
        'qr_code' => 'uploads/qr/' . $qrFileName
    ]);
}
}
