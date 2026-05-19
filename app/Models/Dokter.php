<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dokter extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'dokter';
    protected $fillable = ['nama', 'kontak', 'is_aktif'];
}
