@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 lg:gap-10 h-[85vh]">
    
    <div class="w-full lg:w-3/5 h-full overflow-y-auto pr-2 pb-6 space-y-6">
        <div class="mb-6">
            <h2 class="font-manrope text-3xl font-bold text-slate">Ketersediaan Fasilitas</h2>
            <p class="text-gray-500 text-sm mt-1">Pilih ruang atau fasilitas yang ingin Anda reservasi hari ini.</p>
        </div>

        @foreach($fasilitas as $item)
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 transition-shadow hover:shadow-md">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-manrope text-xl font-bold text-slate">{{ $item->nama }}</h3>
                    <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full tracking-wide">
                        {{ $item->kategoriFasilitas->nama_kategori }}
                    </span>
                </div>
                
                <p class="text-sm text-gray-500 leading-relaxed mb-6">{{ $item->deskripsi }}</p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-5 border-t border-dashed border-gray-200">
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Harga per Jam</span>
                        <span class="block text-sm font-bold text-slate">Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Kapasitas</span>
                        <span class="block text-sm font-semibold text-slate">{{ $item->kapasitas ?? '-' }} Orang</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Min. Reservasi</span>
                        <span class="block text-sm font-semibold text-slate">{{ $item->durasi_minimum_menit }} Menit</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 mb-1">Jam Operasi</span>
                        <span class="block text-sm font-semibold text-slate">{{ date('H:i', strtotime($item->jam_buka)) }} - {{ date('H:i', strtotime($item->jam_tutup)) }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="w-full lg:w-2/5 h-full overflow-y-auto pr-2 pb-6">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
            <h2 class="font-manrope text-2xl font-bold text-slate mb-6">Detail Reservasi</h2>
            
            <form action="{{ route('booking.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-slate mb-2">Pilih Fasilitas</label>
                    <select name="fasilitas_id" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none appearance-none">
                        <option value="">-- Tentukan Pilihan --</option>
                        @foreach($fasilitas as $item)
                            <option value="{{ $item->id }}" {{ old('fasilitas_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate mb-2">Nama Pemesan</label>
                        <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate mb-2">Nomor WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xx..." class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate mb-2">Alamat Email <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                </div>

                <div class="bg-background rounded-lg p-5 border border-gray-100 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate mb-2">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_booking" value="{{ old('tanggal_booking') }}" required class="w-full bg-white border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate mb-2">Waktu Mulai</label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required class="w-full bg-white border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate mb-2">Waktu Selesai</label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" required class="w-full bg-white border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate mb-2">Catatan Khusus</label>
                    <textarea name="catatan" rows="3" placeholder="Contoh: Tolong siapkan proyektor..." class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none resize-none">{{ old('catatan') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-primary text-white font-medium py-3.5 rounded-lg hover:bg-opacity-90 transition-all text-sm shadow-sm flex justify-center items-center gap-2">
                        Jadwalkan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection