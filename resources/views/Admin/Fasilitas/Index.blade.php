@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Kelola Data Fasilitas Utama</h2>
        <a href="{{ route('admin.fasilitas.create') }}" class="bg-indigo-600 text-white px-4 py-2 text-sm rounded font-bold hover:bg-indigo-700">+ Tambah Fasilitas</a>
    </div>

    <table class="w-full text-left text-sm border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="p-3 font-semibold">Nama Fasilitas</th>
                <th class="p-3 font-semibold">Kategori</th>
                <th class="p-3 font-semibold">Harga/Jam</th>
                <th class="p-3 font-semibold text-center">Status</th>
                <th class="p-3 font-semibold text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fasilitas as $row)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-bold">{{ $row->nama }}</td>
                    <td class="p-3">{{ $row->kategoriFasilitas->nama_kategori }}</td>
                    <td class="p-3">Rp {{ number_format($row->harga_per_jam, 0, ',', '.') }}</td>
                    <td class="p-3 text-center">
                        <span class="px-2 py-1 text-xs rounded font-bold uppercase {{ $row->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $row->status }}
                        </span>
                    </td>
                    <td class="p-3 text-center flex justify-center space-x-2">
                        <a href="{{ route('admin.fasilitas.edit', $row->id) }}" class="text-blue-600 font-bold hover:underline">Edit</a>
                        <form action="{{ route('admin.fasilitas.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 font-bold hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-4 text-center text-gray-500">Belum ada data fasilitas.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $fasilitas->links() }}</div>
</div>
@endsection