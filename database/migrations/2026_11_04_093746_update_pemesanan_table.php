<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            // 1. Drop foreign key constraint id_lab terlebih dahulu
            $table->dropForeign(['id_lab']);

            // 2. Hapus kolom-kolom operasional lab & status lama
            $table->dropColumn([
                'id_lab',
                'biaya_lab',
                'tanggal_dikirim',
                'estimasi_selesai',
                'status_bayar_lab',
                'status_pemesanan',
            ]);

            // 3. Tambahkan kolom baru yang berfokus pada transaksi & booking pasien
            $table->decimal('biaya_pemasangan', 15, 2)->default(0)->after('diskon');
            $table->enum('status_bayar_pasien', ['belum_lunas', 'sudah_lunas'])->default('belum_lunas')->after('biaya_pemasangan');
            $table->enum('status_booking', ['pending', 'proses_lab', 'siap_pasang', 'selesai'])->default('pending')->after('status_bayar_pasien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn([
                'biaya_pemasangan',
                'status_bayar_pasien',
                'status_booking',
            ]);

            $table->date('tanggal_dikirim')->nullable()->after('no_pemesanan');
            $table->date('estimasi_selesai')->nullable()->after('tanggal_dikirim');
            $table->decimal('biaya_lab', 15, 2)->default(0)->after('estimasi_selesai');
            $table->enum('status_bayar_lab', ['belum_lunas', 'sudah_lunas'])->default('belum_lunas')->after('diskon');
            $table->enum('status_pemesanan', ['dalam_proses', 'tiba_di_klinik', 'selesai'])->default('dalam_proses')->after('status_bayar_lab');

            $table->unsignedBigInteger('id_lab')->nullable()->after('id_pemeriksaan');
            $table->foreign('id_lab')->references('id')->on('laboratorium')->onDelete('cascade');
        });
    }
};
