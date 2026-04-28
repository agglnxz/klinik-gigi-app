<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisGigi extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_gigi';
    protected $fillable = ['kode_gigi', 'nama_jenis', 'estimasi_biaya','is_aktif'];
}
