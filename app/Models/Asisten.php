<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asisten extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'asisten';
    protected $fillable = ['nama', 'kontak', 'is_aktif'];
}
