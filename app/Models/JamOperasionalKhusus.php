<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fasilitas;

class JamOperasionalKhusus extends Model
{
    protected $table = 'jam_operasional_khusus';
    protected $fillable = [
        'fasilitas_id', 'tanggal', 'tutup_penuh', 
        'jam_buka_override', 'jam_tutup_override', 'keterangan'
    ];
    protected $casts = ['tutup_penuh' => 'boolean', 'tanggal' => 'date'];

    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class, 'fasilitas_id');
    }
}
