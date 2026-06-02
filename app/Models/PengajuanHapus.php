<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class PengajuanHapus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengajuan_hapus';

    protected $fillable = [
        'nama_tabel',
        'id_referensi',
        'nama_data',
        'alasan_hapus',
        'status_approval',
        'id_pemohon',
    ];

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'id_pemohon');
    }
}
