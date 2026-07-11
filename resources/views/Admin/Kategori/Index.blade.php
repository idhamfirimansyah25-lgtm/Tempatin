@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Kelola Kategori Fasilitas</h2>
        <a href="{{ route('admin.kategori.create') }}" class="bg-indigo-600 text-white px-4 py-2 text-sm rounded font-bold hover:bg-indigo-700">+ Tambah Kategori</a>
    </div>

    <table class="w-full text-left text-sm border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="p-3 font-semibold w-1/4">Nama Kategori</th>
                <th class="p-3 font-semibold w-1/2">Deskripsi</th>
                <th class="p-3 font-semibold text-center w-1/4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $row)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold text-gray-800">{{ $row->nama_kategori }}</td>
                    <td class="p-3 text-gray-600">{{ $row->deskripsi ?? '-' }}</td>
                    <td class="p-3 text-center flex justify-center space-x-2">
                        <a href="{{ route('admin.kategori.edit', $row->id) }}" class="text-blue-600 font-bold hover:underline">Edit</a>
                        <form action="{{ route('admin.kategori.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-bold hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="p-4 text-center text-gray-500">Belum ada data kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $kategori->links() }}</div>
</div>
@endsection