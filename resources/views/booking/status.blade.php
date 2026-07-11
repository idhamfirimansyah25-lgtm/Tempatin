@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h2 class="font-manrope text-2xl font-bold text-slate mb-2">Cek Status Reservasi</h2>
        <p class="text-sm text-gray-500 mb-6">Masukkan kode booking Anda untuk melihat detail jadwal dan status pembayaran.</p>
        
        <form action="{{ route('booking.status') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="kode" value="{{ request('kode') }}" placeholder="Contoh: BK-20260709-XXXX" required class="w-full sm:flex-1 bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none font-medium tracking-wide">
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-opacity-90 font-medium transition-colors shadow-sm whitespace-nowrap">
                Cari Jadwal
            </button>
        </form>
    </div>

    @if(isset($booking))
        
        @php
            // Mapping warna status berdasarkan aturan "Jadwal Tenang"
            $statusColor = match($booking->status) {
                'lunas' => 'text-success',
                'dp_terbayar' => 'text-primary',
                'menunggu_pembayaran' => 'text-pending',
                'dibatalkan' => 'text-red-500', // Pengecualian standar untuk error/batal
                'selesai' => 'text-gray-500',
                default => 'text-gray-500'
            };
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col relative overflow-hidden transition-shadow hover:shadow-md">
            
            <div class="px-8 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="block text-sm text-gray-400 font-medium tracking-wider mb-1">{{ $booking->kode_booking }}</span>
                    <h3 class="font-manrope text-2xl font-bold text-slate">{{ $booking->fasilitas->nama }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Pemesan: <span class="font-semibold text-slate">{{ $booking->nama_pelanggan }}</span></p>
                </div>
                <div class="sm:text-right">
                    <span class="{{ $statusColor }} font-bold text-sm tracking-wide uppercase">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </div>
            </div>

            <div class="relative flex items-center">
                <div class="h-6 w-3 bg-background border-r border-gray-200 rounded-r-full absolute left-0 z-10"></div>
                <div class="w-full border-t-2 border-dashed border-gray-200 mx-3"></div>
                <div class="h-6 w-3 bg-background border-l border-gray-200 rounded-l-full absolute right-0 z-10"></div>
            </div>

            <div class="px-8 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
                <div>
                    <span class="block text-2xl font-bold text-slate">{{ $booking->tanggal_booking->format('d F Y') }}</span>
                    <span class="block text-base text-gray-600 mt-1">{{ date('H:i', strtotime($booking->jam_mulai)) }} - {{ date('H:i', strtotime($booking->jam_selesai)) }} WIB</span>
                </div>
                <div class="sm:text-right">
                    <span class="block text-xs text-gray-400 mb-1">Total Tagihan</span>
                    <span class="block text-xl font-bold text-slate">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 p-8 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <span class="block text-xs text-gray-500 mb-1">Total Biaya</span>
                    <span class="block text-base font-bold text-slate">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 mb-1">Sudah Dibayar</span>
                    <span class="block text-base font-bold text-success">Rp {{ number_format($booking->dp_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <span class="block text-xs text-gray-500 mb-1">Sisa Pembayaran</span>
                    <span class="block text-lg font-bold text-pending">Rp {{ number_format($booking->sisa_bayar, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-8">
                <h4 class="font-manrope text-lg font-bold text-slate mb-5">Riwayat Pembayaran</h4>
                
                @if($booking->pembayarans->isEmpty())
                    <p class="text-sm text-gray-400 italic">Belum ada riwayat rekaman pembayaran untuk reservasi ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach($booking->pembayarans as $bayar)
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                <div>
                                    <span class="block font-semibold text-slate uppercase text-sm mb-0.5">
                                        {{ $bayar->tipe }}
                                    </span>
                                    <span class="block text-xs text-gray-500">
                                        Metode: <span class="font-medium text-gray-700">{{ strtoupper($bayar->metode_bayar) }}</span>
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="block font-bold text-slate text-sm mb-0.5">
                                        Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                                    </span>
                                    <span class="block text-xs font-bold uppercase tracking-wide
                                        {{ $bayar->status_verifikasi === 'terverifikasi' ? 'text-success' : ($bayar->status_verifikasi === 'ditolak' ? 'text-red-500' : 'text-pending') }}">
                                        {{ $bayar->status_verifikasi }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if(in_array($booking->status, ['menunggu_pembayaran', 'dp_terbayar']))
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="mb-6">
                    <h4 class="font-manrope text-xl font-bold text-slate">Unggah Bukti Pembayaran</h4>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi pembayaran Anda agar jadwal segera diamankan.</p>
                </div>
                
                <form action="{{ route('booking.pembayaran.upload', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate mb-2">Jenis Pembayaran</label>
                            <select name="tipe" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none">
                                <option value="dp" {{ $booking->status === 'dp_terbayar' ? 'disabled' : '' }}>Uang Muka (DP)</option>
                                <option value="pelunasan">Pelunasan / Sisa Pembayaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate mb-2">Jumlah Transfer (Rp)</label>
                            <input type="number" name="jumlah" required placeholder="Contoh: 500000" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate mb-2">Metode Transfer</label>
                            <select name="metode_bayar" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none">
                                <option value="transfer">Bank Transfer</option>
                                <option value="qris">QRIS Digital</option>
                                <option value="cash">Tunai / On Site</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate mb-2">File Gambar Bukti <span class="text-gray-400 font-normal">(Max 2MB)</span></label>
                            <input type="file" name="bukti_bayar" required class="w-full text-sm text-slate file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-slate hover:file:bg-gray-200 transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto bg-primary text-white font-medium px-8 py-3.5 rounded-lg hover:bg-opacity-90 transition-all text-sm shadow-sm flex items-center justify-center sm:justify-start gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        @endif

    @endif
</div>
@endsection