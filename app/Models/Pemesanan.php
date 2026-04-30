<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemesanan extends Model
{
    use SoftDeletes;

    protected $table = 'pemesanan';

    protected $fillable = [
        'no_pemesanan',
        'tanggal_dikirim',
        'estimasi_selesai',
        'biaya_lab',
        'harga_pasien',
        'status_bayar_lab',
        'status_pemesanan',
        'id_pemeriksaan',
        'id_lab',
        'id_jenis_gigi'
    ];

    // Relasi
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }

    public function lab()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab');
    }

    public function jenisGigi()
    {
        return $this->belongsTo(JenisGigi::class, 'id_jenis_gigi');
    }
}