<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Http\Requests\StoreFasilitasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::with('kategoriFasilitas')->latest()->paginate(10);
        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        $kategori = KategoriFasilitas::all();
        return view('admin.fasilitas.form', compact('kategori'));
    }

    public function store(StoreFasilitasRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('fasilitas', 'public');
        }

        Fasilitas::create($data);
        return redirect()->route('admin.fasilitas.index')->with('success', 'Data fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $kategori = KategoriFasilitas::all();
        return view('admin.fasilitas.form', compact('fasilitas', 'kategori'));
    }

    public function update(StoreFasilitasRequest $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($fasilitas->gambar && Storage::disk('public')->exists($fasilitas->gambar)) {
                Storage::disk('public')->delete($fasilitas->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('fasilitas', 'public');
        }

        $fasilitas->update($data);
        return redirect()->route('admin.fasilitas.index')->with('success', 'Data fasilitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        // Jangan hapus jika ada booking terkait
        if ($fasilitas->bookings()->count() > 0) {
            return back()->withErrors(['error' => 'Gagal menghapus! Fasilitas ini memiliki riwayat pemesanan/booking. Ubah status menjadi Nonaktif saja.']);
        }

        if ($fasilitas->gambar && Storage::disk('public')->exists($fasilitas->gambar)) {
            Storage::disk('public')->delete($fasilitas->gambar);
        }

        $fasilitas->delete();
        return redirect()->route('admin.fasilitas.index')->with('success', 'Data fasilitas berhasil dihapus.');
    }
}