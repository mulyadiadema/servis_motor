<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranService extends Model
{
    protected $fillable = [
        'data_service_id',
        'jumlah_biaya_servis',
        'jenis_pembayaran',
        'keterangan'
    ];

    public function dataService()
    {
        return $this->belongsTo(DataService::class);
    }
}
