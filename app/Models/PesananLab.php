<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PesananLab extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan_lab';

    protected $fillable = [
        'id_pemesanan',
        'id_lab',
        'tanggal_dikirim',
        'estimasi_selesai',
        'tanggal_tiba',
        'biaya_lab',
        'status_bayar_lab',
    ];

    protected $casts = [
        'tanggal_dikirim' => 'date',
        'estimasi_selesai' => 'date',
        'tanggal_tiba' => 'date',
        'biaya_lab' => 'decimal:2',
    ];

    /**
     * RELASI: Ke Pemesanan Induk (Pasien)
     */
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    /**
     * RELASI: Ke Vendor Laboratorium
     */
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Laboratorium::class, 'id_lab');
    }

    /**
     * SCOPE: Notifikasi H-3 s.d Jatuh Tempo Lab
     */
    public function scopeHampirJatuhTempo($query)
    {
        return $query->whereNull('tanggal_tiba')
                     ->whereDate('estimasi_selesai', '<=', Carbon::now()->addDays(3));
    }

    /**
     * ACCESSOR: Cek apakah pengerjaan lab terlambat
     */
    public function getIsTerlambatAttribute(): bool
    {
        if ($this->tanggal_tiba !== null) {
            return false;
        }

        return Carbon::now()->startOfDay()->gt(Carbon::parse($this->estimasi_selesai));
    }
}

