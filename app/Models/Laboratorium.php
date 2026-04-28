<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratorium extends Model
{
    use SoftDeletes;
    
    protected $table = 'laboratorium';
    protected $fillable = ['nama_lab', 'alamat', 'kontak', 'is_aktif'];
}
