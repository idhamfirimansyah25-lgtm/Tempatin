<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriFasilitas extends Model
{
    protected $table = 'kategori_fasilitas';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function fasilitas() {
        return $this->hasMany(Fasilitas::class, 'kategori_fasilitas_id');
    }
}
