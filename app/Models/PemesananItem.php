<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananItem extends Model
{
    
    protected $table = 'pemesanan_items';
    protected $fillable = ['id_pemesanan', 'id_jenis_gigi'];

    public function jenisGigi()
    {
        return $this->belongsTo(JenisGigi::class, 'id_jenis_gigi')->withTrashed();
    }
}
