@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-sm border border-gray-200">
    <h2 class="text-xl font-bold mb-6">{{ isset($kategori) ? 'Edit' : 'Tambah' }} Kategori Fasilitas</h2>
    
    <form action="{{ isset($kategori) ? route('admin.kategori.update', $kategori->id) : route('admin.kategori.store') }}" method="POST">
        @csrf
        @if(isset($kategori)) @method('PUT') @endif

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Kategori *</label>
            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" required class="w-full border rounded p-2 bg-gray-50">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="3" class="w-full border rounded p-2 bg-gray-50">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.kategori.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded font-bold hover:bg-indigo-700">Simpan Data</button>
        </div>
    </form>
</div>
@endsection