@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-sm border border-gray-200">
    <h2 class="text-xl font-bold mb-6">{{ isset($fasilitas) ? 'Edit' : 'Tambah' }} Data Fasilitas</h2>
    
    <form action="{{ isset($fasilitas) ? route('admin.fasilitas.update', $fasilitas->id) : route('admin.fasilitas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($fasilitas)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Nama Fasilitas *</label>
                <input type="text" name="nama" value="{{ old('nama', $fasilitas->nama ?? '') }}" required class="w-full border rounded p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori *</label>
                <select name="kategori_fasilitas_id" required class="w-full border rounded p-2 bg-gray-50">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_fasilitas_id', $fasilitas->kategori_fasilitas_id ?? '') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Deskripsi Lengkap</label>
            <textarea name="deskripsi" rows="3" class="w-full border rounded p-2 bg-gray-50">{{ old('deskripsi', $fasilitas->deskripsi ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Harga Per Jam (Rp) *</label>
                <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $fasilitas->harga_per_jam ?? '') }}" required min="0" class="w-full border rounded p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Min. Durasi (Menit) *</label>
                <input type="number" name="durasi_minimum_menit" value="{{ old('durasi_minimum_menit', $fasilitas->durasi_minimum_menit ?? '60') }}" required class="w-full border rounded p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kapasitas (Orang)</label>
                <input type="number" name="kapasitas" value="{{ old('kapasitas', $fasilitas->kapasitas ?? '') }}" class="w-full border rounded p-2 bg-gray-50">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium mb-1">Jam Buka *</label>
                <input type="time" name="jam_buka" value="{{ old('jam_buka', isset($fasilitas) ? date('H:i', strtotime($fasilitas->jam_buka)) : '08:00') }}" required class="w-full border rounded p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Jam Tutup *</label>
                <input type="time" name="jam_tutup" value="{{ old('jam_tutup', isset($fasilitas) ? date('H:i', strtotime($fasilitas->jam_tutup)) : '22:00') }}" required class="w-full border rounded p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status Aktivasi *</label>
                <select name="status" required class="w-full border rounded p-2 bg-gray-50">
                    <option value="aktif" {{ old('status', $fasilitas->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif (Tampil)</option>
                    <option value="nonaktif" {{ old('status', $fasilitas->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Upload Gambar Cover (Opsional)</label>
            <input type="file" name="gambar" class="w-full border rounded p-2 bg-gray-50">
            @if(isset($fasilitas) && $fasilitas->gambar)
                <p class="text-xs text-gray-500 mt-2">Gambar saat ini ada. Upload baru untuk menimpa.</p>
            @endif
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.fasilitas.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded font-bold hover:bg-indigo-700">Simpan Data</button>
        </div>
    </form>
</div>
@endsection