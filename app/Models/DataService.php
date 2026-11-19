<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataService extends Model
{
    protected $fillable = [
        'daftar_service_id',
        'estimasi_service',
        'nama_mekanik',
        'sparepart_pengganti'
    ];

    public function daftarService()
    {
        return $this->belongsTo(DaftarService::class);
    }

    public function pembayaranService()
    {
        return $this->hasOne(PembayaranService::class);
    }
}
