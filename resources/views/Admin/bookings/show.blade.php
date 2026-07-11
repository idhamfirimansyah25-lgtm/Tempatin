@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="font-manrope text-3xl font-bold text-slate mb-1">Manajemen Detail Reservasi</h2>
            <p class="text-sm text-gray-500">Tinjau informasi jadwal, tagihan, dan verifikasi pembayaran.</p>
        </div>
        
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-400 hover:text-primary transition-colors font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start relative">
        
        <div class="w-full lg:w-[65%] space-y-6">
            
            @php
                $statusBgColor = match($booking->status) {
                    'lunas' => 'bg-success/10 text-success border-success/20',
                    'dp_terbayar' => 'bg-primary/10 text-primary border-primary/20',
                    'menunggu_pembayaran' => 'bg-pending/10 text-pending border-pending/20',
                    'dibatalkan' => 'bg-red-50 text-red-600 border-red-200',
                    default => 'bg-gray-100 text-gray-600 border-gray-200'
                };
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col relative overflow-hidden">
                <div class="px-8 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 bg-gray-50/30">
                    <span class="text-sm font-semibold tracking-wider text-gray-400">#{{ $booking->kode_booking }}</span>
                    <span class="px-3 py-1.5 text-xs font-bold uppercase rounded-lg border tracking-wide {{ $statusBgColor }}">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>

                <div class="p-8 pb-6">
                    <h3 class="font-manrope text-2xl font-bold text-slate mb-6">{{ $booking->fasilitas->nama }}</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <span class="block text-xs text-gray-400 mb-1">Nama Pemesan</span>
                            <span class="block text-sm font-semibold text-slate">{{ $booking->nama_pelanggan }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-400 mb-1">Kontak WhatsApp</span>
                            <span class="block text-sm font-semibold text-slate">{{ $booking->no_hp }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-400 mb-1">Alamat Email</span>
                            <span class="block text-sm font-semibold text-slate">{{ $booking->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="relative flex items-center">
                    <div class="h-6 w-3 bg-background border-r border-gray-200 rounded-r-full absolute left-0 z-10"></div>
                    <div class="w-full border-t-2 border-dashed border-gray-200 mx-3"></div>
                    <div class="h-6 w-3 bg-background border-l border-gray-200 rounded-l-full absolute right-0 z-10"></div>
                </div>

                <div class="px-8 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Tanggal & Waktu Reservasi</span>
                        <span class="block text-xl font-bold text-slate">{{ $booking->tanggal_booking->format('d F Y') }}</span>
                    </div>
                    <div class="sm:text-right">
                        <span class="block text-xl font-bold text-slate mt-4 sm:mt-0">{{ date('H:i', strtotime($booking->jam_mulai)) }} - {{ date('H:i', strtotime($booking->jam_selesai)) }} WIB</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Total Tagihan (Invoice)</span>
                        <span class="block text-lg font-bold text-slate">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">DP Terverifikasi</span>
                        <span class="block text-lg font-bold text-success">Rp {{ number_format($booking->dp_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-4 sm:pt-0 border-t sm:border-t-0 sm:border-l border-gray-100 sm:pl-6">
                        <span class="block text-xs text-gray-400 mb-1">Sisa Piutang</span>
                        <span class="block text-xl font-bold text-pending">Rp {{ number_format($booking->sisa_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if(!in_array($booking->status, ['lunas', 'dibatalkan', 'selesai']))
                    <div class="bg-gray-50/50 border-t border-gray-200 p-6 flex justify-end">
                        <form action="{{ route('admin.bookings.batalkan', $booking->id) }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="bg-white text-red-500 border border-red-200 hover:bg-red-50 font-medium px-5 py-2.5 text-sm rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Batalkan Reservasi
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="w-full lg:w-[35%] lg:sticky lg:top-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-manrope text-lg font-bold text-slate mb-5">Verifikasi Pembayaran</h3>
                
                @if($booking->pembayarans->isEmpty())
                    <div class="border border-dashed border-gray-200 rounded-lg p-8 text-center bg-gray-50/50">
                        <p class="text-sm text-gray-400">Belum ada slip transaksi yang diunggah oleh pelanggan.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($booking->pembayarans as $bayar)
                            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm transition-shadow hover:shadow-md">
                                
                                <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                                    <span class="font-bold text-sm uppercase text-slate tracking-wide">{{ $bayar->tipe }}</span>
                                    <span class="text-xs font-bold uppercase tracking-wide
                                        {{ $bayar->status_verifikasi === 'terverifikasi' ? 'text-success' : ($bayar->status_verifikasi === 'ditolak' ? 'text-red-500' : 'text-pending') }}">
                                        {{ $bayar->status_verifikasi }}
                                    </span>
                                </div>
                                
                                <div class="p-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-xs text-gray-500">Nominal Transfer</span>
                                        <span class="text-base font-bold text-slate">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-xs text-gray-500">Metode</span>
                                        <span class="text-sm font-semibold text-slate uppercase">{{ $bayar->metode_bayar }}</span>
                                    </div>
                                    
                                    @if($bayar->bukti_bayar)
                                        <div class="mt-4 mb-4 border border-gray-200 p-1 rounded-lg bg-gray-50 relative group">
                                            <img src="{{ asset('storage/' . $bayar->bukti_bayar) }}" alt="Bukti Transfer" class="w-full h-32 object-cover rounded">
                                            
                                            <a href="{{ asset('storage/' . $bayar->bukti_bayar) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center text-white text-xs font-semibold gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Penuh
                                            </a>
                                        </div>
                                    @endif

                                    @if($bayar->status_verifikasi === 'menunggu')
                                        <form action="{{ route('admin.pembayaran.konfirmasi', $bayar->id) }}" method="POST" class="mt-4 border-t border-gray-100 pt-4">
                                            @csrf
                                            <button type="submit" class="w-full bg-success text-white font-medium p-2.5 rounded-lg text-sm hover:bg-opacity-90 transition-all flex justify-center items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Verifikasi Pembayaran
                                            </button>
                                        </form>
                                    @else
                                        <div class="mt-4 border-t border-gray-100 pt-4 text-center">
                                            <p class="text-xs text-gray-400">Diverifikasi oleh: <span class="font-medium">{{ $bayar->verifikator?->name ?? 'Sistem' }}</span></p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>
@endsection