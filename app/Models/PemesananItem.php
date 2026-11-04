<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemesananItem extends Model
{
    protected $table = 'pemesanan_items';

    protected $fillable = [
        'id_pemesanan',
        'id_jenis_gigi',
        'harga_jual_pasien',
        'biaya_modal_lab',
    ];

    protected $casts = [
        'harga_jual_pasien' => 'decimal:2',
        'biaya_modal_lab' => 'decimal:2',
    ];

    /**
     * RELASI: Ke Pemesanan Induk
     */
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    /**
     * RELASI: Ke Master Jenis Gigi
     */
    public function jenisGigi(): BelongsTo
    {
        return $this->belongsTo(JenisGigi::class, 'id_jenis_gigi')->withTrashed();
    }

    /**
     * ACCESSOR: Margin profit per item gigi
     */
    public function getMarginProfitAttribute(): float
    {
        return (float) ($this->harga_jual_pasien - ($this->biaya_modal_lab ?? 0));
    }
}
