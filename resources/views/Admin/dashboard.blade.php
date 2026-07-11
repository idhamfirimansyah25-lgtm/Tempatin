@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow-sm border border-gray-200">
    <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
    <p class="text-gray-500 mb-6 text-sm">Waktu log terakhir Anda terhitung pada: {{ Auth::user()->last_login_at?->format('d M Y H:i') ?? '-' }}</p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="p-4 border rounded hover:border-indigo-500 bg-gray-50 block transition shadow-sm">
            <h3 class="font-bold text-lg text-indigo-600">Daftar Reservasi ➔</h3>
            <p class="text-xs text-gray-500 mt-1">Verifikasi lampiran pembayaran pelanggan, batalkan slot, atau lacak struk.</p>
        </a>
        <a href="{{ route('admin.fasilitas.index') }}" class="p-4 border rounded hover:border-green-500 bg-gray-50 block transition shadow-sm">
            <h3 class="font-bold text-lg text-green-600">Master Fasilitas ➔</h3>
            <p class="text-xs text-gray-500 mt-1">Kelola data harga, jam operasional, dan tambah fasilitas baru di sistem.</p>
        </a>
        <a href="{{ route('admin.kategori.index') }}" class="p-4 border rounded hover:border-orange-500 bg-gray-50 block transition shadow-sm">
            <h3 class="font-bold text-lg text-orange-600">Master Kategori ➔</h3>
            <p class="text-xs text-gray-500 mt-1">Kelompokkan fasilitas berdasarkan jenisnya (contoh: Lapangan, Ruangan).</p>
        </a>
    </div>
</div>
@endsection