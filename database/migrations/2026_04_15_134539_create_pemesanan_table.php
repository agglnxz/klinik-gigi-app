<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pemesanan', 20)->unique();
            $table->date('tanggal_dikirim');
            $table->date('estimasi_selesai');
            $table->decimal('biaya_lab', 15, 2);
            $table->decimal('harga_pasien', 15, 2);

            // Sesuai dengan opsi baru di Form Blade
            $table->enum('status_bayar_lab', ['belum_lunas', 'sudah_lunas'])->default('belum_lunas');
            $table->enum('status_pemesanan', ['dalam_proses', 'tiba_di_klinik', 'dibatalkan', 'selesai'])->default('dalam_proses');

            // Relasi Induk (id_jenis_gigi dihapus karena dipindahkan ke tabel pivot pemesanan_items)
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->unsignedBigInteger('id_lab');

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan')->onDelete('cascade');
            $table->foreign('id_lab')->references('id')->on('laboratorium')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
