<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use SoftDeletes;

    protected $table = 'pasien';

    protected $fillable = [
        'no_rm',
        'nama',
        'kontak',
        'jenis_kelamin',
        'alamat',
        'is_aktif'
    ];
}