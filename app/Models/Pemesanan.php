<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pemesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemesanan';

    // id_jenis_gigi DIHAPUS dari sini
    protected $fillable = [
        'no_pemesanan',
        'id_pemeriksaan',
        'id_lab',
        'tanggal_dikirim',
        'estimasi_selesai',
        'biaya_lab',
        'harga_pasien',
        'status_bayar_lab',
        'status_pemesanan',
    ];

    // Mengubah string tanggal menjadi objek Carbon secara otomatis
    protected $casts = [
        'tanggal_dikirim' => 'date',
        'estimasi_selesai' => 'date',
    ];

    /**
     * RELASI: Ke Pemeriksaan (Induk)
     */
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }

    /**
     * RELASI: Ke Laboratorium (Induk)
     */
    public function lab()
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab');
    }

    /**
     * RELASI BARU: Ke Banyak Item Gigi (Anak)
     * Ini menggantikan relasi belongsTo(JenisGigi) yang lama
     */
    public function items()
    {
        return $this->hasMany(PemesananItem::class, 'id_pemesanan');
    }

    /**
     * SCOPE: Notifikasi H-3 s.d Overdue
     * Memastikan status pemesanan sesuai dengan ENUM baru di migrasi (dalam_proses)
     */
    public function scopeHampirJatuhTempo($query)
    {
        return $query->whereNotIn('status_pemesanan', ['tiba_di_klinik', 'selesai', 'dibatalkan'])
                     ->whereDate('estimasi_selesai', '<=', Carbon::now()->addDays(3));
    }
}
