<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Booking;
use App\Models\User;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $fillable = [
        'booking_id', 'tipe', 'jumlah', 'metode_bayar', 
        'bukti_bayar', 'status_verifikasi', 'diverifikasi_oleh'
    ];

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
    public function verifikator() {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
