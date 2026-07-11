@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-8">
        <div>
            <h2 class="font-manrope text-3xl font-bold text-slate mb-2">Daftar Transaksi</h2>
            <p class="text-sm text-gray-500">Kelola master data reservasi dan pantau status pembayaran.</p>
        </div>
        
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <select name="status" class="bg-white border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none font-medium min-w-[200px]">
                <option value="">Semua Status</option>
                <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="dp_terbayar" {{ request('status') === 'dp_terbayar' ? 'selected' : '' }}>DP Terbayar</option>
                <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-opacity-90 font-medium transition-colors shadow-sm text-sm whitespace-nowrap">
                Terapkan Filter
            </button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($bookings as $row)
            @php
                // Logika Warna Status berdasarkan panduan Jadwal Tenang
                $statusColor = match($row->status) {
                    'lunas' => 'text-success',
                    'dp_terbayar' => 'text-primary',
                    'menunggu_pembayaran' => 'text-pending',
                    'dibatalkan' => 'text-red-500',
                    default => 'text-gray-500'
                };
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row relative overflow-hidden transition-shadow hover:shadow-md">
                
                <div class="flex-1 p-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    
                    <div>
                        <span class="block text-xs text-gray-400 font-medium tracking-wider mb-1">{{ $row->kode_booking }}</span>
                        <span class="block text-base font-bold text-slate">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    <div>
                        <h3 class="font-manrope text-lg font-bold text-slate mb-1 line-clamp-1">{{ $row->fasilitas->nama }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-1">
                            {{ $row->nama_pelanggan }} <span class="text-gray-300 mx-1">•</span> {{ $row->no_hp }}
                        </p>
                    </div>

                    <div>
                        <span class="block text-base font-bold text-slate mb-1">{{ $row->tanggal_booking->format('d M Y') }}</span>
                        <span class="block text-sm text-gray-600">{{ date('H:i', strtotime($row->jam_mulai)) }} - {{ date('H:i', strtotime($row->jam_selesai)) }} WIB</span>
                    </div>
                </div>

                <div class="hidden md:flex relative flex-col justify-center items-center w-6 bg-white">
                    <div class="w-6 h-3 bg-background border-b border-gray-200 rounded-b-full absolute top-0 z-10"></div>
                    <div class="h-full border-l-2 border-dashed border-gray-200 my-3"></div>
                    <div class="w-6 h-3 bg-background border-t border-gray-200 rounded-t-full absolute bottom-0 z-10"></div>
                </div>
                
                <div class="md:hidden relative flex items-center bg-white">
                    <div class="h-6 w-3 bg-background border-r border-gray-200 rounded-r-full absolute left-0 z-10"></div>
                    <div class="w-full border-t-2 border-dashed border-gray-200 mx-3"></div>
                    <div class="h-6 w-3 bg-background border-l border-gray-200 rounded-l-full absolute right-0 z-10"></div>
                </div>

                <div class="p-6 md:w-56 flex flex-row md:flex-col justify-between items-center md:items-end bg-white">
                    <span class="{{ $statusColor }} font-bold text-sm tracking-wide uppercase mb-0 md:mb-4">
                        {{ str_replace('_', ' ', $row->status) }}
                    </span>
                    
                    <a href="{{ route('admin.bookings.show', $row->id) }}" class="bg-gray-50 text-slate px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors border border-gray-200 shadow-sm text-center whitespace-nowrap">
                        Kelola Detail
                    </a>
                </div>
            </div>

        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center flex flex-col items-center">
                <span class="block text-4xl mb-3">📭</span>
                <p class="text-slate font-semibold text-lg">Tidak ada data reservasi.</p>
                <p class="text-sm text-gray-500 mt-1">Coba ubah status filter pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
        <div class="mt-8 border-t border-gray-200 pt-6">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection