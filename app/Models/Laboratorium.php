<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laboratorium extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'laboratorium';
    protected $fillable = ['nama_lab', 'alamat', 'kontak', 'is_aktif'];

    /**
     * RELASI: Ke Banyak Pesanan Laboratorium
     */
    public function pesananLab(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PesananLab::class, 'id_lab');
    }
}
