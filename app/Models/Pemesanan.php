<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Carbon\Carbon;

class Pemesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemesanan';

    protected $fillable = [
        'no_pemesanan',
        'id_pemeriksaan',
        'harga_pasien',
        'diskon',
        'biaya_pemasangan',
        'status_bayar_pasien',
        'status_booking',
    ];

    protected $casts = [
        'harga_pasien' => 'decimal:2',
        'diskon' => 'decimal:2',
        'biaya_pemasangan' => 'decimal:2',
    ];

    /**
     * RELASI: Ke Pemeriksaan (Induk)
     */
    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }

    /**
     * RELASI: Ke Operasional Laboratorium (1:1)
     */
    public function pesananLab(): HasOne
    {
        return $this->hasOne(PesananLab::class, 'id_pemesanan');
    }

    /**
     * RELASI: Akses langsung ke Laboratorium melalui pesananLab (hasOneThrough)
     * Mempertahankan kompatibilitas kode / eager loading ($pemesanan->lab)
     */
    public function lab(): HasOneThrough
    {
        return $this->hasOneThrough(Laboratorium::class, PesananLab::class, 'id_pemesanan', 'id', 'id', 'id_lab');
    }

    /**
     * RELASI: Ke Item Pemesanan (PemesananItem)
     */
    public function items(): HasMany
    {
        return $this->hasMany(PemesananItem::class, 'id_pemesanan');
    }

    /**
     * RELASI: Direct BelongsToMany ke Master JenisGigi via Pivot
     */
    public function jenisGigi(): BelongsToMany
    {
        return $this->belongsToMany(JenisGigi::class, 'pemesanan_items', 'id_pemesanan', 'id_jenis_gigi')
                    ->withPivot('id', 'harga_jual_pasien', 'biaya_modal_lab')
                    ->withTimestamps();
    }

    /**
     * SCOPE: Notifikasi H-3 s.d Jatuh Tempo Pesanan Lab
     */
    public function scopeHampirJatuhTempo($query)
    {
        return $query->whereHas('pesananLab', function ($q) {
            $q->whereNull('tanggal_tiba')
              ->whereDate('estimasi_selesai', '<=', Carbon::now()->addDays(3));
        });
    }

    /**
     * ACCESSOR: Cek keterlambatan pengerjaan lab
     */
    public function getIsTerlambatAttribute(): bool
    {
        return $this->pesananLab ? $this->pesananLab->is_terlambat : false;
    }

    /**
     * ACCESSOR: Menghitung total tagihan bersih ke pasien
     * Formula: (Harga Gigi + Biaya Pasang) - Diskon
     */
    public function getTotalTagihanPasienAttribute(): float
    {
        return (float) (($this->harga_pasien + $this->biaya_pemasangan) - $this->diskon);
    }
}
