<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemeriksaan extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'pemeriksaan';

    protected $fillable = [
        'no_pemeriksaan', 'tanggal', 'catatan', 'id_pasien', 'id_dokter', 'id_asisten'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien')->withTrashed();
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter')->withTrashed();
    }

    public function asisten()
    {
        return $this->belongsTo(Asisten::class, 'id_asisten')->withTrashed();
    }
}
