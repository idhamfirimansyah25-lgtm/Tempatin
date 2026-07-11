<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fasilitas;
use App\Models\Pembayaran;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'kode_booking', 'fasilitas_id', 'nama_pelanggan', 'no_hp', 
        'email', 'tanggal_booking', 'jam_mulai', 'jam_selesai', 
        'total_harga', 'dp_dibayar', 'sisa_bayar', 'status', 'catatan'
    ];
    protected $casts = ['tanggal_booking' => 'date'];

    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class, 'fasilitas_id');
    }
    public function pembayarans() {
        return $this->hasMany(Pembayaran::class, 'booking_id');
    }
}
