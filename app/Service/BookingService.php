<?php

namespace App\Service;

use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class BookingService
{
    /**
     * Membuat reservasi baru dengan proteksi race condition menggunakan database transaction & pessimistic locking.
     * * @param array $data
     * @return Booking
     * @throws Exception
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // 1. Ambil data fasilitas dan kunci baris untuk mencegah perubahan harga mendadak saat dibooking
            $fasilitas = Fasilitas::where('id', $data['fasilitas_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($fasilitas->status !== 'aktif') {
                throw new Exception('Maaf, fasilitas ini sedang tidak aktif dan tidak dapat dipesan.');
            }

            // 2. Deteksi bentrok jadwal menggunakan pessimistic locking (lockForUpdate)
            $isBentrok = Booking::where('fasilitas_id', $data['fasilitas_id'])
                ->where('tanggal_booking', $data['tanggal_booking'])
                ->whereIn('status', ['menunggu_pembayaran', 'dp_terbayar', 'lunas'])
                ->where(function ($query) use ($data) {
                    $query->where(function ($q) use ($data) {
                        $q->where('jam_mulai', '<', $data['jam_selesai'])
                            ->where('jam_selesai', '>', $data['jam_mulai']);
                    });
                })
                ->lockForUpdate()
                ->exists();

            if ($isBentrok) {
                throw new Exception('Maaf, slot waktu pada tanggal tersebut sudah dipesan oleh pelanggan lain.');
            }

            // 3. Hitung durasi dan kalkulasi total harga
            $waktuMulai = strtotime($data['jam_mulai']);
            $waktuSelesai = strtotime($data['jam_selesai']);
            $durasiMenit = ($waktuSelesai - $waktuMulai) / 60;

            if ($durasiMenit < $fasilitas->durasi_minimum_menit) {
                throw new Exception('Durasi pemesanan kurang dari batas minimum ' . $fasilitas->durasi_minimum_menit . ' menit.');
            }

            $durasiJam = $durasiMenit / 60;
            $totalHarga = $durasiJam * $fasilitas->harga_per_jam;

            // 4. Generate Kode Booking Unik (Contoh: BK-20260709-A8F3)
            $tanggalClean = str_replace('-', '', $data['tanggal_booking']);
            do {
                $kodeBooking = 'BK-' . $tanggalClean . '-' . strtoupper(Str::random(4));
                $kodeExist = Booking::where('kode_booking', $kodeBooking)->exists();
            } while ($kodeExist);

            // 5. Simpan Data Booking
            return Booking::create([
                'kode_booking' => $kodeBooking,
                'fasilitas_id' => $data['fasilitas_id'],
                'nama_pelanggan' => $data['nama_pelanggan'],
                'no_hp' => $data['no_hp'],
                'email' => $data['email'] ?? null,
                'tanggal_booking' => $data['tanggal_booking'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'total_harga' => $totalHarga,
                'dp_dibayar' => 0,
                'sisa_bayar' => $totalHarga,
                'status' => 'menunggu_pembayaran',
                'catatan' => $data['catatan'] ?? null,
            ]);
        });
    }

    /**
     * Memproses upload pembayaran dari pelanggan (Guest) atau pencatatan langsung oleh Admin.
     * * @param int $bookingId
     * @param array $data
     * @return Pembayaran
     */
    public function submitPembayaran(int $bookingId, array $data): Pembayaran
    {
        $booking = Booking::findOrFail($bookingId);

        $pathBukti = null;
        if (isset($data['bukti_bayar']) && $data['bukti_bayar'] instanceof \Illuminate\Http\UploadedFile) {
            $pathBukti = $data['bukti_bayar']->store('bukti_pembayaran', 'public');
        }

        return Pembayaran::create([
            'booking_id' => $booking->id,
            'tipe' => $data['tipe'], // dp / pelunasan
            'jumlah' => $data['jumlah'],
            'metode_bayar' => $data['metode_bayar'],
            'bukti_bayar' => $pathBukti,
            'status_verifikasi' => 'menunggu',
            'diverifikasi_oleh' => null,
        ]);
    }

    /**
     * Verifikasi pembayaran oleh Admin. 
     * Otomatisasi update status booking ditangani oleh PembayaranObserver.
     * * @param int $pembayaranId
     * @param int $adminId
     * @return bool
     */
    public function verifikasiPembayaran(int $pembayaranId, int $adminId): bool
    {
        return DB::transaction(function () use ($pembayaranId, $adminId) {
            $pembayaran = Pembayaran::where('id', $pembayaranId)->lockForUpdate()->firstOrFail();

            if ($pembayaran->status_verifikasi !== 'menunggu') {
                return false;
            }

            $pembayaran->update([
                'status_verifikasi' => 'terverifikasi',
                'diverifikasi_oleh' => $adminId
            ]);

            return true;
        });
    }
}
