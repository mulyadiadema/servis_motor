<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarService extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'pelanggan_id',
        'keluhan_kendaraan',
        'tanggal_servis'
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function dataService()
    {
        return $this->hasOne(DataService::class);
    }
}