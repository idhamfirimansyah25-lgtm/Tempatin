<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriFasilitas;
use App\Models\Booking;
use App\Models\JamOperasionalKhusus;


class Fasilitas extends Model
{
    protected $table = 'fasilitas';
    protected $fillable = [
        'kategori_fasilitas_id', 'nama', 'deskripsi', 'kapasitas', 
        'harga_per_jam', 'durasi_minimum_menit', 'jam_buka', 'jam_tutup', 
        'gambar', 'status'
    ];

    public function kategoriFasilitas() {
        return $this->belongsTo(KategoriFasilitas::class, 'kategori_fasilitas_id');
    }
    public function bookings() {
        return $this->hasMany(Booking::class, 'fasilitas_id');
    }
    public function jamOperasionalKhusus() {
        return $this->hasMany(JamOperasionalKhusus::class, 'fasilitas_id');
    }
}
