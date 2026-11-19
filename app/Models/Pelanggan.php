<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'no_hp',
        'alamat_pelanggan',
        'pekerjaan'
    ];

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function daftarServices()
    {
        return $this->hasMany(DaftarService::class);
    }
}
