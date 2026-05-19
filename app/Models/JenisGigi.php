<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisGigi extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'jenis_gigi';
    protected $fillable = ['kode_gigi', 'nama_jenis', 'estimasi_biaya','is_aktif'];
}
