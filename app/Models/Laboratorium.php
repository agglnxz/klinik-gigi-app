<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorium extends Model
{
    protected $table = 'laboratorium';
    protected $fillable = ['nama_lab', 'alamat', 'kontak', 'is_aktif'];
}
